<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('academic_years', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->string('name', 50);
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_current')->default(false);
            $table->timestamps();

            $table->unique(['school_id', 'branch_id', 'name'], 'acad_years_school_branch_name_uniq');
            $table->index(['school_id', 'branch_id', 'is_current'], 'acad_years_school_branch_current_idx');
        });

        Schema::create('semesters', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('academic_year_id')->constrained()->cascadeOnDelete();
            $table->string('name', 50);
            $table->date('start_date');
            $table->date('end_date');
            $table->unsignedTinyInteger('sort_order')->default(1);
            $table->timestamps();

            $table->unique(['academic_year_id', 'name'], 'semesters_year_name_uniq');
        });

        Schema::create('grades', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->string('name', 100);
            $table->unsignedTinyInteger('level');
            $table->timestamps();

            $table->unique(['school_id', 'branch_id', 'name'], 'grades_school_branch_name_uniq');
            $table->index(['school_id', 'branch_id', 'level'], 'grades_school_branch_level_idx');
        });

        Schema::create('classrooms', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('grade_id')->constrained('grades')->cascadeOnDelete();
            $table->string('name', 100);
            $table->unsignedSmallInteger('capacity')->nullable();
            $table->timestamps();

            $table->unique(['school_id', 'branch_id', 'grade_id', 'name'], 'classrooms_scope_grade_name_uniq');
        });

        Schema::create('sections', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('classroom_id')->constrained('classrooms')->cascadeOnDelete();
            $table->string('name', 50);
            $table->foreignId('homeroom_teacher_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['classroom_id', 'name'], 'sections_classroom_name_uniq');
        });

        Schema::create('subjects', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->string('code', 20);
            $table->string('name', 120);
            $table->unsignedTinyInteger('credit_hours')->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['school_id', 'branch_id', 'code'], 'subjects_school_branch_code_uniq');
            $table->index(['school_id', 'branch_id', 'is_active'], 'subjects_school_branch_active_idx');
        });

        Schema::create('subject_teacher_assignments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('academic_year_id')->constrained()->cascadeOnDelete();
            $table->foreignId('section_id')->constrained('sections')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['academic_year_id', 'section_id', 'subject_id'], 'sub_teacher_year_section_subject_uniq');
            $table->index(['teacher_id', 'academic_year_id'], 'sub_teacher_teacher_year_idx');
        });

        Schema::create('student_enrollments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('academic_year_id')->constrained()->cascadeOnDelete();
            $table->foreignId('section_id')->constrained('sections')->cascadeOnDelete();
            $table->date('enrolled_at')->nullable();
            $table->string('status', 30)->default('enrolled');
            $table->timestamps();

            $table->unique(['student_id', 'academic_year_id'], 'enrollments_student_year_uniq');
            $table->index(['section_id', 'academic_year_id'], 'enrollments_section_year_idx');
        });

        Schema::create('timetables', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('academic_year_id')->constrained()->cascadeOnDelete();
            $table->foreignId('semester_id')->nullable()->constrained('semesters')->nullOnDelete();
            $table->string('name', 100);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['school_id', 'branch_id', 'academic_year_id', 'is_active'], 'timetables_scope_active_idx');
        });

        Schema::create('timetable_entries', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('timetable_id')->constrained()->cascadeOnDelete();
            $table->foreignId('section_id')->constrained('sections')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();
            $table->string('day_of_week', 15);
            $table->time('starts_at');
            $table->time('ends_at');
            $table->string('room_name', 80)->nullable();
            $table->timestamps();

            $table->index(['section_id', 'day_of_week', 'starts_at'], 'tt_entries_section_day_start_idx');
            $table->index(['teacher_id', 'day_of_week', 'starts_at'], 'tt_entries_teacher_day_start_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('timetable_entries');
        Schema::dropIfExists('timetables');
        Schema::dropIfExists('student_enrollments');
        Schema::dropIfExists('subject_teacher_assignments');
        Schema::dropIfExists('subjects');
        Schema::dropIfExists('sections');
        Schema::dropIfExists('classrooms');
        Schema::dropIfExists('grades');
        Schema::dropIfExists('semesters');
        Schema::dropIfExists('academic_years');
    }
};
