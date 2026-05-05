@extends('dashboard.layouts.master')
@section('title', __('app.menu.attendance_register'))
@section('page-header')
    @include('dashboard.partials.page-header', ['title' => __('app.menu.attendance_register'), 'actions' => '<a href="'.route('attendances.create').'" class="btn btn-primary btn-sm">'.__('app.actions.add').'</a>'])
@endsection
@section('content')
    <div class="card resource-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead><tr><th>{{ __('app.fields.attendance_date') }}</th><th>{{ __('app.fields.school') }}</th><th>{{ __('app.fields.branch') }}</th><th>{{ __('app.fields.section') }}</th><th>{{ __('app.status.present') }}</th><th>{{ __('app.status.absent') }}</th><th>{{ __('app.fields.actions') }}</th></tr></thead>
                    <tbody>
                        @forelse($sessions as $session)
                            <tr>
                                <td>{{ $session->attendance_date?->format('Y-m-d') }}</td>
                                <td>{{ $session->school?->name }}</td>
                                <td>{{ $session->branch?->name }}</td>
                                <td>{{ $session->section?->classroom?->name }} / {{ $session->section?->name }}</td>
                                <td>{{ $session->present_count }}</td>
                                <td>{{ $session->absent_count }}</td>
                                <td class="d-flex">
                                    <a href="{{ route('attendances.edit', $session) }}" class="btn btn-sm btn-info mr-2">{{ __('app.actions.edit') }}</a>
                                    <a href="{{ route('attendances.print', $session) }}" class="btn btn-sm btn-secondary">{{ __('app.actions.print') ?? 'Print' }}</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center text-muted">{{ __('app.messages.no_data') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $sessions->links() }}
        </div>
    </div>
@endsection
