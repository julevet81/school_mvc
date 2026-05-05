<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AuthorizesDashboardRequests;
use App\Http\Requests\Invoice\StoreInvoiceRequest;
use App\Http\Requests\Invoice\UpdateInvoiceRequest;
use App\Models\Invoice;
use App\Models\Student;
use App\Support\SchoolContext;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    use AuthorizesDashboardRequests;

    public function index(Request $request): View
    {
        $this->ensurePermission('finance.view');

        $invoices = Invoice::query()
            ->with(['school:id,name', 'branch:id,name', 'student:id,full_name'])
            ->when($request->filled('school_id'), fn ($query) => $query->where('school_id', $request->integer('school_id')))
            ->when($request->filled('branch_id'), fn ($query) => $query->where('branch_id', $request->integer('branch_id')))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')->value()))
            ->when($request->filled('search'), function ($query) use ($request): void {
                $term = '%'.$request->string('search')->trim().'%';
                $query->where(function ($invoiceQuery) use ($term): void {
                    $invoiceQuery
                        ->where('invoice_no', 'like', $term)
                        ->orWhereHas('student', fn ($studentQuery) => $studentQuery->where('full_name', 'like', $term));
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $schools = SchoolContext::schoolOptions();
        $branches = SchoolContext::branchOptions($request->integer('school_id') ?: null);

        return view('invoices.index', compact('invoices', 'schools', 'branches'));
    }

    public function create(): View
    {
        $this->ensurePermission('finance.manage');

        $schools = SchoolContext::schoolOptions();
        $branches = SchoolContext::branchOptions();
        $students = Student::query()->orderBy('full_name')->get(['id', 'school_id', 'branch_id', 'full_name']);

        return view('invoices.create', compact('schools', 'branches', 'students'));
    }

    public function store(StoreInvoiceRequest $request): RedirectResponse
    {
        $data = $this->normalizeTotals($request->validated());
        $invoice = Invoice::query()->create($data);

        return redirect()
            ->route('invoices.edit', $invoice)
            ->with('success', __('app.messages.created', ['resource' => __('app.resources.invoice')]));
    }

    public function edit(Invoice $invoice): View
    {
        $this->ensurePermission('finance.manage');

        $schools = SchoolContext::schoolOptions();
        $branches = SchoolContext::branchOptions($invoice->school_id);
        $students = Student::query()
            ->where('school_id', $invoice->school_id)
            ->where('branch_id', $invoice->branch_id)
            ->orderBy('full_name')
            ->get(['id', 'full_name']);

        return view('invoices.edit', compact('invoice', 'schools', 'branches', 'students'));
    }

    public function update(UpdateInvoiceRequest $request, Invoice $invoice): RedirectResponse
    {
        $invoice->update($this->normalizeTotals($request->validated()));

        return redirect()
            ->route('invoices.edit', $invoice)
            ->with('success', __('app.messages.updated', ['resource' => __('app.resources.invoice')]));
    }

    public function destroy(Invoice $invoice): RedirectResponse
    {
        $this->ensurePermission('finance.manage');

        $invoice->delete();

        return redirect()
            ->route('invoices.index')
            ->with('success', __('app.messages.deleted', ['resource' => __('app.resources.invoice')]));
    }

    private function normalizeTotals(array $data): array
    {
        $subtotal = (float) ($data['subtotal'] ?? 0);
        $discount = (float) ($data['discount_total'] ?? 0);
        $penalty = (float) ($data['penalty_total'] ?? 0);
        $paid = (float) ($data['paid_amount'] ?? 0);

        $data['total'] = max(0, $subtotal - $discount + $penalty);
        $data['paid_amount'] = min($paid, $data['total']);

        return $data;
    }
}
