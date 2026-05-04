<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\AdmissionApplication;
use App\Models\AttendanceSession;
use App\Models\Branch;
use App\Models\Classroom;
use App\Models\CrmLead;
use App\Models\Employee;
use App\Models\Expense;
use App\Models\Fee;
use App\Models\Grade;
use App\Models\IntegrationConnection;
use App\Models\IntegrationProvider;
use App\Models\Invoice;
use App\Models\KpiSnapshot;
use App\Models\LeaveRequest;
use App\Models\MarketingCampaign;
use App\Models\ParentProfile;
use App\Models\Payment;
use App\Models\PayrollRun;
use App\Models\School;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentAttendance;
use App\Models\StudentEnrollment;
use App\Models\Subject;
use App\Models\Timetable;
use App\Models\TimetableEntry;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $school = School::query()->where('code', 'MAIN')->firstOrFail();
        $branch = Branch::query()->where('school_id', $school->id)->where('code', 'HQ')->firstOrFail();

        $teacher = User::query()->firstOrCreate([
            'email' => 'teacher1@privetschool.local',
        ], [
            'school_id' => $school->id,
            'branch_id' => $branch->id,
            'name' => 'Amina Yusuf',
            'password' => 'Password@123',
            'status' => 'active',
        ]);
        $teacher->syncRoles(['Teacher']);

        $accountant = User::query()->firstOrCreate([
            'email' => 'accountant@privetschool.local',
        ], [
            'school_id' => $school->id,
            'branch_id' => $branch->id,
            'name' => 'James Okon',
            'password' => 'Password@123',
            'status' => 'active',
        ]);
        $accountant->syncRoles(['Accountant']);

        $admissionOfficer = User::query()->firstOrCreate([
            'email' => 'admission@privetschool.local',
        ], [
            'school_id' => $school->id,
            'branch_id' => $branch->id,
            'name' => 'Fatima Bello',
            'password' => 'Password@123',
            'status' => 'active',
        ]);
        $admissionOfficer->syncRoles(['Admission Officer']);

        $year = AcademicYear::query()->updateOrCreate([
            'school_id' => $school->id,
            'branch_id' => $branch->id,
            'name' => '2026/2027',
        ], [
            'start_date' => '2026-09-01',
            'end_date' => '2027-07-31',
            'is_current' => true,
        ]);

        $grade7 = Grade::query()->updateOrCreate([
            'school_id' => $school->id,
            'branch_id' => $branch->id,
            'name' => 'Grade 7',
        ], ['level' => 7]);

        $classA = Classroom::query()->updateOrCreate([
            'school_id' => $school->id,
            'branch_id' => $branch->id,
            'grade_id' => $grade7->id,
            'name' => 'Class A',
        ], ['capacity' => 35]);

        $sectionA = Section::query()->updateOrCreate([
            'classroom_id' => $classA->id,
            'name' => 'Section A',
        ], ['homeroom_teacher_id' => $teacher->id]);

        $math = Subject::query()->updateOrCreate([
            'school_id' => $school->id,
            'branch_id' => $branch->id,
            'code' => 'MTH-7',
        ], ['name' => 'Mathematics', 'credit_hours' => 4, 'is_active' => true]);

        $english = Subject::query()->updateOrCreate([
            'school_id' => $school->id,
            'branch_id' => $branch->id,
            'code' => 'ENG-7',
        ], ['name' => 'English Language', 'credit_hours' => 3, 'is_active' => true]);

        DB::table('subject_teacher_assignments')->updateOrInsert([
            'school_id' => $school->id,
            'branch_id' => $branch->id,
            'academic_year_id' => $year->id,
            'section_id' => $sectionA->id,
            'subject_id' => $math->id,
        ], ['teacher_id' => $teacher->id, 'updated_at' => now(), 'created_at' => now()]);

        DB::table('subject_teacher_assignments')->updateOrInsert([
            'school_id' => $school->id,
            'branch_id' => $branch->id,
            'academic_year_id' => $year->id,
            'section_id' => $sectionA->id,
            'subject_id' => $english->id,
        ], ['teacher_id' => $teacher->id, 'updated_at' => now(), 'created_at' => now()]);

        $parent = ParentProfile::query()->firstOrCreate([
            'school_id' => $school->id,
            'branch_id' => $branch->id,
            'parent_no' => 'P-0001',
        ], [
            'full_name' => 'Maryam Musa',
            'email' => 'parent1@privetschool.local',
            'phone' => '08030000001',
            'portal_enabled' => true,
        ]);

        $student = Student::query()->firstOrCreate([
            'school_id' => $school->id,
            'branch_id' => $branch->id,
            'student_no' => 'STU-0001',
        ], [
            'full_name' => 'Yahya Musa',
            'date_of_birth' => '2013-05-14',
            'gender' => 'male',
            'enrollment_date' => now()->toDateString(),
            'status' => 'active',
        ]);

        $student2 = Student::query()->firstOrCreate([
            'school_id' => $school->id,
            'branch_id' => $branch->id,
            'student_no' => 'STU-0002',
        ], [
            'full_name' => 'Zainab Musa',
            'date_of_birth' => '2012-11-03',
            'gender' => 'female',
            'enrollment_date' => now()->toDateString(),
            'status' => 'active',
        ]);

        $parent->students()->syncWithoutDetaching([
            $student->id => ['relationship' => 'mother', 'is_primary' => true, 'financially_responsible' => true],
            $student2->id => ['relationship' => 'mother', 'is_primary' => true, 'financially_responsible' => true],
        ]);

        StudentEnrollment::query()->updateOrCreate([
            'student_id' => $student->id,
            'academic_year_id' => $year->id,
        ], ['section_id' => $sectionA->id, 'enrolled_at' => now()->toDateString(), 'status' => 'enrolled']);

        StudentEnrollment::query()->updateOrCreate([
            'student_id' => $student2->id,
            'academic_year_id' => $year->id,
        ], ['section_id' => $sectionA->id, 'enrolled_at' => now()->toDateString(), 'status' => 'enrolled']);

        $application = AdmissionApplication::query()->firstOrCreate([
            'school_id' => $school->id,
            'branch_id' => $branch->id,
            'application_no' => 'APP-2026-0001',
        ], [
            'status' => 'accepted',
            'student_first_name' => 'Hassan',
            'student_last_name' => 'Ibrahim',
            'student_date_of_birth' => '2014-02-12',
            'target_grade' => 'Grade 6',
            'submitted_by' => $admissionOfficer->id,
            'reviewed_by' => $admissionOfficer->id,
            'reviewed_at' => now(),
            'registration_fee' => 25000,
        ]);

        DB::table('admission_workflow_logs')->insertOrIgnore([
            'admission_application_id' => $application->id,
            'from_status' => 'new',
            'to_status' => 'accepted',
            'actor_id' => $admissionOfficer->id,
            'comment' => 'Demo accepted application.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $fee = Fee::query()->updateOrCreate([
            'school_id' => $school->id,
            'branch_id' => $branch->id,
            'code' => 'TUITION-TERM',
        ], ['name' => 'Tuition Fee (Term)', 'amount' => 120000, 'frequency' => 'term', 'is_active' => true]);

        $invoice = Invoice::query()->updateOrCreate([
            'school_id' => $school->id,
            'branch_id' => $branch->id,
            'invoice_no' => 'INV-2026-0001',
        ], [
            'student_id' => $student->id,
            'issue_date' => now()->toDateString(),
            'due_date' => now()->addDays(14)->toDateString(),
            'subtotal' => 120000,
            'discount_total' => 5000,
            'penalty_total' => 0,
            'total' => 115000,
            'paid_amount' => 60000,
            'status' => 'partial',
        ]);

        DB::table('invoice_items')->updateOrInsert([
            'invoice_id' => $invoice->id,
            'description' => 'Term Tuition',
        ], [
            'fee_id' => $fee->id,
            'quantity' => 1,
            'unit_price' => 120000,
            'line_total' => 120000,
            'updated_at' => now(),
            'created_at' => now(),
        ]);

        Payment::query()->updateOrCreate([
            'school_id' => $school->id,
            'branch_id' => $branch->id,
            'payment_no' => 'PAY-2026-0001',
        ], [
            'invoice_id' => $invoice->id,
            'amount' => 60000,
            'method' => 'bank_transfer',
            'status' => 'completed',
            'received_by' => $accountant->id,
            'paid_at' => now(),
        ]);

        DB::table('expense_categories')->updateOrInsert([
            'school_id' => $school->id,
            'branch_id' => $branch->id,
            'name' => 'Utilities',
        ], ['is_active' => true, 'updated_at' => now(), 'created_at' => now()]);

        $expenseCategoryId = DB::table('expense_categories')
            ->where('school_id', $school->id)
            ->where('branch_id', $branch->id)
            ->where('name', 'Utilities')
            ->value('id');

        Expense::query()->updateOrCreate([
            'school_id' => $school->id,
            'branch_id' => $branch->id,
            'expense_no' => 'EXP-2026-0001',
        ], [
            'expense_category_id' => $expenseCategoryId,
            'expense_date' => now()->toDateString(),
            'amount' => 22000,
            'description' => 'Electricity bill',
            'status' => 'approved',
            'approved_by' => $accountant->id,
        ]);

        DB::table('departments')->updateOrInsert([
            'school_id' => $school->id,
            'branch_id' => $branch->id,
            'name' => 'Academics',
        ], ['manager_id' => $teacher->id, 'updated_at' => now(), 'created_at' => now()]);

        $departmentId = DB::table('departments')
            ->where('school_id', $school->id)
            ->where('branch_id', $branch->id)
            ->where('name', 'Academics')
            ->value('id');

        $employee = Employee::query()->updateOrCreate([
            'school_id' => $school->id,
            'branch_id' => $branch->id,
            'employee_no' => 'EMP-0001',
        ], [
            'user_id' => $teacher->id,
            'department_id' => $departmentId,
            'job_title' => 'Senior Teacher',
            'hire_date' => '2024-01-10',
            'employment_type' => 'full_time',
            'status' => 'active',
        ]);

        DB::table('contracts')->updateOrInsert([
            'employee_id' => $employee->id,
            'contract_type' => 'permanent',
            'start_date' => '2024-01-10',
        ], [
            'base_salary' => 180000,
            'terms' => json_encode(['notice_period_days' => 30]),
            'status' => 'active',
            'updated_at' => now(),
            'created_at' => now(),
        ]);

        LeaveRequest::query()->updateOrCreate([
            'employee_id' => $employee->id,
            'leave_type' => 'annual',
            'start_date' => now()->addDays(10)->toDateString(),
        ], [
            'end_date' => now()->addDays(14)->toDateString(),
            'days' => 5,
            'status' => 'approved',
            'approved_by' => $accountant->id,
            'reason' => 'Family travel',
        ]);

        $payroll = PayrollRun::query()->updateOrCreate([
            'school_id' => $school->id,
            'branch_id' => $branch->id,
            'run_no' => 'PAYROLL-2026-04',
        ], [
            'month' => 4,
            'year' => 2026,
            'status' => 'processed',
            'processed_at' => now(),
        ]);

        DB::table('payroll_items')->updateOrInsert([
            'payroll_run_id' => $payroll->id,
            'employee_id' => $employee->id,
        ], [
            'basic_salary' => 180000,
            'allowances' => 20000,
            'deductions' => 5000,
            'net_salary' => 195000,
            'status' => 'paid',
            'updated_at' => now(),
            'created_at' => now(),
        ]);

        CrmLead::query()->updateOrCreate([
            'school_id' => $school->id,
            'branch_id' => $branch->id,
            'lead_no' => 'LEAD-0001',
        ], [
            'full_name' => 'Mr. Daniel Peters',
            'email' => 'lead1@example.com',
            'phone' => '08031111111',
            'source' => 'facebook',
            'stage' => 'qualified',
            'score' => 78.5,
            'owner_id' => $admissionOfficer->id,
            'last_contact_at' => now(),
        ]);

        $leadId = CrmLead::query()->where('lead_no', 'LEAD-0001')->value('id');
        DB::table('crm_activities')->updateOrInsert([
            'crm_lead_id' => $leadId,
            'type' => 'call',
            'activity_at' => now()->subDay(),
        ], [
            'notes' => 'Parent asked about fee plan.',
            'created_by' => $admissionOfficer->id,
            'updated_at' => now(),
            'created_at' => now(),
        ]);

        $campaign = MarketingCampaign::query()->updateOrCreate([
            'school_id' => $school->id,
            'branch_id' => $branch->id,
            'name' => 'Back To School 2026',
        ], [
            'channel' => 'social',
            'starts_on' => now()->subDays(7)->toDateString(),
            'ends_on' => now()->addDays(14)->toDateString(),
            'budget' => 350000,
            'status' => 'running',
        ]);

        DB::table('campaign_lead')->updateOrInsert([
            'marketing_campaign_id' => $campaign->id,
            'crm_lead_id' => $leadId,
        ], ['updated_at' => now(), 'created_at' => now()]);

        $provider = IntegrationProvider::query()->updateOrCreate([
            'code' => 'paystack',
        ], ['name' => 'Paystack', 'category' => 'payment', 'is_active' => true]);

        IntegrationConnection::query()->updateOrCreate([
            'school_id' => $school->id,
            'branch_id' => $branch->id,
            'integration_provider_id' => $provider->id,
            'name' => 'Primary Paystack',
        ], [
            'credentials_encrypted' => ['public_key' => 'pk_test_demo', 'secret_key' => 'sk_test_demo'],
            'settings' => ['webhook_enabled' => true],
            'status' => 'active',
            'last_synced_at' => now(),
        ]);

        $timetable = Timetable::query()->updateOrCreate([
            'school_id' => $school->id,
            'branch_id' => $branch->id,
            'academic_year_id' => $year->id,
            'name' => 'Grade 7 Weekly Timetable',
        ], ['is_active' => true]);

        TimetableEntry::query()->updateOrCreate([
            'timetable_id' => $timetable->id,
            'section_id' => $sectionA->id,
            'subject_id' => $math->id,
            'teacher_id' => $teacher->id,
            'day_of_week' => 'monday',
            'starts_at' => '08:00:00',
        ], [
            'ends_at' => '08:45:00',
            'room_name' => 'R-101',
        ]);

        $attendanceSession = AttendanceSession::query()->updateOrCreate([
            'school_id' => $school->id,
            'branch_id' => $branch->id,
            'attendance_date' => now()->toDateString(),
            'type' => 'student',
        ], [
            'academic_year_id' => $year->id,
            'section_id' => $sectionA->id,
            'method' => 'manual',
            'recorded_by' => $teacher->id,
        ]);

        StudentAttendance::query()->updateOrCreate([
            'attendance_session_id' => $attendanceSession->id,
            'student_id' => $student->id,
        ], ['status' => 'present', 'check_in_at' => '07:55:00']);

        StudentAttendance::query()->updateOrCreate([
            'attendance_session_id' => $attendanceSession->id,
            'student_id' => $student2->id,
        ], ['status' => 'late', 'check_in_at' => '08:10:00']);

        KpiSnapshot::query()->updateOrCreate([
            'school_id' => $school->id,
            'branch_id' => $branch->id,
            'snapshot_date' => now()->toDateString(),
        ], [
            'total_students' => Student::query()->where('school_id', $school->id)->where('branch_id', $branch->id)->count(),
            'active_teachers' => User::query()->role('Teacher')->where('school_id', $school->id)->where('branch_id', $branch->id)->count(),
            'fee_due_total' => (float) Invoice::query()->where('school_id', $school->id)->where('branch_id', $branch->id)->sum('total'),
            'fee_collected_total' => (float) Invoice::query()->where('school_id', $school->id)->where('branch_id', $branch->id)->sum('paid_amount'),
            'new_leads' => CrmLead::query()->where('school_id', $school->id)->where('branch_id', $branch->id)->count(),
        ]);
    }
}
