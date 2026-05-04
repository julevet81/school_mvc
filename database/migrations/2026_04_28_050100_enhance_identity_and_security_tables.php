<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->foreignId('school_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->foreignId('branch_id')->nullable()->after('school_id')->constrained()->nullOnDelete();
            $table->string('employee_number', 50)->nullable()->after('remember_token');
            $table->string('status', 30)->default('active')->after('employee_number');
            $table->timestamp('last_login_at')->nullable()->after('status');
            $table->ipAddress('last_login_ip')->nullable()->after('last_login_at');

            $table->index(['school_id', 'branch_id']);
            $table->index(['status']);
        });

        Schema::create('user_profiles', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('gender', 20)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('nationality', 2)->nullable();
            $table->string('national_id', 80)->nullable();
            $table->string('address_line_1')->nullable();
            $table->string('address_line_2')->nullable();
            $table->string('city', 100)->nullable();
            $table->string('state', 100)->nullable();
            $table->string('country', 2)->nullable();
            $table->string('postal_code', 20)->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone', 30)->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->index(['national_id']);
        });

        Schema::create('user_devices', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('device_uuid')->nullable();
            $table->string('device_name')->nullable();
            $table->string('platform', 50)->nullable();
            $table->string('browser', 80)->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('last_seen_at')->nullable();
            $table->boolean('is_trusted')->default(false);
            $table->timestamps();

            $table->index(['user_id', 'last_seen_at']);
        });

        Schema::create('login_histories', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('email_attempted')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('status', 20);
            $table->string('failure_reason')->nullable();
            $table->timestamp('logged_in_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status', 'created_at']);
            $table->index(['email_attempted']);
        });

        Schema::create('audit_logs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('school_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('actor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('event', 80);
            $table->morphs('auditable');
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->index(['school_id', 'branch_id', 'event']);
            $table->index(['actor_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('login_histories');
        Schema::dropIfExists('user_devices');
        Schema::dropIfExists('user_profiles');

        Schema::table('users', function (Blueprint $table): void {
            $table->dropIndex(['school_id', 'branch_id']);
            $table->dropIndex(['status']);
            $table->dropConstrainedForeignId('branch_id');
            $table->dropConstrainedForeignId('school_id');
            $table->dropColumn(['employee_number', 'status', 'last_login_at', 'last_login_ip']);
        });
    }
};
