<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\AbsenceNotificationLog;
use App\Models\AttendanceSession;
use Illuminate\Support\Facades\Mail;
use Throwable;

class AbsenceNotificationService
{
    public function sendForSession(AttendanceSession $session): array
    {
        $sent = 0;
        $failed = 0;
        $skipped = 0;

        $session->loadMissing([
            'studentAttendances.student.parents',
            'school:id,name',
            'branch:id,name',
            'section.classroom.grade',
        ]);

        foreach ($session->studentAttendances->where('status', 'absent') as $attendance) {
            $parents = $attendance->student->parents;

            if ($parents->isEmpty()) {
                AbsenceNotificationLog::query()->create([
                    'attendance_session_id' => $session->id,
                    'student_id' => $attendance->student_id,
                    'channel' => 'email',
                    'status' => 'skipped',
                    'message' => $this->messageFor($session, $attendance->student->full_name),
                    'error_message' => 'No linked parent found.',
                ]);
                $skipped++;
                continue;
            }

            foreach ($parents as $parent) {
                $message = $this->messageFor($session, $attendance->student->full_name, $parent->full_name);

                if (blank($parent->email)) {
                    AbsenceNotificationLog::query()->create([
                        'attendance_session_id' => $session->id,
                        'student_id' => $attendance->student_id,
                        'parent_id' => $parent->id,
                        'channel' => 'email',
                        'recipient' => $parent->phone,
                        'status' => 'skipped',
                        'message' => $message,
                        'error_message' => 'Parent email is missing.',
                    ]);
                    $skipped++;
                    continue;
                }

                try {
                    Mail::raw($message, function ($mail) use ($parent, $attendance): void {
                        $mail->to($parent->email)
                            ->subject('Student absence notice: '.$attendance->student->full_name);
                    });

                    AbsenceNotificationLog::query()->create([
                        'attendance_session_id' => $session->id,
                        'student_id' => $attendance->student_id,
                        'parent_id' => $parent->id,
                        'channel' => 'email',
                        'recipient' => $parent->email,
                        'status' => 'sent',
                        'message' => $message,
                        'sent_at' => now(),
                    ]);
                    $sent++;
                } catch (Throwable $throwable) {
                    AbsenceNotificationLog::query()->create([
                        'attendance_session_id' => $session->id,
                        'student_id' => $attendance->student_id,
                        'parent_id' => $parent->id,
                        'channel' => 'email',
                        'recipient' => $parent->email,
                        'status' => 'failed',
                        'message' => $message,
                        'error_message' => $throwable->getMessage(),
                    ]);
                    $failed++;
                }
            }
        }

        return compact('sent', 'failed', 'skipped');
    }

    private function messageFor(AttendanceSession $session, string $studentName, ?string $parentName = null): string
    {
        $sectionName = $session->section?->classroom?->name.' - '.$session->section?->name;

        return trim(
            'Dear '.($parentName ?: 'Parent').",\n".
            'This is to inform you that '.$studentName.' was marked absent on '.$session->attendance_date?->format('Y-m-d').".\n".
            'School: '.($session->school?->name ?? '-')."\n".
            'Branch: '.($session->branch?->name ?? '-')."\n".
            'Section: '.trim((string) $sectionName)."\n".
            'Please contact the school administration if this absence needs clarification.'
        );
    }
}
