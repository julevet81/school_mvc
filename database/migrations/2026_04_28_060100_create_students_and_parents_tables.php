<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('parents', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('parent_no', 40);
            $table->string('full_name');
            $table->string('email')->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('occupation')->nullable();
            $table->boolean('portal_enabled')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['school_id', 'branch_id', 'parent_no']);
            $table->index(['school_id', 'branch_id', 'full_name'], 'parents_school_branch_name_idx');
        });

        Schema::create('students', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('admission_application_id')->nullable()->constrained()->nullOnDelete();
            $table->string('student_no', 40);
            $table->string('full_name');
            $table->date('date_of_birth')->nullable();
            $table->string('gender', 20)->nullable();
            $table->date('enrollment_date')->nullable();
            $table->string('status', 30)->default('active');
            $table->string('blood_group', 5)->nullable();
            $table->text('allergies')->nullable();
            $table->text('medical_notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['school_id', 'branch_id', 'student_no']);
            $table->index(['school_id', 'branch_id', 'status'], 'students_school_branch_status_idx');
        });

        Schema::create('parent_student', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('parent_id')->constrained('parents')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->string('relationship', 30);
            $table->boolean('is_primary')->default(false);
            $table->boolean('financially_responsible')->default(false);
            $table->timestamps();

            $table->unique(['parent_id', 'student_id']);
            $table->index(['student_id', 'is_primary'], 'parent_student_primary_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parent_student');
        Schema::dropIfExists('students');
        Schema::dropIfExists('parents');
    }
};
