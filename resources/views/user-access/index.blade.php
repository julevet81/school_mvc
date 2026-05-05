@extends('dashboard.layouts.master')
@section('title', __('app.menu.users'))
@section('page-header')
    @include('dashboard.partials.page-header', ['title' => __('app.menu.users'), 'subtitle' => __('app.messages.permission_hint')])
@endsection
@section('content')
    <div class="card resource-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>{{ __('app.fields.name') }}</th>
                            <th>{{ __('app.fields.email') }}</th>
                            <th>{{ __('app.fields.roles') }}</th>
                            <th>{{ __('app.fields.direct_permissions') }}</th>
                            <th>{{ __('app.fields.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->roles->pluck('name')->join(', ') ?: '-' }}</td>
                                <td>{{ $user->permissions->pluck('name')->join(', ') ?: '-' }}</td>
                                <td><a href="{{ route('user-access.edit', $user) }}" class="btn btn-sm btn-info">{{ __('app.actions.manage_access') }}</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted">{{ __('app.messages.no_data') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $users->links() }}
        </div>
    </div>
@endsection
