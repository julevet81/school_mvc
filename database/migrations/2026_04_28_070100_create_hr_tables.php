<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('departments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->string('name', 120);
            $table->foreignId('manager_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['school_id','branch_id','name'], 'dept_scope_name_uniq');
        });

        Schema::create('employees', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
            $table->string('employee_no', 40);
            $table->string('job_title', 120)->nullable();
            $table->date('hire_date')->nullable();
            $table->string('employment_type', 30)->default('full_time');
            $table->string('status', 30)->default('active');
            $table->timestamps();

            $table->unique(['school_id','branch_id','employee_no'], 'employees_scope_no_uniq');
            $table->index(['department_id','status'], 'employees_dept_status_idx');
        });

        Schema::create('contracts', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->string('contract_type', 40);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->decimal('base_salary', 12, 2);
            $table->json('terms')->nullable();
            $table->string('status', 30)->default('active');
            $table->timestamps();

            $table->index(['employee_id','status'], 'contracts_employee_status_idx');
        });

        Schema::create('leave_requests', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->string('leave_type', 40);
            $table->date('start_date');
            $table->date('end_date');
            $table->unsignedSmallInteger('days');
            $table->string('status', 30)->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('reason')->nullable();
            $table->timestamps();

            $table->index(['employee_id','status','start_date'], 'leave_emp_status_start_idx');
        });

        Schema::create('payroll_runs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->string('run_no', 40);
            $table->unsignedTinyInteger('month');
            $table->unsignedSmallInteger('year');
            $table->string('status', 30)->default('draft');
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            $table->unique(['school_id','branch_id','run_no'], 'payroll_scope_no_uniq');
            $table->index(['month','year','status'], 'payroll_month_year_status_idx');
        });

        Schema::create('payroll_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('payroll_run_id')->constrained()->cascadeOnDelete();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->decimal('basic_salary', 12, 2)->default(0);
            $table->decimal('allowances', 12, 2)->default(0);
            $table->decimal('deductions', 12, 2)->default(0);
            $table->decimal('net_salary', 12, 2)->default(0);
            $table->string('status', 30)->default('pending');
            $table->timestamps();

            $table->unique(['payroll_run_id','employee_id'], 'payroll_item_run_employee_uniq');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payroll_items');
        Schema::dropIfExists('payroll_runs');
        Schema::dropIfExists('leave_requests');
        Schema::dropIfExists('contracts');
        Schema::dropIfExists('employees');
        Schema::dropIfExists('departments');
    }
};
