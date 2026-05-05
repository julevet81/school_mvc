@csrf
@if(isset($grade)) @method('PUT') @endif
<div class="row">
    <div class="col-md-4 mb-3"><label>{{ __('app.fields.school') }}</label><select name="school_id" class="form-control">@foreach($schools as $school)<option value="{{ $school->id }}" @selected(old('school_id', $grade->school_id ?? '') == $school->id)>{{ $school->name }}</option>@endforeach</select></div>
    <div class="col-md-4 mb-3"><label>{{ __('app.fields.branch') }}</label><select name="branch_id" class="form-control">@foreach($branches as $branch)<option value="{{ $branch->id }}" @selected(old('branch_id', $grade->branch_id ?? '') == $branch->id)>{{ $branch->name }}</option>@endforeach</select></div>
    <div class="col-md-4 mb-3"><label>{{ __('app.fields.name') }}</label><input name="name" class="form-control" value="{{ old('name', $grade->name ?? '') }}" required></div>
    <div class="col-md-4 mb-3"><label>{{ __('app.fields.level') }}</label><input type="number" min="1" max="20" name="level" class="form-control" value="{{ old('level', $grade->level ?? 1) }}" required></div>
</div>
<button class="btn btn-primary">{{ __('app.actions.save') }}</button>
