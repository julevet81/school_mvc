<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('crm_leads', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->string('lead_no', 40);
            $table->string('full_name');
            $table->string('email')->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('source', 50)->nullable();
            $table->string('stage', 30)->default('new');
            $table->decimal('score', 6, 2)->default(0);
            $table->foreignId('owner_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('last_contact_at')->nullable();
            $table->timestamps();

            $table->unique(['school_id','branch_id','lead_no'], 'crm_leads_scope_no_uniq');
            $table->index(['stage','owner_id'], 'crm_leads_stage_owner_idx');
        });

        Schema::create('crm_activities', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('crm_lead_id')->constrained('crm_leads')->cascadeOnDelete();
            $table->string('type', 40);
            $table->timestamp('activity_at');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['crm_lead_id','activity_at'], 'crm_activity_lead_at_idx');
        });

        Schema::create('marketing_campaigns', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->string('name', 150);
            $table->string('channel', 40);
            $table->date('starts_on');
            $table->date('ends_on')->nullable();
            $table->decimal('budget', 12, 2)->default(0);
            $table->string('status', 30)->default('planned');
            $table->timestamps();

            $table->index(['school_id','branch_id','status'], 'campaign_scope_status_idx');
        });

        Schema::create('campaign_lead', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('marketing_campaign_id')->constrained()->cascadeOnDelete();
            $table->foreignId('crm_lead_id')->constrained('crm_leads')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['marketing_campaign_id','crm_lead_id'], 'campaign_lead_uniq');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaign_lead');
        Schema::dropIfExists('marketing_campaigns');
        Schema::dropIfExists('crm_activities');
        Schema::dropIfExists('crm_leads');
    }
};
