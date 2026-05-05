<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AuthorizesDashboardRequests;
use App\Http\Requests\Classroom\StoreClassroomRequest;
use App\Http\Requests\Classroom\UpdateClassroomRequest;
use App\Models\Classroom;
use App\Models\Grade;
use App\Support\SchoolContext;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClassroomController extends Controller
{
    use AuthorizesDashboardRequests;

    public function index(Request $request): View
    {
        $this->ensurePermission('academics.view');

        $classrooms = Classroom::query()
            ->with(['school:id,name', 'branch:id,name', 'grade:id,name'])
            ->when($request->filled('school_id'), fn ($query) => $query->where('school_id', $request->integer('school_id')))
            ->when($request->filled('branch_id'), fn ($query) => $query->where('branch_id', $request->integer('branch_id')))
            ->when($request->filled('grade_id'), fn ($query) => $query->where('grade_id', $request->integer('grade_id')))
            ->when($request->filled('search'), fn ($query) => $query->where('name', 'like', '%'.$request->string('search')->trim().'%'))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $schools = SchoolContext::schoolOptions();
        $branches = SchoolContext::branchOptions($request->integer('school_id') ?: null);
        $grades = Grade::query()
            ->when($request->filled('school_id'), fn ($query) => $query->where('school_id', $request->integer('school_id')))
            ->when($request->filled('branch_id'), fn ($query) => $query->where('branch_id', $request->integer('branch_id')))
            ->orderBy('level')
            ->get(['id', 'name']);

        return view('classrooms.index', compact('classrooms', 'schools', 'branches', 'grades'));
    }

    public function create(): View
    {
        $this->ensurePermission('academics.manage');

        $schools = SchoolContext::schoolOptions();
        $branches = SchoolContext::branchOptions();
        $grades = Grade::query()->orderBy('level')->get(['id', 'name']);

        return view('classrooms.create', compact('schools', 'branches', 'grades'));
    }

    public function store(StoreClassroomRequest $request): RedirectResponse
    {
        $classroom = Classroom::query()->create($request->validated());

        return redirect()
            ->route('classrooms.edit', $classroom)
            ->with('success', __('app.messages.created', ['resource' => __('app.resources.classroom')]));
    }

    public function edit(Classroom $classroom): View
    {
        $this->ensurePermission('academics.manage');

        $schools = SchoolContext::schoolOptions();
        $branches = SchoolContext::branchOptions($classroom->school_id);
        $grades = Grade::query()
            ->where('school_id', $classroom->school_id)
            ->where('branch_id', $classroom->branch_id)
            ->orderBy('level')
            ->get(['id', 'name']);

        return view('classrooms.edit', compact('classroom', 'schools', 'branches', 'grades'));
    }

    public function update(UpdateClassroomRequest $request, Classroom $classroom): RedirectResponse
    {
        $classroom->update($request->validated());

        return redirect()
            ->route('classrooms.edit', $classroom)
            ->with('success', __('app.messages.updated', ['resource' => __('app.resources.classroom')]));
    }

    public function destroy(Classroom $classroom): RedirectResponse
    {
        $this->ensurePermission('academics.manage');

        $classroom->delete();

        return redirect()
            ->route('classrooms.index')
            ->with('success', __('app.messages.deleted', ['resource' => __('app.resources.classroom')]));
    }
}
