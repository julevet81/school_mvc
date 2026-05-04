<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('integration_providers', function (Blueprint $table): void {
            $table->id();
            $table->string('code', 40)->unique();
            $table->string('name', 120);
            $table->string('category', 40);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('integration_connections', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('integration_provider_id')->constrained()->cascadeOnDelete();
            $table->string('name', 120);
            $table->json('credentials_encrypted')->nullable();
            $table->json('settings')->nullable();
            $table->string('status', 30)->default('inactive');
            $table->timestamp('last_synced_at')->nullable();
            $table->timestamps();

            $table->unique(['school_id','branch_id','integration_provider_id','name'], 'integ_conn_scope_provider_name_uniq');
            $table->index(['status','last_synced_at'], 'integ_conn_status_sync_idx');
        });

        Schema::create('integration_sync_logs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('integration_connection_id')->constrained()->cascadeOnDelete();
            $table->string('direction', 20);
            $table->string('status', 30);
            $table->unsignedInteger('records_processed')->default(0);
            $table->text('message')->nullable();
            $table->timestamp('synced_at');
            $table->timestamps();

            $table->index(['integration_connection_id','synced_at'], 'integ_logs_conn_sync_idx');
        });

        Schema::create('webhook_events', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('integration_connection_id')->nullable()->constrained()->nullOnDelete();
            $table->string('provider_code', 40);
            $table->string('event_type', 120);
            $table->json('payload');
            $table->string('status', 30)->default('received');
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            $table->index(['provider_code','event_type'], 'webhook_provider_event_idx');
            $table->index(['status','created_at'], 'webhook_status_created_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('webhook_events');
        Schema::dropIfExists('integration_sync_logs');
        Schema::dropIfExists('integration_connections');
        Schema::dropIfExists('integration_providers');
    }
};
