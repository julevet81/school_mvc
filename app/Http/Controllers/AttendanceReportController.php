<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AuthorizesDashboardRequests;
use App\Http\Requests\Attendance\AttendanceReportRequest;
use App\Models\AttendanceSession;
use App\Models\Branch;
use App\Models\School;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentAttendance;
use Illuminate\Contracts\View\View;

class AttendanceReportController extends Controller
{
    use AuthorizesDashboardRequests;

    public function index(AttendanceReportRequest $request): View
    {
        $this->ensureAuthenticated();

        $filters = $request->validated();
        $query = StudentAttendance::query()
            ->with(['student:id,student_no,full_name', 'session:id,school_id,branch_id,section_id,attendance_date', 'session.branch:id,name', 'session.section:id,name']);

        $this->applyFilters($query, $filters);

        $rows = $query->latest('id')->paginate(25)->withQueryString();

        $summaryQuery = StudentAttendance::query();
        $this->applyFilters($summaryQuery, $filters);

        $summary = [
            'total' => (clone $summaryQuery)->count(),
            'present' => (clone $summaryQuery)->where('status', 'present')->count(),
            'absent' => (clone $summaryQuery)->where('status', 'absent')->count(),
            'late' => (clone $summaryQuery)->where('status', 'late')->count(),
            'excused' => (clone $summaryQuery)->where('status', 'excused')->count(),
        ];

        $latestSessions = AttendanceSession::query()->latest('attendance_date')->take(5)->get(['id', 'attendance_date', 'type']);
        $schools = School::query()->orderBy('name')->get(['id', 'name']);
        $branches = Branch::query()->orderBy('name')->get(['id', 'name', 'school_id']);
        $sections = Section::query()->orderBy('name')->get(['id', 'name']);
        $students = Student::query()->orderBy('full_name')->get(['id', 'full_name']);

        return view('attendance-reports.index', compact('rows', 'summary', 'latestSessions', 'schools', 'branches', 'sections', 'students', 'filters'));
    }

    public function print(AttendanceReportRequest $request): View
    {
        $this->ensureAuthenticated();

        $filters = $request->validated();
        $query = StudentAttendance::query()
            ->with(['student:id,student_no,full_name', 'session:id,branch_id,section_id,attendance_date', 'session.branch:id,name', 'session.section:id,name']);

        $this->applyFilters($query, $filters);

        $rows = $query->latest('id')->get();

        return view('attendance-reports.print', compact('rows', 'filters'));
    }

    private function applyFilters($query, array $filters): void
    {
        $query
            ->when($filters['student_id'] ?? null, fn ($q, $value) => $q->where('student_id', $value))
            ->when($filters['status'] ?? null, fn ($q, $value) => $q->where('status', $value))
            ->when($filters['from_date'] ?? null, fn ($q, $value) => $q->whereHas('session', fn ($sessionQuery) => $sessionQuery->whereDate('attendance_date', '>=', $value)))
            ->when($filters['to_date'] ?? null, fn ($q, $value) => $q->whereHas('session', fn ($sessionQuery) => $sessionQuery->whereDate('attendance_date', '<=', $value)))
            ->when($filters['school_id'] ?? null, fn ($q, $value) => $q->whereHas('session', fn ($sessionQuery) => $sessionQuery->where('school_id', $value)))
            ->when($filters['branch_id'] ?? null, fn ($q, $value) => $q->whereHas('session', fn ($sessionQuery) => $sessionQuery->where('branch_id', $value)))
            ->when($filters['section_id'] ?? null, fn ($q, $value) => $q->whereHas('session', fn ($sessionQuery) => $sessionQuery->where('section_id', $value)));
    }
}
