<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AuthorizesDashboardRequests;
use App\Models\AcademicYear;
use App\Models\AttendanceSession;
use App\Models\Branch;
use App\Models\Employee;
use App\Models\Fee;
use App\Models\Invoice;
use App\Models\School;
use App\Models\StudentAttendance;
use App\Models\Student;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    use AuthorizesDashboardRequests;

    public function __invoke(): View
    {
        $this->ensureAuthenticated();

        $stats = [
            'schools' => School::query()->count(),
            'branches' => Branch::query()->count(),
            'students' => Student::query()->count(),
            'employees' => Employee::query()->count(),
            'academic_years' => AcademicYear::query()->count(),
            'fees' => Fee::query()->count(),
            'active_invoices' => Invoice::query()->whereIn('status', ['draft', 'issued', 'partial'])->count(),
            'revenue_due' => (float) Invoice::query()->sum('total'),
            'revenue_paid' => (float) Invoice::query()->sum('paid_amount'),
            'today_absences' => StudentAttendance::query()->where('status', 'absent')->whereHas('session', fn ($query) => $query->whereDate('attendance_date', today()))->count(),
            'today_attendance_sessions' => AttendanceSession::query()->whereDate('attendance_date', today())->count(),
        ];

        $recentSchools = School::query()
            ->withCount('branches')
            ->latest()
            ->take(5)
            ->get(['id', 'name', 'code', 'is_active', 'created_at']);

        $recentInvoices = Invoice::query()
            ->with(['student:id,full_name', 'branch:id,name'])
            ->latest()
            ->take(5)
            ->get(['id', 'branch_id', 'student_id', 'invoice_no', 'total', 'paid_amount', 'status', 'due_date']);

        $recentStudents = Student::query()
            ->with(['school:id,name', 'branch:id,name'])
            ->latest()
            ->take(5)
            ->get(['id', 'school_id', 'branch_id', 'student_no', 'full_name', 'status', 'enrollment_date']);

        return view('dashboard.home', compact('stats', 'recentSchools', 'recentInvoices', 'recentStudents'));
    }
}
