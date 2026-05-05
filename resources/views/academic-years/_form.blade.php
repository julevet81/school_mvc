@csrf
@if(isset($academicYear)) @method('PUT') @endif
<div class="row">
    <div class="col-md-4 mb-3"><label>{{ __('app.fields.school') }}</label><select name="school_id" class="form-control" required>@foreach($schools as $school)<option value="{{ $school->id }}" @selected(old('school_id', $academicYear->school_id ?? request('school_id')) == $school->id)>{{ $school->name }}</option>@endforeach</select></div>
    <div class="col-md-4 mb-3"><label>{{ __('app.fields.branch') }}</label><select name="branch_id" class="form-control" required>@foreach($branches as $branch)<option value="{{ $branch->id }}" @selected(old('branch_id', $academicYear->branch_id ?? request('branch_id')) == $branch->id)>{{ $branch->name }}</option>@endforeach</select></div>
    <div class="col-md-4 mb-3"><label>{{ __('app.fields.name') }}</label><input name="name" class="form-control" value="{{ old('name', $academicYear->name ?? '') }}" required></div>
    <div class="col-md-4 mb-3"><label>{{ __('app.fields.start_date') }}</label><input type="date" name="start_date" class="form-control" value="{{ old('start_date', isset($academicYear) && $academicYear->start_date ? $academicYear->start_date->format('Y-m-d') : '') }}" required></div>
    <div class="col-md-4 mb-3"><label>{{ __('app.fields.end_date') }}</label><input type="date" name="end_date" class="form-control" value="{{ old('end_date', isset($academicYear) && $academicYear->end_date ? $academicYear->end_date->format('Y-m-d') : '') }}" required></div>
    <div class="col-md-4 d-flex align-items-center"><div class="form-check mt-4"><input class="form-check-input" type="checkbox" name="is_current" value="1" @checked(old('is_current', $academicYear->is_current ?? false))><label class="form-check-label">{{ __('app.fields.is_current') }}</label></div></div>
</div>
<button class="btn btn-primary">{{ __('app.actions.save') }}</button>
