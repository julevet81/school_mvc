<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AuthorizesDashboardRequests;
use App\Http\Requests\Fee\StoreFeeRequest;
use App\Http\Requests\Fee\UpdateFeeRequest;
use App\Models\Fee;
use App\Support\SchoolContext;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FeeController extends Controller
{
    use AuthorizesDashboardRequests;

    public function index(Request $request): View
    {
        $this->ensurePermission('finance.view');

        $fees = Fee::query()
            ->with(['school:id,name', 'branch:id,name'])
            ->when($request->filled('school_id'), fn ($query) => $query->where('school_id', $request->integer('school_id')))
            ->when($request->filled('branch_id'), fn ($query) => $query->where('branch_id', $request->integer('branch_id')))
            ->when($request->filled('status'), fn ($query) => $query->where('is_active', $request->string('status')->value() === 'active'))
            ->when($request->filled('search'), function ($query) use ($request): void {
                $term = '%'.$request->string('search')->trim().'%';
                $query->where(function ($feeQuery) use ($term): void {
                    $feeQuery
                        ->where('name', 'like', $term)
                        ->orWhere('code', 'like', $term);
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $schools = SchoolContext::schoolOptions();
        $branches = SchoolContext::branchOptions($request->integer('school_id') ?: null);

        return view('fees.index', compact('fees', 'schools', 'branches'));
    }

    public function create(): View
    {
        $this->ensurePermission('finance.manage');

        $schools = SchoolContext::schoolOptions();
        $branches = SchoolContext::branchOptions();

        return view('fees.create', compact('schools', 'branches'));
    }

    public function store(StoreFeeRequest $request): RedirectResponse
    {
        $fee = Fee::query()->create($request->validated());

        return redirect()
            ->route('fees.edit', $fee)
            ->with('success', __('app.messages.created', ['resource' => __('app.resources.fee')]));
    }

    public function edit(Fee $fee): View
    {
        $this->ensurePermission('finance.manage');

        $schools = SchoolContext::schoolOptions();
        $branches = SchoolContext::branchOptions($fee->school_id);

        return view('fees.edit', compact('fee', 'schools', 'branches'));
    }

    public function update(UpdateFeeRequest $request, Fee $fee): RedirectResponse
    {
        $fee->update($request->validated());

        return redirect()
            ->route('fees.edit', $fee)
            ->with('success', __('app.messages.updated', ['resource' => __('app.resources.fee')]));
    }

    public function destroy(Fee $fee): RedirectResponse
    {
        $this->ensurePermission('finance.manage');

        $fee->delete();

        return redirect()
            ->route('fees.index')
            ->with('success', __('app.messages.deleted', ['resource' => __('app.resources.fee')]));
    }
}
