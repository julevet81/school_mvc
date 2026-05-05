<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absence_notification_logs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('attendance_session_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('parents')->nullOnDelete();
            $table->string('channel', 30)->default('email');
            $table->string('recipient')->nullable();
            $table->string('status', 20)->default('pending');
            $table->text('message')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();

            $table->index(['attendance_session_id', 'status'], 'absence_logs_session_status_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absence_notification_logs');
    }
};
