@extends('dashboard.layouts.master')
@section('title', __('app.menu.attendance_reports'))
@section('page-header')
    @include('dashboard.partials.page-header', [
        'title' => __('app.menu.attendance_reports'),
        'actions' => '<a href="'.route('attendance-reports.print', request()->query()).'" class="btn btn-secondary btn-sm">'.(__('app.actions.print') ?? 'Print').'</a>',
    ])
@endsection
@section('content')
    <div class="row mb-30">
        @foreach ([
            ['label' => __('app.status.present'), 'value' => $summary['present'], 'color' => 'success'],
            ['label' => __('app.status.absent'), 'value' => $summary['absent'], 'color' => 'danger'],
            ['label' => __('app.status.late'), 'value' => $summary['late'], 'color' => 'warning'],
            ['label' => __('app.status.excused'), 'value' => $summary['excused'], 'color' => 'info'],
        ] as $card)
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card resource-card h-100">
                    <div class="card-body">
                        <p class="text-{{ $card['color'] }}">{{ $card['label'] }}</p>
                        <h3>{{ number_format($card['value']) }}</h3>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="card resource-card mb-30"><div class="card-body">
        <form method="GET" class="row">
            <div class="col-md-2 mb-2"><select name="school_id" class="form-control"><option value="">- {{ __('app.fields.school') }} -</option>@foreach($schools as $school)<option value="{{ $school->id }}" @selected(($filters['school_id'] ?? null) == $school->id)>{{ $school->name }}</option>@endforeach</select></div>
            <div class="col-md-2 mb-2"><select name="branch_id" class="form-control"><option value="">- {{ __('app.fields.branch') }} -</option>@foreach($branches as $branch)<option value="{{ $branch->id }}" @selected(($filters['branch_id'] ?? null) == $branch->id)>{{ $branch->name }}</option>@endforeach</select></div>
            <div class="col-md-2 mb-2"><select name="section_id" class="form-control"><option value="">- {{ __('app.fields.section') }} -</option>@foreach($sections as $section)<option value="{{ $section->id }}" @selected(($filters['section_id'] ?? null) == $section->id)>{{ $section->name }}</option>@endforeach</select></div>
            <div class="col-md-2 mb-2"><select name="student_id" class="form-control"><option value="">- {{ __('app.fields.student') }} -</option>@foreach($students as $student)<option value="{{ $student->id }}" @selected(($filters['student_id'] ?? null) == $student->id)>{{ $student->full_name }}</option>@endforeach</select></div>
            <div class="col-md-2 mb-2"><input type="date" name="from_date" class="form-control" value="{{ $filters['from_date'] ?? '' }}"></div>
            <div class="col-md-2 mb-2"><input type="date" name="to_date" class="form-control" value="{{ $filters['to_date'] ?? '' }}"></div>
            <div class="col-md-2 mb-2"><select name="status" class="form-control"><option value="">- {{ __('app.fields.status') }} -</option>@foreach(['present','absent','late','excused'] as $status)<option value="{{ $status }}" @selected(($filters['status'] ?? null) === $status)>{{ __('app.status.'.$status) }}</option>@endforeach</select></div>
            <div class="col-md-2 mb-2"><button class="btn btn-primary">{{ __('app.actions.filter') }}</button></div>
        </form>
    </div></div>
    <div class="card resource-card"><div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead><tr><th>{{ __('app.fields.attendance_date') }}</th><th>{{ __('app.fields.student_no') }}</th><th>{{ __('app.fields.name') }}</th><th>{{ __('app.fields.branch') }}</th><th>{{ __('app.fields.section') }}</th><th>{{ __('app.fields.status') }}</th><th>{{ __('app.fields.remarks') }}</th></tr></thead>
                <tbody>
                    @forelse($rows as $row)
                        <tr>
                            <td>{{ $row->session?->attendance_date?->format('Y-m-d') }}</td>
                            <td>{{ $row->student?->student_no }}</td>
                            <td>{{ $row->student?->full_name }}</td>
                            <td>{{ $row->session?->branch?->name }}</td>
                            <td>{{ $row->session?->section?->name }}</td>
                            <td>{{ __('app.status.'.$row->status) }}</td>
                            <td>{{ $row->remarks }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted">{{ __('app.messages.no_data') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $rows->links() }}
    </div></div>
@endsection
