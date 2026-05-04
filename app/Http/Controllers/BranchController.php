<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Branch\StoreBranchRequest;
use App\Http\Requests\Branch\UpdateBranchRequest;
use App\Models\Branch;
use App\Models\School;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

final class BranchController extends Controller
{
    use AuthorizesRequests;
    public function index(Request $request, School $school): View
    {
        $this->authorize('viewAny', [Branch::class, $school]);

        $branches = $school->branches()
            ->when(
                $request->filled('search'),
                fn($q) => $q->where(function ($query) use ($request): void {
                    $term = '%' . $request->string('search')->trim() . '%';
                    $query->where('name', 'like', $term)
                        ->orWhere('code', 'like', $term);
                })
            )
            ->when(
                $request->has('is_active'),
                fn($q) => $q->where('is_active', $request->boolean('is_active'))
            )
            ->select(['id', 'school_id', 'code', 'name', 'email', 'phone', 'is_main', 'is_active', 'created_at'])
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('branches.index', compact('school', 'branches'));
    }

    public function create(School $school): View
    {
        $this->authorize('create', [Branch::class, $school]);

        abort_if(! $school->is_active, 403, __('branches.school_inactive'));

        return view('branches.create', compact('school'));
    }

    public function store(StoreBranchRequest $request, School $school): RedirectResponse
    {
        abort_if(! $school->is_active, 403, __('branches.school_inactive'));

        $branch = DB::transaction(function () use ($request, $school): Branch {
            $validated = $request->validated();

            if (! empty($validated['is_main'])) {
                $school->branches()->update(['is_main' => false]);
            }

            return $school->branches()->create($validated);
        });

        return redirect()
            ->route('schools.branches.show', [$school, $branch])
            ->with('success', __('branches.created_successfully'));
    }

    public function show(School $school, Branch $branch): View
    {
        $this->authorize('view', [$branch, $school]);

        $this->ensureBelongsToSchool($branch, $school);

        return view('branches.show', compact('school', 'branch'));
    }

    public function edit(School $school, Branch $branch): View
    {
        $this->authorize('update', [$branch, $school]);

        $this->ensureBelongsToSchool($branch, $school);

        return view('branches.edit', compact('school', 'branch'));
    }

    public function update(UpdateBranchRequest $request, School $school, Branch $branch): RedirectResponse
    {
        $this->ensureBelongsToSchool($branch, $school);

        DB::transaction(function () use ($request, $school, $branch): void {
            $validated = $request->validated();

            if (! empty($validated['is_main']) && ! $branch->is_main) {
                $school->branches()
                    ->where('id', '!=', $branch->id)
                    ->update(['is_main' => false]);
            }

            $branch->update($validated);
        });

        return redirect()
            ->route('schools.branches.show', [$school, $branch])
            ->with('success', __('branches.updated_successfully'));
    }

    public function destroy(School $school, Branch $branch): RedirectResponse
    {
        $this->authorize('delete', [$branch, $school]);

        $this->ensureBelongsToSchool($branch, $school);

        abort_if(
            $branch->is_main && $school->branches()->where('is_active', true)->count() > 1,
            422,
            __('branches.cannot_delete_main')
        );

        DB::transaction(fn() => $branch->delete($branch->id));

        return redirect()
            ->route('schools.branches.index', $school)
            ->with('success', __('branches.deleted_successfully'));
    }

    public function restore(School $school, int $id): RedirectResponse
    {
        $this->authorize('restore', [Branch::class, $school]);

        $branch = $school->branches()->onlyTrashed()->findOrFail($id);
        $branch->restore();

        return back()->with('success', __('branches.restored_successfully'));
    }

    public function setAsMain(School $school, Branch $branch): RedirectResponse
    {
        $this->authorize('update', [$branch, $school]);

        $this->ensureBelongsToSchool($branch, $school);

        abort_if(! $branch->is_active, 422, __('branches.cannot_set_inactive_as_main'));

        DB::transaction(function () use ($school, $branch): void {
            $school->branches()->update(['is_main' => false]);
            $branch->update(['is_main' => true]);
        });

        return back()->with('success', __('branches.main_updated_successfully'));
    }

    public function toggleActive(School $school, Branch $branch): RedirectResponse
    {
        $this->authorize('update', [$branch, $school]);

        $this->ensureBelongsToSchool($branch, $school);

        abort_if(
            $branch->is_main && $branch->is_active,
            422,
            __('branches.cannot_deactivate_main')
        );

        $branch->update(['is_active' => ! $branch->is_active]);

        return back()->with(
            'success',
            $branch->is_active
                ? __('branches.activated_successfully')
                : __('branches.deactivated_successfully')
        );
    }

    private function ensureBelongsToSchool(Branch $branch, School $school): void
    {
        abort_if(
            $branch->school_id !== $school->id,
            404,
            __('branches.not_found_for_school')
        );
    }
}
