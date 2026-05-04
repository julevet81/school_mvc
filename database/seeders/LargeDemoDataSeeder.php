<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\AttendanceSession;
use App\Models\Branch;
use App\Models\Classroom;
use App\Models\CrmLead;
use App\Models\Employee;
use App\Models\Fee;
use App\Models\Grade;
use App\Models\Invoice;
use App\Models\KpiSnapshot;
use App\Models\ParentProfile;
use App\Models\Payment;
use App\Models\PayrollRun;
use App\Models\MarketingCampaign;
use App\Models\School;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentEnrollment;
use App\Models\Subject;
use App\Models\Timetable;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LargeDemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $school = School::query()->where('code', 'MAIN')->firstOrFail();
        $branch = Branch::query()->where('school_id', $school->id)->where('code', 'HQ')->firstOrFail();

        $year = AcademicYear::query()->firstOrCreate([
            'school_id' => $school->id,
            'branch_id' => $branch->id,
            'name' => '2026/2027',
        ], [
            'start_date' => '2026-09-01',
            'end_date' => '2027-07-31',
            'is_current' => true,
        ]);

        $grade = Grade::query()->firstOrCreate([
            'school_id' => $school->id,
            'branch_id' => $branch->id,
            'name' => 'Grade 8',
        ], ['level' => 8]);

        $classA = Classroom::query()->firstOrCreate([
            'school_id' => $school->id,
            'branch_id' => $branch->id,
            'grade_id' => $grade->id,
            'name' => 'Class B',
        ], ['capacity' => 45]);

        $section = Section::query()->firstOrCreate([
            'classroom_id' => $classA->id,
            'name' => 'Section B',
        ]);

        $teacherUsers = [];
        for ($i = 1; $i <= 8; $i++) {
            $u = User::query()->firstOrCreate([
                'email' => "teacher{$i}.bulk@privetschool.local",
            ], [
                'school_id' => $school->id,
                'branch_id' => $branch->id,
                'name' => "Teacher Bulk {$i}",
                'password' => 'Password@123',
                'status' => 'active',
            ]);
            $u->syncRoles(['Teacher']);
            $teacherUsers[] = $u;
        }

        $hrUser = User::query()->firstOrCreate([
            'email' => 'hr.bulk@privetschool.local',
        ], [
            'school_id' => $school->id,
            'branch_id' => $branch->id,
            'name' => 'HR Bulk Manager',
            'password' => 'Password@123',
            'status' => 'active',
        ]);
        $hrUser->syncRoles(['HR Manager']);

        $accountant = User::query()->firstOrCreate([
            'email' => 'accounting.bulk@privetschool.local',
        ], [
            'school_id' => $school->id,
            'branch_id' => $branch->id,
            'name' => 'Accounting Bulk Manager',
            'password' => 'Password@123',
            'status' => 'active',
        ]);
        $accountant->syncRoles(['Accountant']);

        $subjects = [];
        foreach ([['SCI-8', 'Science'], ['MTH-8', 'Mathematics'], ['ENG-8', 'English'], ['CIV-8', 'Civic Education']] as $s) {
            $subjects[] = Subject::query()->firstOrCreate([
                'school_id' => $school->id,
                'branch_id' => $branch->id,
                'code' => $s[0],
            ], [
                'name' => $s[1],
                'credit_hours' => 3,
                'is_active' => true,
            ]);
        }

        foreach ($subjects as $idx => $subject) {
            DB::table('subject_teacher_assignments')->updateOrInsert([
                'school_id' => $school->id,
                'branch_id' => $branch->id,
                'academic_year_id' => $year->id,
                'section_id' => $section->id,
                'subject_id' => $subject->id,
            ], [
                'teacher_id' => $teacherUsers[$idx % count($teacherUsers)]->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $fee = Fee::query()->firstOrCreate([
            'school_id' => $school->id,
            'branch_id' => $branch->id,
            'code' => 'TUITION-MONTHLY',
        ], [
            'name' => 'Tuition Monthly',
            'amount' => 45000,
            'frequency' => 'monthly',
            'is_active' => true,
        ]);

        $studentIds = [];
        for ($i = 1; $i <= 100; $i++) {
            $studentNo = 'BLK-STU-'.str_pad((string) $i, 4, '0', STR_PAD_LEFT);
            $student = Student::query()->firstOrCreate([
                'school_id' => $school->id,
                'branch_id' => $branch->id,
                'student_no' => $studentNo,
            ], [
                'full_name' => "Bulk Student {$i}",
                'date_of_birth' => now()->subYears(rand(10, 15))->subDays(rand(1, 300))->toDateString(),
                'gender' => $i % 2 === 0 ? 'female' : 'male',
                'enrollment_date' => now()->subMonths(rand(1, 8))->toDateString(),
                'status' => 'active',
            ]);
            $studentIds[] = $student->id;

            StudentEnrollment::query()->updateOrCreate([
                'student_id' => $student->id,
                'academic_year_id' => $year->id,
            ], [
                'section_id' => $section->id,
                'enrolled_at' => now()->subMonths(rand(1, 8))->toDateString(),
                'status' => 'enrolled',
            ]);

            if ($i <= 60) {
                $parent = ParentProfile::query()->firstOrCreate([
                    'school_id' => $school->id,
                    'branch_id' => $branch->id,
                    'parent_no' => 'BLK-PAR-'.str_pad((string) $i, 4, '0', STR_PAD_LEFT),
                ], [
                    'full_name' => "Bulk Parent {$i}",
                    'email' => "bulk.parent{$i}@example.com",
                    'phone' => '0803'.str_pad((string) rand(1000000, 9999999), 7, '0', STR_PAD_LEFT),
                    'occupation' => 'Business',
                    'portal_enabled' => true,
                ]);
                $parent->students()->syncWithoutDetaching([
                    $student->id => ['relationship' => 'guardian', 'is_primary' => true, 'financially_responsible' => true],
                ]);
            }
        }

        $departmentId = DB::table('departments')->updateOrInsert([
            'school_id' => $school->id,
            'branch_id' => $branch->id,
            'name' => 'Operations',
        ], [
            'manager_id' => $hrUser->id,
            'updated_at' => now(),
            'created_at' => now(),
        ]);

        $depId = DB::table('departments')->where('school_id', $school->id)->where('branch_id', $branch->id)->where('name', 'Operations')->value('id');

        for ($i = 1; $i <= 20; $i++) {
            $user = User::query()->firstOrCreate([
                'email' => "employee{$i}.bulk@privetschool.local",
            ], [
                'school_id' => $school->id,
                'branch_id' => $branch->id,
                'name' => "Bulk Employee {$i}",
                'password' => 'Password@123',
                'status' => 'active',
            ]);

            Employee::query()->updateOrCreate([
                'school_id' => $school->id,
                'branch_id' => $branch->id,
                'employee_no' => 'BLK-EMP-'.str_pad((string) $i, 4, '0', STR_PAD_LEFT),
            ], [
                'user_id' => $user->id,
                'department_id' => $depId,
                'job_title' => 'Staff',
                'hire_date' => now()->subMonths(rand(2, 36))->toDateString(),
                'employment_type' => 'full_time',
                'status' => 'active',
            ]);
        }

        for ($m = 0; $m < 6; $m++) {
            $monthDate = now()->subMonths($m);
            $month = (int) $monthDate->format('m');
            $yearNum = (int) $monthDate->format('Y');

            $payrollRun = PayrollRun::query()->firstOrCreate([
                'school_id' => $school->id,
                'branch_id' => $branch->id,
                'run_no' => "BLK-PAYROLL-{$yearNum}{$month}",
            ], [
                'month' => $month,
                'year' => $yearNum,
                'status' => 'processed',
                'processed_at' => $monthDate->copy()->endOfMonth(),
            ]);

            $employeeIds = Employee::query()->where('school_id', $school->id)->where('branch_id', $branch->id)->limit(20)->pluck('id');
            foreach ($employeeIds as $empId) {
                DB::table('payroll_items')->updateOrInsert([
                    'payroll_run_id' => $payrollRun->id,
                    'employee_id' => $empId,
                ], [
                    'basic_salary' => 120000,
                    'allowances' => 15000,
                    'deductions' => 5000,
                    'net_salary' => 130000,
                    'status' => 'paid',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $invoiceChunk = array_slice($studentIds, 0, 80);
            foreach ($invoiceChunk as $idx => $sid) {
                $invoiceNo = 'BLK-INV-'.$yearNum.$month.'-'.str_pad((string) ($idx + 1), 4, '0', STR_PAD_LEFT);
                $total = 45000;
                $paid = $idx % 4 === 0 ? 45000 : ($idx % 4 === 1 ? 30000 : 0);
                $status = $paid >= $total ? 'paid' : ($paid > 0 ? 'partial' : 'pending');

                $invoice = Invoice::query()->updateOrCreate([
                    'school_id' => $school->id,
                    'branch_id' => $branch->id,
                    'invoice_no' => $invoiceNo,
                ], [
                    'student_id' => $sid,
                    'issue_date' => $monthDate->copy()->startOfMonth()->toDateString(),
                    'due_date' => $monthDate->copy()->addDays(10)->toDateString(),
                    'subtotal' => $total,
                    'discount_total' => 0,
                    'penalty_total' => 0,
                    'total' => $total,
                    'paid_amount' => $paid,
                    'status' => $status,
                ]);

                DB::table('invoice_items')->updateOrInsert([
                    'invoice_id' => $invoice->id,
                    'description' => 'Monthly Tuition',
                ], [
                    'fee_id' => $fee->id,
                    'quantity' => 1,
                    'unit_price' => $total,
                    'line_total' => $total,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                if ($paid > 0) {
                    Payment::query()->updateOrCreate([
                        'school_id' => $school->id,
                        'branch_id' => $branch->id,
                        'payment_no' => 'BLK-PAY-'.$yearNum.$month.'-'.str_pad((string) ($idx + 1), 4, '0', STR_PAD_LEFT),
                    ], [
                        'invoice_id' => $invoice->id,
                        'amount' => $paid,
                        'method' => 'bank_transfer',
                        'status' => 'completed',
                        'received_by' => $accountant->id,
                        'paid_at' => $monthDate->copy()->addDays(5),
                    ]);
                }
            }

            DB::table('expense_categories')->updateOrInsert([
                'school_id' => $school->id,
                'branch_id' => $branch->id,
                'name' => 'Operations Bulk',
            ], [
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $expCatId = DB::table('expense_categories')->where('school_id', $school->id)->where('branch_id', $branch->id)->where('name', 'Operations Bulk')->value('id');
            for ($e = 1; $e <= 12; $e++) {
                DB::table('expenses')->updateOrInsert([
                    'school_id' => $school->id,
                    'branch_id' => $branch->id,
                    'expense_no' => 'BLK-EXP-'.$yearNum.$month.'-'.str_pad((string) $e, 3, '0', STR_PAD_LEFT),
                ], [
                    'expense_category_id' => $expCatId,
                    'expense_date' => $monthDate->copy()->addDays(rand(1, 25))->toDateString(),
                    'amount' => rand(12000, 85000),
                    'description' => 'Bulk generated expense',
                    'status' => 'approved',
                    'approved_by' => $accountant->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $campaignId = MarketingCampaign::query()->updateOrCreate([
            'school_id' => $school->id,
            'branch_id' => $branch->id,
            'name' => 'Bulk Enrollment Drive',
        ], [
            'channel' => 'digital',
            'starts_on' => now()->subMonths(2)->toDateString(),
            'ends_on' => now()->addMonth()->toDateString(),
            'budget' => 1250000,
            'status' => 'running',
        ])->id;

        for ($i = 1; $i <= 120; $i++) {
            $lead = CrmLead::query()->updateOrCreate([
                'school_id' => $school->id,
                'branch_id' => $branch->id,
                'lead_no' => 'BLK-LEAD-'.str_pad((string) $i, 4, '0', STR_PAD_LEFT),
            ], [
                'full_name' => 'Lead Prospect '.$i,
                'email' => 'lead.bulk'.$i.'@example.com',
                'phone' => '0804'.str_pad((string) rand(1000000, 9999999), 7, '0', STR_PAD_LEFT),
                'source' => ['facebook', 'google', 'walk_in'][array_rand([0,1,2])],
                'stage' => ['new', 'contacted', 'qualified'][array_rand([0,1,2])],
                'score' => rand(40, 95),
                'owner_id' => $teacherUsers[array_rand($teacherUsers)]->id,
                'last_contact_at' => now()->subDays(rand(0, 15)),
            ]);

            DB::table('campaign_lead')->updateOrInsert([
                'marketing_campaign_id' => $campaignId,
                'crm_lead_id' => $lead->id,
            ], ['created_at' => now(), 'updated_at' => now()]);
        }

        $timetable = Timetable::query()->firstOrCreate([
            'school_id' => $school->id,
            'branch_id' => $branch->id,
            'academic_year_id' => $year->id,
            'name' => 'Bulk Timetable',
        ], ['is_active' => true]);

        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
        $starts = ['08:00:00', '09:00:00', '10:00:00', '11:00:00'];
        foreach ($days as $d) {
            foreach ($starts as $slot => $start) {
                $subject = $subjects[$slot % count($subjects)];
                $teacher = $teacherUsers[$slot % count($teacherUsers)];
                DB::table('timetable_entries')->updateOrInsert([
                    'timetable_id' => $timetable->id,
                    'section_id' => $section->id,
                    'subject_id' => $subject->id,
                    'teacher_id' => $teacher->id,
                    'day_of_week' => $d,
                    'starts_at' => $start,
                ], [
                    'ends_at' => date('H:i:s', strtotime($start . ' +45 minutes')),
                    'room_name' => 'B-'.($slot + 1),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        for ($d = 0; $d < 20; $d++) {
            $session = AttendanceSession::query()->firstOrCreate([
                'school_id' => $school->id,
                'branch_id' => $branch->id,
                'attendance_date' => now()->subDays($d)->toDateString(),
                'type' => 'student',
            ], [
                'academic_year_id' => $year->id,
                'section_id' => $section->id,
                'method' => 'manual',
                'recorded_by' => $teacherUsers[0]->id,
            ]);

            foreach (array_slice($studentIds, 0, 60) as $sid) {
                DB::table('student_attendances')->updateOrInsert([
                    'attendance_session_id' => $session->id,
                    'student_id' => $sid,
                ], [
                    'status' => ['present', 'present', 'present', 'late', 'absent'][array_rand([0,1,2,3,4])],
                    'check_in_at' => '07:55:00',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

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
