<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AuthorizesDashboardRequests;
use App\Http\Requests\Student\StoreStudentRequest;
use App\Http\Requests\Student\UpdateStudentRequest;
use App\Models\Student;
use App\Support\SchoolContext;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentController extends Controller
{
    use AuthorizesDashboardRequests;

    public function index(Request $request): View
    {
        $this->ensurePermission('students.view');

        $students = Student::query()
            ->with(['school:id,name', 'branch:id,name'])
            ->when($request->filled('school_id'), fn ($query) => $query->where('school_id', $request->integer('school_id')))
            ->when($request->filled('branch_id'), fn ($query) => $query->where('branch_id', $request->integer('branch_id')))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')->value()))
            ->when($request->filled('search'), function ($query) use ($request): void {
                $term = '%'.$request->string('search')->trim().'%';
                $query->where(function ($studentQuery) use ($term): void {
                    $studentQuery
                        ->where('full_name', 'like', $term)
                        ->orWhere('student_no', 'like', $term);
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $schools = SchoolContext::schoolOptions();
        $branches = SchoolContext::branchOptions($request->integer('school_id') ?: null);

        return view('students.index', compact('students', 'schools', 'branches'));
    }

    public function create(): View
    {
        $this->ensurePermission('students.create');

        $schools = SchoolContext::schoolOptions();
        $branches = SchoolContext::branchOptions();

        return view('students.create', compact('schools', 'branches'));
    }

    public function store(StoreStudentRequest $request): RedirectResponse
    {
        $student = Student::query()->create($request->validated());

        return redirect()
            ->route('students.edit', $student)
            ->with('success', __('app.messages.created', ['resource' => __('app.resources.student')]));
    }

    public function edit(Student $student): View
    {
        $this->ensurePermission('students.update');

        $schools = SchoolContext::schoolOptions();
        $branches = SchoolContext::branchOptions($student->school_id);

        return view('students.edit', compact('student', 'schools', 'branches'));
    }

    public function update(UpdateStudentRequest $request, Student $student): RedirectResponse
    {
        $student->update($request->validated());

        return redirect()
            ->route('students.edit', $student)
            ->with('success', __('app.messages.updated', ['resource' => __('app.resources.student')]));
    }

    public function destroy(Student $student): RedirectResponse
    {
        $this->ensurePermission('students.update');

        $student->delete();

        return redirect()
            ->route('students.index')
            ->with('success', __('app.messages.deleted', ['resource' => __('app.resources.student')]));
    }
}
