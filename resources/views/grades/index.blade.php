@extends('dashboard.layouts.master')
@section('title', __('app.menu.grades'))
@section('page-header')
@include('dashboard.partials.page-header', ['title' => __('app.menu.grades'), 'actions' => '<a
    href="'.route('grades.create').'" class="btn btn-primary btn-sm">'.__('app.actions.add').'</a>'])
@endsection
@section('content')
<div class="card resource-card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>{{ __('app.fields.name') }}</th>
                        <th>{{ __('app.fields.level') }}</th>
                        <th>{{ __('app.fields.school') }}</th>
                        <th>{{ __('app.fields.branch') }}</th>
                        <th>{{ __('app.fields.actions') }}</th>
                    </tr>
                </thead>
                <tbody>@forelse($grades as $grade)<tr>
                        <td>{{ $grade->name }}</td>
                        <td>{{ $grade->level }}</td>
                        <td>{{ $grade->school?->name }}</td>
                        <td>{{ $grade->branch?->name }}</td>
                        <td class="d-flex"><a href="{{ route('grades.edit', $grade) }}"
                                class="btn btn-sm btn-info mr-2">{{ __('app.actions.edit') }}</a>
                            <form method="POST" action="{{ route('grades.destroy', $grade) }}"
                                onsubmit="return confirm('{{ __('app.messages.confirm_delete') }}')">
                                @csrf
                                @method('DELETE')<button
                                    class="btn btn-sm btn-danger">{{ __('app.actions.delete') }}</button></form>
                        </td>
                    </tr>@empty<tr>
                        <td colspan="5" class="text-center text-muted">{{ __('app.messages.no_data') }}</td>
                    </tr>@endforelse</tbody>
            </table>
        </div>{{ $grades->links() }}
    </div>
</div>
@endsection