<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('system_settings', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('school_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('group', 80);
            $table->string('key', 120);
            $table->json('value')->nullable();
            $table->boolean('is_encrypted')->default(false);
            $table->timestamps();

            $table->unique(['school_id','branch_id','group','key'], 'settings_scope_group_key_uniq');
        });

        Schema::create('report_snapshots', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->string('report_type', 80);
            $table->date('snapshot_date');
            $table->json('payload');
            $table->timestamps();

            $table->unique(['school_id','branch_id','report_type','snapshot_date'], 'report_snapshot_scope_type_date_uniq');
            $table->index(['report_type','snapshot_date'], 'report_snapshot_type_date_idx');
        });

        Schema::create('kpi_snapshots', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->date('snapshot_date');
            $table->unsignedInteger('total_students')->default(0);
            $table->unsignedInteger('active_teachers')->default(0);
            $table->decimal('fee_due_total', 12, 2)->default(0);
            $table->decimal('fee_collected_total', 12, 2)->default(0);
            $table->unsignedInteger('new_leads')->default(0);
            $table->timestamps();

            $table->unique(['school_id','branch_id','snapshot_date'], 'kpi_scope_date_uniq');
        });

        Schema::create('security_incidents', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('school_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type', 80);
            $table->string('severity', 20)->default('medium');
            $table->text('description');
            $table->ipAddress('ip_address')->nullable();
            $table->json('context')->nullable();
            $table->timestamp('detected_at');
            $table->timestamps();

            $table->index(['type','severity','detected_at'], 'security_incident_type_severity_at_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('security_incidents');
        Schema::dropIfExists('kpi_snapshots');
        Schema::dropIfExists('report_snapshots');
        Schema::dropIfExists('system_settings');
    }
};
