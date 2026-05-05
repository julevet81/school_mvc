<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AuthorizesDashboardRequests;
use App\Http\Requests\Branch\StoreBranchRequest;
use App\Http\Requests\Branch\UpdateBranchRequest;
use App\Models\Branch;
use App\Models\School;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BranchController extends Controller
{
    use AuthorizesDashboardRequests;

    public function index(Request $request, School $school): View
    {
        $this->ensurePermission('settings.manage');

        $branches = $school->branches()
            ->when($request->filled('search'), function ($query) use ($request): void {
                $term = '%'.$request->string('search')->trim().'%';
                $query->where(function ($branchQuery) use ($term): void {
                    $branchQuery
                        ->where('name', 'like', $term)
                        ->orWhere('code', 'like', $term)
                        ->orWhere('email', 'like', $term);
                });
            })
            ->when($request->filled('status'), fn ($query) => $query->where('is_active', $request->string('status')->value() === 'active'))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('branches.index', compact('school', 'branches'));
    }

    public function create(School $school): View
    {
        $this->ensurePermission('settings.manage');

        return view('branches.create', compact('school'));
    }

    public function store(StoreBranchRequest $request, School $school): RedirectResponse
    {
        $branch = DB::transaction(function () use ($request, $school): Branch {
            $data = $request->validated();

            if (($data['is_main'] ?? false) === true) {
                $school->branches()->update(['is_main' => false]);
            }

            return $school->branches()->create($data);
        });

        return redirect()
            ->route('schools.branches.edit', [$school, $branch])
            ->with('success', __('app.messages.created', ['resource' => __('app.resources.branch')]));
    }

    public function edit(School $school, Branch $branch): View
    {
        $this->ensurePermission('settings.manage');
        abort_unless($branch->school_id === $school->id, 404);

        return view('branches.edit', compact('school', 'branch'));
    }

    public function update(UpdateBranchRequest $request, School $school, Branch $branch): RedirectResponse
    {
        abort_unless($branch->school_id === $school->id, 404);

        DB::transaction(function () use ($request, $school, $branch): void {
            $data = $request->validated();

            if (($data['is_main'] ?? false) === true) {
                $school->branches()->whereKeyNot($branch->id)->update(['is_main' => false]);
            }

            $branch->update($data);
        });

        return redirect()
            ->route('schools.branches.edit', [$school, $branch])
            ->with('success', __('app.messages.updated', ['resource' => __('app.resources.branch')]));
    }

    public function destroy(School $school, Branch $branch): RedirectResponse
    {
        $this->ensurePermission('settings.manage');
        abort_unless($branch->school_id === $school->id, 404);

        $branch->delete();

        return redirect()
            ->route('schools.branches.index', $school)
            ->with('success', __('app.messages.deleted', ['resource' => __('app.resources.branch')]));
    }
}
