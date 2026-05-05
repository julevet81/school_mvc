@csrf
@if(isset($fee)) @method('PUT') @endif
<div class="row">
    <div class="col-md-4 mb-3"><label>{{ __('app.fields.school') }}</label><select name="school_id" class="form-control">@foreach($schools as $school)<option value="{{ $school->id }}" @selected(old('school_id', $fee->school_id ?? '') == $school->id)>{{ $school->name }}</option>@endforeach</select></div>
    <div class="col-md-4 mb-3"><label>{{ __('app.fields.branch') }}</label><select name="branch_id" class="form-control">@foreach($branches as $branch)<option value="{{ $branch->id }}" @selected(old('branch_id', $fee->branch_id ?? '') == $branch->id)>{{ $branch->name }}</option>@endforeach</select></div>
    <div class="col-md-4 mb-3"><label>{{ __('app.fields.code') }}</label><input name="code" class="form-control" value="{{ old('code', $fee->code ?? '') }}" required></div>
    <div class="col-md-6 mb-3"><label>{{ __('app.fields.name') }}</label><input name="name" class="form-control" value="{{ old('name', $fee->name ?? '') }}" required></div>
    <div class="col-md-3 mb-3"><label>{{ __('app.fields.amount') }}</label><input type="number" step="0.01" min="0" name="amount" class="form-control" value="{{ old('amount', $fee->amount ?? 0) }}" required></div>
    <div class="col-md-3 mb-3"><label>{{ __('app.fields.frequency') }}</label><input name="frequency" class="form-control" value="{{ old('frequency', $fee->frequency ?? 'term') }}" required></div>
    <div class="col-md-12"><div class="form-check"><input class="form-check-input" type="checkbox" name="is_active" value="1" @checked(old('is_active', $fee->is_active ?? true))><label class="form-check-label">{{ __('app.fields.is_active') }}</label></div></div>
</div>
<button class="btn btn-primary mt-3">{{ __('app.actions.save') }}</button>
