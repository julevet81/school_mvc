@extends('dashboard.layouts.master')
@section('title', __('app.menu.attendance_register'))
@section('page-header')
    @include('dashboard.partials.page-header', [
        'title' => __('app.menu.attendance_register').' - '.$attendanceSession->attendance_date?->format('Y-m-d'),
        'actions' => '<a href="'.route('attendances.print', $attendanceSession).'" class="btn btn-secondary btn-sm mr-2">'.(__('app.actions.print') ?? 'Print').'</a>'.
                     '<form method="POST" action="'.route('attendances.notify-absences', $attendanceSession).'" style="display:inline-block;">'.csrf_field().'<button class="btn btn-warning btn-sm">'.(__('app.actions.send_absence_notifications') ?? 'Send absence notifications').'</button></form>',
    ])
@endsection
@section('content')
    <div class="card resource-card mb-30"><div class="card-body">
        <form method="POST" action="{{ route('attendances.update', $attendanceSession) }}">
            @include('attendances._form')
        </form>
    </div></div>

    <div class="card resource-card">
        <div class="card-body">
            <h5 class="card-title">{{ __('app.menu.attendance_reports') }}</h5>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead><tr><th>{{ __('app.fields.name') }}</th><th>{{ __('app.fields.recipient') }}</th><th>{{ __('app.fields.notification_status') }}</th><th>{{ __('app.fields.sent_at') }}</th><th>{{ __('app.fields.remarks') }}</th></tr></thead>
                    <tbody>
                        @forelse($attendanceSession->absenceNotificationLogs as $log)
                            <tr>
                                <td>{{ $log->parent?->full_name ?? '-' }}</td>
                                <td>{{ $log->recipient }}</td>
                                <td><span class="badge badge-pill badge-soft">{{ __('app.status.'.$log->status) }}</span></td>
                                <td>{{ optional($log->sent_at)->format('Y-m-d H:i') }}</td>
                                <td>{{ $log->error_message }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted">{{ __('app.messages.no_data') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
