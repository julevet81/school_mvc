<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\School;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminBootstrapSeeder extends Seeder
{
    public function run(): void
    {
        $school = School::query()->firstOrCreate([
            'code' => 'MAIN',
        ], [
            'name' => 'Private School Network',
            'country' => 'NG',
            'timezone' => 'Africa/Lagos',
            'currency' => 'NGN',
            'is_active' => true,
        ]);

        $branch = Branch::query()->firstOrCreate([
            'school_id' => $school->id,
            'code' => 'HQ',
        ], [
            'name' => 'Headquarters',
            'is_main' => true,
            'is_active' => true,
        ]);

        $admin = User::query()->firstOrCreate([
            'email' => 'admin@privetschool.local',
        ], [
            'school_id' => $school->id,
            'branch_id' => $branch->id,
            'name' => 'Platform Super Admin',
            'password' => 'Admin@123456',
            'status' => 'active',
        ]);

        if (! $admin->hasRole('Super Admin')) {
            $admin->assignRole('Super Admin');
        }
    }
}
