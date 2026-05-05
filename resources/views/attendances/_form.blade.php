@php
    $currentStudents = old('attendances');
    if (! isset($currentStudents) && isset($attendanceSession)) {
        $currentStudents = $attendanceSession->studentAttendances
            ->map(fn ($row) => ['student_id' => $row->student_id, 'status' => $row->status, 'remarks' => $row->remarks])
            ->values()
            ->all();
    }
@endphp
@csrf
@if(isset($attendanceSession)) @method('PUT') @endif
<div class="row">
    <div class="col-md-4 mb-3"><label>{{ __('app.fields.school') }}</label><select name="school_id" class="form-control">@foreach($schools as $school)<option value="{{ $school->id }}" @selected(old('school_id', $attendanceSession->school_id ?? '') == $school->id)>{{ $school->name }}</option>@endforeach</select></div>
    <div class="col-md-4 mb-3"><label>{{ __('app.fields.branch') }}</label><select name="branch_id" class="form-control">@foreach($branches as $branch)<option value="{{ $branch->id }}" @selected(old('branch_id', $attendanceSession->branch_id ?? '') == $branch->id)>{{ $branch->name }}</option>@endforeach</select></div>
    <div class="col-md-4 mb-3"><label>{{ __('app.menu.academic_years') }}</label><select name="academic_year_id" class="form-control"><option value="">-</option>@foreach($academicYears as $year)<option value="{{ $year->id }}" @selected(old('academic_year_id', $attendanceSession->academic_year_id ?? '') == $year->id)>{{ $year->name }}</option>@endforeach</select></div>
    <div class="col-md-4 mb-3"><label>{{ __('app.fields.section') }}</label><select name="section_id" class="form-control">@foreach($sections as $section)<option value="{{ $section->id }}" @selected(old('section_id', $attendanceSession->section_id ?? $selectedSectionId ?? request('section_id')) == $section->id)>{{ $section->classroom?->name }} / {{ $section->name }}</option>@endforeach</select></div>
    <div class="col-md-4 mb-3"><label>{{ __('app.fields.attendance_date') }}</label><input type="date" name="attendance_date" class="form-control" value="{{ old('attendance_date', isset($attendanceSession) ? $attendanceSession->attendance_date?->format('Y-m-d') : now()->format('Y-m-d')) }}"></div>
    <div class="col-md-2 mb-3"><label>{{ __('app.fields.type') }}</label><input type="text" name="type" class="form-control" value="{{ old('type', $attendanceSession->type ?? 'daily') }}"></div>
    <div class="col-md-2 mb-3"><label>{{ __('app.fields.method') }}</label><input type="text" name="method" class="form-control" value="{{ old('method', $attendanceSession->method ?? 'manual') }}"></div>
</div>

<div class="table-responsive mt-3">
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>{{ __('app.fields.student_no') }}</th>
                <th>{{ __('app.fields.name') }}</th>
                <th>{{ __('app.fields.status') }}</th>
                <th>{{ __('app.fields.remarks') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $index => $student)
                @php
                    $row = collect($currentStudents)->firstWhere('student_id', $student->id);
                @endphp
                <tr>
                    <td>{{ $student->student_no }}</td>
                    <td>{{ $student->full_name }}</td>
                    <td>
                        <input type="hidden" name="attendances[{{ $index }}][student_id]" value="{{ $student->id }}">
                        <select name="attendances[{{ $index }}][status]" class="form-control">
                            @foreach(['present', 'absent', 'late', 'excused'] as $status)
                                <option value="{{ $status }}" @selected(($row['status'] ?? 'present') === $status)>{{ __('app.status.'.$status) }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td><input type="text" name="attendances[{{ $index }}][remarks]" class="form-control" value="{{ $row['remarks'] ?? '' }}"></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<button class="btn btn-primary mt-3">{{ __('app.actions.save') }}</button>
