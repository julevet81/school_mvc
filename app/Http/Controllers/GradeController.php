<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AuthorizesDashboardRequests;
use App\Http\Requests\Grade\StoreGradeRequest;
use App\Http\Requests\Grade\UpdateGradeRequest;
use App\Models\Grade;
use App\Support\SchoolContext;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GradeController extends Controller
{
    use AuthorizesDashboardRequests;

    public function index(Request $request): View
    {
        $this->ensurePermission('academics.view');

        $grades = Grade::query()
            ->with(['school:id,name', 'branch:id,name'])
            ->when($request->filled('school_id'), fn ($query) => $query->where('school_id', $request->integer('school_id')))
            ->when($request->filled('branch_id'), fn ($query) => $query->where('branch_id', $request->integer('branch_id')))
            ->when($request->filled('search'), function ($query) use ($request): void {
                $term = '%'.$request->string('search')->trim().'%';
                $query->where('name', 'like', $term);
            })
            ->orderBy('level')
            ->paginate(12)
            ->withQueryString();

        $schools = SchoolContext::schoolOptions();
        $branches = SchoolContext::branchOptions($request->integer('school_id') ?: null);

        return view('grades.index', compact('grades', 'schools', 'branches'));
    }

    public function create(): View
    {
        $this->ensurePermission('academics.manage');

        $schools = SchoolContext::schoolOptions();
        $branches = SchoolContext::branchOptions();

        return view('grades.create', compact('schools', 'branches'));
    }

    public function store(StoreGradeRequest $request): RedirectResponse
    {
        $grade = Grade::query()->create($request->validated());

        return redirect()
            ->route('grades.edit', $grade)
            ->with('success', __('app.messages.created', ['resource' => __('app.resources.grade')]));
    }

    public function edit(Grade $grade): View
    {
        $this->ensurePermission('academics.manage');

        $schools = SchoolContext::schoolOptions();
        $branches = SchoolContext::branchOptions($grade->school_id);

        return view('grades.edit', compact('grade', 'schools', 'branches'));
    }

    public function update(UpdateGradeRequest $request, Grade $grade): RedirectResponse
    {
        $grade->update($request->validated());

        return redirect()
            ->route('grades.edit', $grade)
            ->with('success', __('app.messages.updated', ['resource' => __('app.resources.grade')]));
    }

    public function destroy(Grade $grade): RedirectResponse
    {
        $this->ensurePermission('academics.manage');

        $grade->delete();

        return redirect()
            ->route('grades.index')
            ->with('success', __('app.messages.deleted', ['resource' => __('app.resources.grade')]));
    }
}
