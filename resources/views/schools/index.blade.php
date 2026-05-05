@extends('dashboard.layouts.master')

@section('title', __('app.menu.schools'))

@section('page-header')
@include('dashboard.partials.page-header', [
'title' => __('app.menu.schools'),
'subtitle' => __('app.dashboard.manage_resources'),
'actions' => '<a href="'.route('schools.create').'" class="btn btn-primary btn-sm">'.__('app.actions.add').'</a>',
])
@endsection

@section('content')
<div class="card resource-card mb-30">
    <div class="card-body">
        <form method="GET" class="row">
            <div class="col-md-5 mb-2"><input type="text" name="search" class="form-control"
                    value="{{ request('search') }}" placeholder="{{ __('app.actions.search') }}"></div>
            <div class="col-md-3 mb-2">
                <select name="status" class="form-control">
                    <option value="">{{ __('app.fields.all_statuses') }}</option>
                    <option value="active" @selected(request('status')==='active' )>{{ __('app.status.active') }}
                    </option>
                    <option value="inactive" @selected(request('status')==='inactive' )>{{ __('app.status.inactive') }}
                    </option>
                </select>
            </div>
            <div class="col-md-4 mb-2">
                <button class="btn btn-secondary">{{ __('app.actions.filter') }}</button>
            </div>
        </form>
    </div>
</div>
<div class="card resource-card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>{{ __('app.fields.name') }}</th>
                        <th>{{ __('app.fields.code') }}</th>
                        <th>{{ __('app.menu.branches') }}</th>
                        <th>{{ __('app.fields.country') }}</th>
                        <th>{{ __('app.fields.status') }}</th>
                        <th>{{ __('app.fields.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($schools as $school)
                    <tr>
                        <td>{{ $school->name }}</td>
                        <td>{{ $school->code }}</td>
                        <td>{{ $school->branches_count }}</td>
                        <td>{{ $school->country }}</td>
                        <td><span
                                class="badge badge-pill badge-soft">{{ $school->is_active ? __('app.status.active') : __('app.status.inactive') }}</span>
                        </td>
                        <td class="d-flex">
                            <a href="{{ route('schools.edit', $school) }}"
                                class="btn btn-sm btn-info mr-2">{{ __('app.actions.edit') }}</a>
                            <a href="{{ route('schools.branches.index', $school) }}"
                                class="btn btn-sm btn-secondary mr-2">{{ __('app.menu.branches') }}</a>
                            <form method="POST" action="{{ route('schools.destroy', $school) }}"
                                onsubmit="return confirm('{{ __('app.messages.confirm_delete') }}')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">{{ __('app.actions.delete') }}</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">{{ __('app.messages.no_data') }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $schools->links() }}
    </div>
</div>
@endsection