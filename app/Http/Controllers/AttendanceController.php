<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AuthorizesDashboardRequests;
use App\Http\Requests\Attendance\StoreAttendanceSessionRequest;
use App\Http\Requests\Attendance\UpdateAttendanceSessionRequest;
use App\Models\AcademicYear;
use App\Models\AttendanceSession;
use App\Models\Branch;
use App\Models\School;
use App\Models\Section;
use App\Models\StudentEnrollment;
use App\Services\AbsenceNotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    use AuthorizesDashboardRequests;

    public function index(Request $request): View
    {
        $this->ensureAuthenticated();

        $sessions = AttendanceSession::query()
            ->with(['school:id,name', 'branch:id,name', 'section:id,classroom_id,name', 'section.classroom:id,name'])
            ->withCount([
                'studentAttendances as absent_count' => fn ($query) => $query->where('status', 'absent'),
                'studentAttendances as present_count' => fn ($query) => $query->where('status', 'present'),
            ])
            ->latest('attendance_date')
            ->paginate(12)
            ->withQueryString();

        return view('attendances.index', compact('sessions'));
    }

    public function create(): View
    {
        $this->ensureAuthenticated();

        return view('attendances.create', $this->formData());
    }

    public function store(StoreAttendanceSessionRequest $request): RedirectResponse
    {
        $session = DB::transaction(function () use ($request): AttendanceSession {
            $data = $request->validated();

            $session = AttendanceSession::query()->create([
                'school_id' => $data['school_id'],
                'branch_id' => $data['branch_id'],
                'academic_year_id' => $data['academic_year_id'] ?? null,
                'section_id' => $data['section_id'],
                'attendance_date' => $data['attendance_date'],
                'type' => $data['type'],
                'method' => $data['method'],
                'recorded_by' => auth()->id(),
            ]);

            foreach ($data['attendances'] as $attendance) {
                $session->studentAttendances()->create([
                    'student_id' => $attendance['student_id'],
                    'status' => $attendance['status'],
                    'remarks' => $attendance['remarks'] ?? null,
                ]);
            }

            return $session;
        });

        return redirect()->route('attendances.edit', $session)->with('success', __('app.messages.created', ['resource' => __('app.resources.attendance')]));
    }

    public function edit(AttendanceSession $attendance): View
    {
        $this->ensureAuthenticated();

        $attendance->load([
            'studentAttendances.student',
            'absenceNotificationLogs.parent',
        ]);

        return view('attendances.edit', array_merge($this->formData((int) $attendance->section_id), [
            'attendanceSession' => $attendance,
        ]));
    }

    public function update(UpdateAttendanceSessionRequest $request, AttendanceSession $attendance): RedirectResponse
    {
        DB::transaction(function () use ($request, $attendance): void {
            $data = $request->validated();

            $attendance->update([
                'school_id' => $data['school_id'],
                'branch_id' => $data['branch_id'],
                'academic_year_id' => $data['academic_year_id'] ?? null,
                'section_id' => $data['section_id'],
                'attendance_date' => $data['attendance_date'],
                'type' => $data['type'],
                'method' => $data['method'],
                'recorded_by' => auth()->id(),
            ]);

            $attendance->studentAttendances()->delete();

            foreach ($data['attendances'] as $row) {
                $attendance->studentAttendances()->create([
                    'student_id' => $row['student_id'],
                    'status' => $row['status'],
                    'remarks' => $row['remarks'] ?? null,
                ]);
            }
        });

        return redirect()->route('attendances.edit', $attendance)->with('success', __('app.messages.updated', ['resource' => __('app.resources.attendance')]));
    }

    public function notifyAbsences(AttendanceSession $attendance, AbsenceNotificationService $service): RedirectResponse
    {
        $this->ensureAuthenticated();

        $result = $service->sendForSession($attendance);

        return redirect()->route('attendances.edit', $attendance)->with(
            'success',
            __('app.messages.absence_notifications_sent', $result)
        );
    }

    public function print(AttendanceSession $attendance): View
    {
        $this->ensureAuthenticated();

        $attendance->load([
            'school:id,name',
            'branch:id,name',
            'section.classroom:id,name',
            'studentAttendances.student:id,student_no,full_name',
        ]);

        return view('attendances.print', compact('attendance'));
    }

    private function formData(?int $sectionId = null): array
    {
        $schools = School::query()->orderBy('name')->get(['id', 'name']);
        $branches = Branch::query()->orderBy('name')->get(['id', 'school_id', 'name']);
        $academicYears = AcademicYear::query()->orderByDesc('start_date')->get(['id', 'school_id', 'branch_id', 'name']);
        $sections = Section::query()
            ->with('classroom:id,name')
            ->orderBy('name')
            ->get(['id', 'classroom_id', 'name']);

        $selectedSectionId = $sectionId ?? optional($sections->first())->id;
        $students = collect();

        if ($selectedSectionId !== null) {
            $students = StudentEnrollment::query()
                ->with('student:id,student_no,full_name')
                ->where('section_id', $selectedSectionId)
                ->where('status', 'enrolled')
                ->get(['id', 'student_id', 'section_id'])
                ->pluck('student');
        }

        return compact('schools', 'branches', 'academicYears', 'sections', 'students', 'selectedSectionId');
    }
}
