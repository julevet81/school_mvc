<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AuthorizesDashboardRequests;
use App\Http\Requests\AcademicYear\StoreAcademicYearRequest;
use App\Http\Requests\AcademicYear\UpdateAcademicYearRequest;
use App\Models\AcademicYear;
use App\Support\SchoolContext;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AcademicYearController extends Controller
{
    use AuthorizesDashboardRequests;

    public function index(Request $request): View
    {
        $this->ensurePermission('academics.view');

        $academicYears = AcademicYear::query()
            ->with(['school:id,name', 'branch:id,name'])
            ->when($request->filled('school_id'), fn ($query) => $query->where('school_id', $request->integer('school_id')))
            ->when($request->filled('branch_id'), fn ($query) => $query->where('branch_id', $request->integer('branch_id')))
            ->when($request->filled('status'), fn ($query) => $query->where('is_current', $request->string('status')->value() === 'current'))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $schools = SchoolContext::schoolOptions();
        $branches = SchoolContext::branchOptions($request->integer('school_id') ?: null);

        return view('academic-years.index', compact('academicYears', 'schools', 'branches'));
    }

    public function create(): View
    {
        $this->ensurePermission('academics.manage');

        $schools = SchoolContext::schoolOptions();
        $branches = SchoolContext::branchOptions();

        return view('academic-years.create', compact('schools', 'branches'));
    }

    public function store(StoreAcademicYearRequest $request): RedirectResponse
    {
        $academicYear = DB::transaction(function () use ($request): AcademicYear {
            $data = $request->validated();

            if (($data['is_current'] ?? false) === true) {
                AcademicYear::query()
                    ->where('school_id', $data['school_id'])
                    ->where('branch_id', $data['branch_id'])
                    ->update(['is_current' => false]);
            }

            return AcademicYear::query()->create($data);
        });

        return redirect()
            ->route('academic-years.edit', $academicYear)
            ->with('success', __('app.messages.created', ['resource' => __('app.resources.academic_year')]));
    }

    public function edit(AcademicYear $academicYear): View
    {
        $this->ensurePermission('academics.manage');

        $schools = SchoolContext::schoolOptions();
        $branches = SchoolContext::branchOptions($academicYear->school_id);

        return view('academic-years.edit', compact('academicYear', 'schools', 'branches'));
    }

    public function update(UpdateAcademicYearRequest $request, AcademicYear $academicYear): RedirectResponse
    {
        DB::transaction(function () use ($request, $academicYear): void {
            $data = $request->validated();

            if (($data['is_current'] ?? false) === true) {
                AcademicYear::query()
                    ->where('school_id', $data['school_id'])
                    ->where('branch_id', $data['branch_id'])
                    ->whereKeyNot($academicYear->id)
                    ->update(['is_current' => false]);
            }

            $academicYear->update($data);
        });

        return redirect()
            ->route('academic-years.edit', $academicYear)
            ->with('success', __('app.messages.updated', ['resource' => __('app.resources.academic_year')]));
    }

    public function destroy(AcademicYear $academicYear): RedirectResponse
    {
        $this->ensurePermission('academics.manage');

        $academicYear->delete();

        return redirect()
            ->route('academic-years.index')
            ->with('success', __('app.messages.deleted', ['resource' => __('app.resources.academic_year')]));
    }
}
