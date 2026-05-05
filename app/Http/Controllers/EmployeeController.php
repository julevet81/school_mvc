<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AuthorizesDashboardRequests;
use App\Http\Requests\Employee\StoreEmployeeRequest;
use App\Http\Requests\Employee\UpdateEmployeeRequest;
use App\Models\Employee;
use App\Support\SchoolContext;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    use AuthorizesDashboardRequests;

    public function index(Request $request): View
    {
        $this->ensurePermission('hr.view');

        $employees = Employee::query()
            ->with(['school:id,name', 'branch:id,name'])
            ->when($request->filled('school_id'), fn ($query) => $query->where('school_id', $request->integer('school_id')))
            ->when($request->filled('branch_id'), fn ($query) => $query->where('branch_id', $request->integer('branch_id')))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')->value()))
            ->when($request->filled('search'), function ($query) use ($request): void {
                $term = '%'.$request->string('search')->trim().'%';
                $query->where(function ($employeeQuery) use ($term): void {
                    $employeeQuery
                        ->where('employee_no', 'like', $term)
                        ->orWhere('job_title', 'like', $term);
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $schools = SchoolContext::schoolOptions();
        $branches = SchoolContext::branchOptions($request->integer('school_id') ?: null);

        return view('employees.index', compact('employees', 'schools', 'branches'));
    }

    public function create(): View
    {
        $this->ensurePermission('hr.manage');

        $schools = SchoolContext::schoolOptions();
        $branches = SchoolContext::branchOptions();

        return view('employees.create', compact('schools', 'branches'));
    }

    public function store(StoreEmployeeRequest $request): RedirectResponse
    {
        $employee = Employee::query()->create($request->validated());

        return redirect()
            ->route('employees.edit', $employee)
            ->with('success', __('app.messages.created', ['resource' => __('app.resources.employee')]));
    }

    public function edit(Employee $employee): View
    {
        $this->ensurePermission('hr.manage');

        $schools = SchoolContext::schoolOptions();
        $branches = SchoolContext::branchOptions($employee->school_id);

        return view('employees.edit', compact('employee', 'schools', 'branches'));
    }

    public function update(UpdateEmployeeRequest $request, Employee $employee): RedirectResponse
    {
        $employee->update($request->validated());

        return redirect()
            ->route('employees.edit', $employee)
            ->with('success', __('app.messages.updated', ['resource' => __('app.resources.employee')]));
    }

    public function destroy(Employee $employee): RedirectResponse
    {
        $this->ensurePermission('hr.manage');

        $employee->delete();

        return redirect()
            ->route('employees.index')
            ->with('success', __('app.messages.deleted', ['resource' => __('app.resources.employee')]));
    }
}
