<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AuthorizesDashboardRequests;
use App\Http\Requests\Permission\StorePermissionRequest;
use App\Http\Requests\Permission\UpdatePermissionRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionController extends Controller
{
    use AuthorizesDashboardRequests;

    public function index(Request $request): View
    {
        $this->ensureAuthenticated();

        $permissions = Permission::query()
            ->withCount('roles')
            ->when($request->filled('search'), fn ($query) => $query->where('name', 'like', '%'.$request->string('search')->trim().'%'))
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('permissions.index', compact('permissions'));
    }

    public function create(): View
    {
        $this->ensureAuthenticated();

        return view('permissions.create');
    }

    public function store(StorePermissionRequest $request): RedirectResponse
    {
        $permission = Permission::query()->create([
            'name' => $request->string('name')->value(),
            'guard_name' => 'web',
        ]);

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return redirect()->route('permissions.edit', $permission)->with('success', __('app.messages.created', ['resource' => __('app.resources.permission')]));
    }

    public function edit(Permission $permission): View
    {
        $this->ensureAuthenticated();

        return view('permissions.edit', compact('permission'));
    }

    public function update(UpdatePermissionRequest $request, Permission $permission): RedirectResponse
    {
        $permission->update(['name' => $request->string('name')->value()]);
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return redirect()->route('permissions.edit', $permission)->with('success', __('app.messages.updated', ['resource' => __('app.resources.permission')]));
    }

    public function destroy(Permission $permission): RedirectResponse
    {
        $this->ensureAuthenticated();
        $permission->delete();
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return redirect()->route('permissions.index')->with('success', __('app.messages.deleted', ['resource' => __('app.resources.permission')]));
    }
}
