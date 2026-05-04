<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\School\StoreSchoolRequest;
use App\Http\Requests\School\UpdateSchoolRequest;
use App\Models\School;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SchoolController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request): View
    {
        //$this->authorize('viewAny', School::class);

        $schools = School::query()
            ->when(
                $request->filled('search'),
                fn($q) => $q->where(function ($query) use ($request): void {
                    $term = '%' . $request->string('search')->trim() . '%';
                    $query->where('name', 'like', $term)
                        ->orWhere('code', 'like', $term)
                        ->orWhere('email', 'like', $term);
                })
            )
            ->when(
                $request->filled('country'),
                fn($q) => $q->where('country', strtoupper($request->string('country')->value()))
            )
            ->when(
                $request->has('is_active'),
                fn($q) => $q->where('is_active', $request->boolean('is_active'))
            )
            ->withCount('branches')
            ->select(['id', 'code', 'name', 'email', 'country', 'currency', 'is_active', 'created_at'])
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('schools.index', compact('schools'));
    }

    public function create(): View
    {
        $this->authorize('create', School::class);

        $timezones = \DateTimeZone::listIdentifiers();

        return view('schools.create', compact('timezones'));
    }

    public function store(StoreSchoolRequest $request): RedirectResponse
    {
        $school = DB::transaction(fn() => School::create($request->validated()));

        return redirect()
            ->route('schools.show', $school)
            ->with('success', __('schools.created_successfully'));
    }

    public function show(School $school): View
    {
        $this->authorize('view', $school);

        $school->loadMissing([
            'branches' => fn($q) => $q
                ->select(['id', 'school_id', 'name', 'code', 'is_main', 'is_active'])
                ->latest(),
        ]);

        return view('schools.show', compact('school'));
    }

    public function edit(School $school): View
    {
        $this->authorize('update', $school);

        $timezones = \DateTimeZone::listIdentifiers();

        return view('schools.edit', compact('school', 'timezones'));
    }

    public function update(UpdateSchoolRequest $request, School $school): RedirectResponse
    {
        DB::transaction(fn() => $school->update($request->validated()));

        return redirect()
            ->route('schools.show', $school)
            ->with('success', __('schools.updated_successfully'));
    }

    public function destroy(School $school): RedirectResponse
    {
        $this->authorize('delete', $school);

        DB::transaction(function () use ($school): void {
            $school->branches()->delete();
            School::destroy($school->id);
        });

        return redirect()
            ->route('schools.index')
            ->with('success', __('schools.deleted_successfully'));
    }

    public function restore(int $id): RedirectResponse
    {
        $this->authorize('restore', School::class);

        $school = School::onlyTrashed()->findOrFail($id);

        DB::transaction(function () use ($school): void {
            $school->restore();
            $school->branches()->onlyTrashed()->restore();
        });

        return back()->with('success', __('schools.restored_successfully'));
    }

    public function toggleActive(School $school): RedirectResponse
    {
        $this->authorize('update', $school);

        $school->update(['is_active' => ! $school->is_active]);

        return back()->with(
            'success',
            $school->is_active
                ? __('schools.activated_successfully')
                : __('schools.deactivated_successfully')
        );
    }
}
