@csrf
@if(isset($classroom)) @method('PUT') @endif
<div class="row">
    <div class="col-md-4 mb-3"><label>{{ __('app.fields.school') }}</label><select name="school_id" class="form-control">@foreach($schools as $school)<option value="{{ $school->id }}" @selected(old('school_id', $classroom->school_id ?? '') == $school->id)>{{ $school->name }}</option>@endforeach</select></div>
    <div class="col-md-4 mb-3"><label>{{ __('app.fields.branch') }}</label><select name="branch_id" class="form-control">@foreach($branches as $branch)<option value="{{ $branch->id }}" @selected(old('branch_id', $classroom->branch_id ?? '') == $branch->id)>{{ $branch->name }}</option>@endforeach</select></div>
    <div class="col-md-4 mb-3"><label>{{ __('app.fields.grade') }}</label><select name="grade_id" class="form-control">@foreach($grades as $grade)<option value="{{ $grade->id }}" @selected(old('grade_id', $classroom->grade_id ?? '') == $grade->id)>{{ $grade->name }}</option>@endforeach</select></div>
    <div class="col-md-6 mb-3"><label>{{ __('app.fields.name') }}</label><input name="name" class="form-control" value="{{ old('name', $classroom->name ?? '') }}" required></div>
    <div class="col-md-6 mb-3"><label>{{ __('app.fields.capacity') }}</label><input type="number" min="1" name="capacity" class="form-control" value="{{ old('capacity', $classroom->capacity ?? '') }}"></div>
</div>
<button class="btn btn-primary">{{ __('app.actions.save') }}</button>
