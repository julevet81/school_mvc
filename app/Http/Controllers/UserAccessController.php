<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AuthorizesDashboardRequests;
use App\Http\Requests\UserAccess\UpdateUserAccessRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class UserAccessController extends Controller
{
    use AuthorizesDashboardRequests;

    public function index(Request $request): View
    {
        $this->ensureAuthenticated();

        $users = User::query()
            ->with(['roles', 'permissions', 'school:id,name', 'branch:id,name'])
            ->when($request->filled('search'), function ($query) use ($request): void {
                $term = '%'.$request->string('search')->trim().'%';
                $query->where(function ($userQuery) use ($term): void {
                    $userQuery->where('name', 'like', $term)->orWhere('email', 'like', $term);
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('user-access.index', compact('users'));
    }

    public function edit(User $user): View
    {
        $this->ensureAuthenticated();

        $roles = Role::query()->orderBy('name')->get(['id', 'name']);
        $permissions = Permission::query()->orderBy('name')->get(['id', 'name']);
        $assignedRoles = $user->roles()->pluck('name')->all();
        $directPermissions = $user->permissions()->pluck('name')->all();

        return view('user-access.edit', compact('user', 'roles', 'permissions', 'assignedRoles', 'directPermissions'));
    }

    public function update(UpdateUserAccessRequest $request, User $user): RedirectResponse
    {
        $user->syncRoles($request->input('roles', []));
        $user->syncPermissions($request->input('permissions', []));
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return redirect()->route('user-access.edit', $user)->with('success', __('app.messages.updated', ['resource' => __('app.resources.user_access')]));
    }
}
