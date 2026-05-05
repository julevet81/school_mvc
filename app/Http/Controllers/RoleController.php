<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AuthorizesDashboardRequests;
use App\Http\Requests\Role\StoreRoleRequest;
use App\Http\Requests\Role\UpdateRoleRequest;
use App\Support\AccessCatalog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleController extends Controller
{
    use AuthorizesDashboardRequests;

    public function index(Request $request): View
    {
        $this->ensureAuthenticated();

        $roles = Role::query()
            ->withCount('permissions')
            ->when($request->filled('search'), fn ($query) => $query->where('name', 'like', '%'.$request->string('search')->trim().'%'))
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return view('roles.index', compact('roles'));
    }

    public function create(): View
    {
        $this->ensureAuthenticated();

        $permissionGroups = AccessCatalog::permissions();

        return view('roles.create', compact('permissionGroups'));
    }

    public function store(StoreRoleRequest $request): RedirectResponse
    {
        $role = Role::query()->create([
            'name' => $request->string('name')->value(),
            'guard_name' => 'web',
        ]);

        $role->syncPermissions($request->input('permissions', []));
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return redirect()->route('roles.edit', $role)->with('success', __('app.messages.created', ['resource' => __('app.resources.role')]));
    }

    public function edit(Role $role): View
    {
        $this->ensureAuthenticated();

        $permissionGroups = AccessCatalog::permissions();
        $assignedPermissions = $role->permissions()->pluck('name')->all();

        return view('roles.edit', compact('role', 'permissionGroups', 'assignedPermissions'));
    }

    public function update(UpdateRoleRequest $request, Role $role): RedirectResponse
    {
        $role->update(['name' => $request->string('name')->value()]);
        $role->syncPermissions($request->input('permissions', []));
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return redirect()->route('roles.edit', $role)->with('success', __('app.messages.updated', ['resource' => __('app.resources.role')]));
    }

    public function destroy(Role $role): RedirectResponse
    {
        $this->ensureAuthenticated();
        $role->delete();
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return redirect()->route('roles.index')->with('success', __('app.messages.deleted', ['resource' => __('app.resources.role')]));
    }
}
