@csrf
@if(isset($invoice)) @method('PUT') @endif
<div class="row">
    <div class="col-md-4 mb-3"><label>{{ __('app.fields.school') }}</label><select name="school_id" class="form-control">@foreach($schools as $school)<option value="{{ $school->id }}" @selected(old('school_id', $invoice->school_id ?? '') == $school->id)>{{ $school->name }}</option>@endforeach</select></div>
    <div class="col-md-4 mb-3"><label>{{ __('app.fields.branch') }}</label><select name="branch_id" class="form-control">@foreach($branches as $branch)<option value="{{ $branch->id }}" @selected(old('branch_id', $invoice->branch_id ?? '') == $branch->id)>{{ $branch->name }}</option>@endforeach</select></div>
    <div class="col-md-4 mb-3"><label>{{ __('app.fields.student') }}</label><select name="student_id" class="form-control">@foreach($students as $student)<option value="{{ $student->id }}" @selected(old('student_id', $invoice->student_id ?? '') == $student->id)>{{ $student->full_name }}</option>@endforeach</select></div>
    <div class="col-md-3 mb-3"><label>{{ __('app.fields.invoice_no') }}</label><input name="invoice_no" class="form-control" value="{{ old('invoice_no', $invoice->invoice_no ?? '') }}" required></div>
    <div class="col-md-3 mb-3"><label>{{ __('app.fields.issue_date') }}</label><input type="date" name="issue_date" class="form-control" value="{{ old('issue_date', isset($invoice) && $invoice->issue_date ? $invoice->issue_date->format('Y-m-d') : '') }}" required></div>
    <div class="col-md-3 mb-3"><label>{{ __('app.fields.due_date') }}</label><input type="date" name="due_date" class="form-control" value="{{ old('due_date', isset($invoice) && $invoice->due_date ? $invoice->due_date->format('Y-m-d') : '') }}" required></div>
    <div class="col-md-3 mb-3"><label>{{ __('app.fields.status') }}</label><input name="status" class="form-control" value="{{ old('status', $invoice->status ?? 'draft') }}" required></div>
    <div class="col-md-3 mb-3"><label>{{ __('app.fields.subtotal') }}</label><input type="number" step="0.01" min="0" name="subtotal" class="form-control" value="{{ old('subtotal', $invoice->subtotal ?? 0) }}" required></div>
    <div class="col-md-3 mb-3"><label>{{ __('app.fields.discount_total') }}</label><input type="number" step="0.01" min="0" name="discount_total" class="form-control" value="{{ old('discount_total', $invoice->discount_total ?? 0) }}"></div>
    <div class="col-md-3 mb-3"><label>{{ __('app.fields.penalty_total') }}</label><input type="number" step="0.01" min="0" name="penalty_total" class="form-control" value="{{ old('penalty_total', $invoice->penalty_total ?? 0) }}"></div>
    <div class="col-md-3 mb-3"><label>{{ __('app.fields.paid_amount') }}</label><input type="number" step="0.01" min="0" name="paid_amount" class="form-control" value="{{ old('paid_amount', $invoice->paid_amount ?? 0) }}"></div>
</div>
<button class="btn btn-primary">{{ __('app.actions.save') }}</button>
