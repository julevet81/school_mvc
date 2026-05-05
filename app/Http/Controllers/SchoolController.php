<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AuthorizesDashboardRequests;
use App\Http\Requests\School\StoreSchoolRequest;
use App\Http\Requests\School\UpdateSchoolRequest;
use App\Models\School;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SchoolController extends Controller
{
    use AuthorizesDashboardRequests;

    public function index(Request $request): View
    {
        $this->ensurePermission('settings.manage');

        $schools = School::query()
            ->when($request->filled('search'), function ($query) use ($request): void {
                $term = '%'.$request->string('search')->trim().'%';
                $query->where(function ($schoolQuery) use ($term): void {
                    $schoolQuery
                        ->where('name', 'like', $term)
                        ->orWhere('code', 'like', $term)
                        ->orWhere('email', 'like', $term)
                        ->orWhere('country', 'like', $term);
                });
            })
            ->when($request->filled('status'), fn ($query) => $query->where('is_active', $request->string('status')->value() === 'active'))
            ->withCount('branches')
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('schools.index', compact('schools'));
    }

    public function create(): View
    {
        $this->ensurePermission('settings.manage');

        $timezones = \DateTimeZone::listIdentifiers();

        return view('schools.create', compact('timezones'));
    }

    public function store(StoreSchoolRequest $request): RedirectResponse
    {
        $school = School::query()->create($request->validated());

        return redirect()
            ->route('schools.edit', $school)
            ->with('success', __('app.messages.created', ['resource' => __('app.resources.school')]));
    }

    public function edit(School $school): View
    {
        $this->ensurePermission('settings.manage');

        $school->loadCount('branches');
        $timezones = \DateTimeZone::listIdentifiers();

        return view('schools.edit', compact('school', 'timezones'));
    }

    public function update(UpdateSchoolRequest $request, School $school): RedirectResponse
    {
        $school->update($request->validated());

        return redirect()
            ->route('schools.edit', $school)
            ->with('success', __('app.messages.updated', ['resource' => __('app.resources.school')]));
    }

    public function destroy(School $school): RedirectResponse
    {
        $this->ensurePermission('settings.manage');

        $school->delete();

        return redirect()
            ->route('schools.index')
            ->with('success', __('app.messages.deleted', ['resource' => __('app.resources.school')]));
    }
}
