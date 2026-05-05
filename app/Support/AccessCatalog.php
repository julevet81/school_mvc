<?php

declare(strict_types=1);

namespace App\Support;

class AccessCatalog
{
    public static function permissions(): array
    {
        return [
            'users' => [
                'users.view',
                'users.create',
                'users.update',
                'users.delete',
                'users.assign-roles',
            ],
            'admissions' => [
                'admissions.view',
                'admissions.create',
                'admissions.update',
                'admissions.approve',
                'admissions.reject',
            ],
            'students' => [
                'students.view',
                'students.create',
                'students.update',
                'students.transfer',
            ],
            'parents' => [
                'parents.view',
                'parents.manage',
            ],
            'academics' => [
                'academics.view',
                'academics.manage',
            ],
            'attendance' => [
                'attendance.view',
                'attendance.manage',
            ],
            'exams' => [
                'exams.view',
                'exams.manage',
            ],
            'finance' => [
                'finance.view',
                'finance.manage',
                'finance.approve',
            ],
            'hr' => [
                'hr.view',
                'hr.manage',
            ],
            'transport' => [
                'transport.view',
                'transport.manage',
            ],
            'library' => [
                'library.view',
                'library.manage',
            ],
            'inventory' => [
                'inventory.view',
                'inventory.manage',
            ],
            'crm' => [
                'crm.view',
                'crm.manage',
            ],
            'reports' => [
                'reports.view',
                'reports.export',
            ],
            'settings' => [
                'settings.manage',
                'security.manage',
                'integrations.manage',
            ],
        ];
    }

    public static function flatPermissions(): array
    {
        return collect(self::permissions())->flatten()->values()->all();
    }

    public static function roleMap(): array
    {
        return [
            'Super Admin' => ['*'],
            'School Owner' => ['users.view', 'users.create', 'users.update', 'users.assign-roles', 'admissions.view', 'admissions.approve', 'students.view', 'parents.view', 'academics.view', 'attendance.view', 'exams.view', 'finance.view', 'finance.manage', 'hr.view', 'reports.view', 'reports.export', 'settings.manage'],
            'School Director' => ['users.view', 'admissions.view', 'admissions.approve', 'students.view', 'parents.view', 'academics.manage', 'attendance.view', 'exams.view', 'finance.view', 'hr.view', 'reports.view'],
            'Vice Director' => ['admissions.view', 'students.view', 'academics.manage', 'attendance.view', 'exams.view', 'reports.view'],
            'Academic Supervisor' => ['students.view', 'academics.manage', 'attendance.view', 'exams.manage', 'reports.view'],
            'Accountant' => ['finance.view', 'finance.manage', 'finance.approve', 'reports.view', 'reports.export'],
            'Admission Officer' => ['admissions.view', 'admissions.create', 'admissions.update'],
            'Teacher' => ['students.view', 'attendance.manage', 'exams.manage'],
            'Parent' => ['parents.view'],
            'Student' => ['students.view'],
            'Receptionist' => ['admissions.view', 'admissions.create', 'parents.view'],
            'Transport Manager' => ['transport.view', 'transport.manage'],
            'HR Manager' => ['hr.view', 'hr.manage'],
            'Security Staff' => ['security.manage'],
            'Library Manager' => ['library.view', 'library.manage'],
            'Procurement Officer' => ['inventory.view', 'inventory.manage'],
        ];
    }
}
