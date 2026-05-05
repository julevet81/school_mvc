@csrf
@if(isset($employee)) @method('PUT') @endif
<div class="row">
    <div class="col-md-4 mb-3"><label>{{ __('app.fields.school') }}</label><select name="school_id" class="form-control">@foreach($schools as $school)<option value="{{ $school->id }}" @selected(old('school_id', $employee->school_id ?? '') == $school->id)>{{ $school->name }}</option>@endforeach</select></div>
    <div class="col-md-4 mb-3"><label>{{ __('app.fields.branch') }}</label><select name="branch_id" class="form-control">@foreach($branches as $branch)<option value="{{ $branch->id }}" @selected(old('branch_id', $employee->branch_id ?? '') == $branch->id)>{{ $branch->name }}</option>@endforeach</select></div>
    <div class="col-md-4 mb-3"><label>{{ __('app.fields.employee_no') }}</label><input name="employee_no" class="form-control" value="{{ old('employee_no', $employee->employee_no ?? '') }}" required></div>
    <div class="col-md-4 mb-3"><label>{{ __('app.fields.job_title') }}</label><input name="job_title" class="form-control" value="{{ old('job_title', $employee->job_title ?? '') }}"></div>
    <div class="col-md-4 mb-3"><label>{{ __('app.fields.hire_date') }}</label><input type="date" name="hire_date" class="form-control" value="{{ old('hire_date', isset($employee) && $employee->hire_date ? $employee->hire_date->format('Y-m-d') : '') }}"></div>
    <div class="col-md-2 mb-3"><label>{{ __('app.fields.type') }}</label><input name="employment_type" class="form-control" value="{{ old('employment_type', $employee->employment_type ?? 'full_time') }}" required></div>
    <div class="col-md-2 mb-3"><label>{{ __('app.fields.status') }}</label><input name="status" class="form-control" value="{{ old('status', $employee->status ?? 'active') }}" required></div>
</div>
<button class="btn btn-primary">{{ __('app.actions.save') }}</button>
