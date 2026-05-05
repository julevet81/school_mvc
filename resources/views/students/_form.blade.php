@csrf
@if(isset($student)) @method('PUT') @endif
<div class="row">
    <div class="col-md-4 mb-3"><label>{{ __('app.fields.school') }}</label><select name="school_id" class="form-control">@foreach($schools as $school)<option value="{{ $school->id }}" @selected(old('school_id', $student->school_id ?? '') == $school->id)>{{ $school->name }}</option>@endforeach</select></div>
    <div class="col-md-4 mb-3"><label>{{ __('app.fields.branch') }}</label><select name="branch_id" class="form-control">@foreach($branches as $branch)<option value="{{ $branch->id }}" @selected(old('branch_id', $student->branch_id ?? '') == $branch->id)>{{ $branch->name }}</option>@endforeach</select></div>
    <div class="col-md-4 mb-3"><label>{{ __('app.fields.student_no') }}</label><input name="student_no" class="form-control" value="{{ old('student_no', $student->student_no ?? '') }}" required></div>
    <div class="col-md-6 mb-3"><label>{{ __('app.fields.name') }}</label><input name="full_name" class="form-control" value="{{ old('full_name', $student->full_name ?? '') }}" required></div>
    <div class="col-md-3 mb-3"><label>{{ __('app.fields.gender') }}</label><input name="gender" class="form-control" value="{{ old('gender', $student->gender ?? '') }}"></div>
    <div class="col-md-3 mb-3"><label>{{ __('app.fields.blood_group') }}</label><input name="blood_group" class="form-control" value="{{ old('blood_group', $student->blood_group ?? '') }}"></div>
    <div class="col-md-3 mb-3"><label>{{ __('app.fields.date_of_birth') }}</label><input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth', isset($student) && $student->date_of_birth ? $student->date_of_birth->format('Y-m-d') : '') }}"></div>
    <div class="col-md-3 mb-3"><label>{{ __('app.fields.enrollment_date') }}</label><input type="date" name="enrollment_date" class="form-control" value="{{ old('enrollment_date', isset($student) && $student->enrollment_date ? $student->enrollment_date->format('Y-m-d') : '') }}"></div>
    <div class="col-md-4 mb-3"><label>{{ __('app.fields.status') }}</label><input name="status" class="form-control" value="{{ old('status', $student->status ?? 'active') }}" required></div>
    <div class="col-md-4 mb-3"><label>{{ __('app.fields.allergies') }}</label><textarea name="allergies" class="form-control">{{ old('allergies', $student->allergies ?? '') }}</textarea></div>
    <div class="col-md-4 mb-3"><label>{{ __('app.fields.medical_notes') }}</label><textarea name="medical_notes" class="form-control">{{ old('medical_notes', $student->medical_notes ?? '') }}</textarea></div>
</div>
<button class="btn btn-primary">{{ __('app.actions.save') }}</button>
