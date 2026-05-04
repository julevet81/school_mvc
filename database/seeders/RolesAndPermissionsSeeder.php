<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'users.view','users.create','users.update','users.delete','users.assign-roles',
            'admissions.view','admissions.create','admissions.update','admissions.approve','admissions.reject',
            'students.view','students.create','students.update','students.transfer',
            'parents.view','parents.manage',
            'academics.view','academics.manage',
            'attendance.view','attendance.manage',
            'exams.view','exams.manage',
            'finance.view','finance.manage','finance.approve',
            'hr.view','hr.manage',
            'transport.view','transport.manage',
            'library.view','library.manage',
            'inventory.view','inventory.manage',
            'crm.view','crm.manage',
            'reports.view','reports.export',
            'settings.manage','security.manage','integrations.manage',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        $roleMap = [
            'Super Admin' => ['*'],
            'School Owner' => ['users.view','users.create','users.update','users.assign-roles','admissions.view','admissions.approve','students.view','parents.view','academics.view','attendance.view','exams.view','finance.view','finance.manage','hr.view','reports.view','reports.export','settings.manage'],
            'School Director' => ['users.view','admissions.view','admissions.approve','students.view','parents.view','academics.manage','attendance.view','exams.view','finance.view','hr.view','reports.view'],
            'Vice Director' => ['admissions.view','students.view','academics.manage','attendance.view','exams.view','reports.view'],
            'Academic Supervisor' => ['students.view','academics.manage','attendance.view','exams.manage','reports.view'],
            'Accountant' => ['finance.view','finance.manage','finance.approve','reports.view','reports.export'],
            'Admission Officer' => ['admissions.view','admissions.create','admissions.update'],
            'Teacher' => ['students.view','attendance.manage','exams.manage'],
            'Parent' => ['parents.view'],
            'Student' => ['students.view'],
            'Receptionist' => ['admissions.view','admissions.create','parents.view'],
            'Transport Manager' => ['transport.view','transport.manage'],
            'HR Manager' => ['hr.view','hr.manage'],
            'Security Staff' => ['security.manage'],
            'Library Manager' => ['library.view','library.manage'],
            'Procurement Officer' => ['inventory.view','inventory.manage'],
        ];

        foreach ($roleMap as $roleName => $grants) {
            $role = Role::findOrCreate($roleName, 'web');

            if ($grants === ['*']) {
                $role->syncPermissions(Permission::all());
                continue;
            }

            $role->syncPermissions($grants);
        }
    }
}
