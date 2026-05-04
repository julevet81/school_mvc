<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('attendance_sessions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('academic_year_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('section_id')->nullable()->constrained('sections')->nullOnDelete();
            $table->date('attendance_date');
            $table->string('type', 30);
            $table->string('method', 30)->default('manual');
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['school_id', 'branch_id', 'attendance_date', 'type'], 'attendance_sessions_scope_date_type_idx');
        });

        Schema::create('student_attendances', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('attendance_session_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->string('status', 20);
            $table->time('check_in_at')->nullable();
            $table->time('check_out_at')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->unique(['attendance_session_id', 'student_id']);
            $table->index(['student_id', 'status'], 'student_attendance_student_status_idx');
        });

        Schema::create('staff_attendances', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('attendance_session_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('status', 20);
            $table->time('check_in_at')->nullable();
            $table->time('check_out_at')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->unique(['attendance_session_id', 'user_id']);
            $table->index(['user_id', 'status'], 'staff_attendance_user_status_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff_attendances');
        Schema::dropIfExists('student_attendances');
        Schema::dropIfExists('attendance_sessions');
    }
};
