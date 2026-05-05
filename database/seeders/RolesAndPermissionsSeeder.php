<?php

namespace Database\Seeders;

use App\Support\AccessCatalog;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = AccessCatalog::flatPermissions();

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        $roleMap = AccessCatalog::roleMap();

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
