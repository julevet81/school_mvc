<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('admission_applications', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->string('application_no', 40);
            $table->string('status', 30)->default('new');
            $table->string('student_first_name');
            $table->string('student_last_name');
            $table->date('student_date_of_birth')->nullable();
            $table->string('student_gender', 20)->nullable();
            $table->string('target_grade', 80)->nullable();
            $table->foreignId('submitted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamp('interview_at')->nullable();
            $table->string('placement_result', 50)->nullable();
            $table->decimal('registration_fee', 12, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['school_id', 'branch_id', 'application_no']);
            $table->index(['school_id', 'branch_id', 'status'], 'adm_apps_school_branch_status_idx');
            $table->index(['submitted_by'], 'adm_apps_submitted_by_idx');
        });

        Schema::create('admission_documents', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('admission_application_id')->constrained()->cascadeOnDelete();
            $table->string('document_type', 80);
            $table->string('file_path');
            $table->string('mime_type', 100)->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['admission_application_id', 'document_type'], 'adm_docs_app_type_idx');
        });

        Schema::create('admission_workflow_logs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('admission_application_id')->constrained()->cascadeOnDelete();
            $table->string('from_status', 30)->nullable();
            $table->string('to_status', 30);
            $table->foreignId('actor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->index(['admission_application_id', 'created_at'], 'adm_wf_app_created_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admission_workflow_logs');
        Schema::dropIfExists('admission_documents');
        Schema::dropIfExists('admission_applications');
    }
};
