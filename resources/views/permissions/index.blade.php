@extends('dashboard.layouts.master')
@section('title', __('app.menu.permissions'))
@section('page-header')
    @include('dashboard.partials.page-header', ['title' => __('app.menu.permissions'), 'actions' => '<a href="'.route('permissions.create').'" class="btn btn-primary btn-sm">'.__('app.actions.add').'</a>'])
@endsection
@section('content')
    <div class="card resource-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>{{ __('app.fields.name') }}</th>
                            <th>{{ __('app.fields.roles') }}</th>
                            <th>{{ __('app.fields.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($permissions as $permission)
                            <tr>
                                <td>{{ $permission->name }}</td>
                                <td>{{ $permission->roles_count }}</td>
                                <td class="d-flex">
                                    <a href="{{ route('permissions.edit', $permission) }}" class="btn btn-sm btn-info mr-2">{{ __('app.actions.edit') }}</a>
                                    <form method="POST" action="{{ route('permissions.destroy', $permission) }}" onsubmit="return confirm('{{ __('app.messages.confirm_delete') }}')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger">{{ __('app.actions.delete') }}</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted">{{ __('app.messages.no_data') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $permissions->links() }}
        </div>
    </div>
@endsection
