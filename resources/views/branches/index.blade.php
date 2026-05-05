@extends('dashboard.layouts.master')
@section('title', __('app.menu.branches'))
@section('page-header')
    @include('dashboard.partials.page-header', [
        'title' => __('app.menu.branches').' - '.$school->name,
        'actions' => '<a href="'.route('schools.branches.create', $school).'" class="btn btn-primary btn-sm">'.__('app.actions.add').'</a>',
    ])
@endsection
@section('content')
    <div class="card resource-card"><div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead><tr><th>{{ __('app.fields.name') }}</th><th>{{ __('app.fields.code') }}</th><th>{{ __('app.fields.email') }}</th><th>{{ __('app.fields.status') }}</th><th>{{ __('app.fields.actions') }}</th></tr></thead>
                <tbody>
                    @forelse($branches as $branch)
                        <tr>
                            <td>{{ $branch->name }}</td>
                            <td>{{ $branch->code }}</td>
                            <td>{{ $branch->email }}</td>
                            <td><span class="badge badge-pill badge-soft">{{ $branch->is_active ? __('app.status.active') : __('app.status.inactive') }}</span></td>
                            <td class="d-flex">
                                <a href="{{ route('schools.branches.edit', [$school, $branch]) }}" class="btn btn-sm btn-info mr-2">{{ __('app.actions.edit') }}</a>
                                <form method="POST" action="{{ route('schools.branches.destroy', [$school, $branch]) }}" onsubmit="return confirm('{{ __('app.messages.confirm_delete') }}')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger">{{ __('app.actions.delete') }}</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted">{{ __('app.messages.no_data') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $branches->links() }}
    </div></div>
@endsection
