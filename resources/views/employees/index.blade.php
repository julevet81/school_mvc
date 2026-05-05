@extends('dashboard.layouts.master')
@section('title', __('app.menu.employees'))
@section('page-header')
    @include('dashboard.partials.page-header', ['title' => __('app.menu.employees'), 'actions' => '<a href="'.route('employees.create').'" class="btn btn-primary btn-sm">'.__('app.actions.add').'</a>'])
@endsection
@section('content')
    <div class="card resource-card"><div class="card-body"><div class="table-responsive"><table class="table table-hover"><thead><tr><th>{{ __('app.fields.employee_no') }}</th><th>{{ __('app.fields.job_title') }}</th><th>{{ __('app.fields.branch') }}</th><th>{{ __('app.fields.status') }}</th><th>{{ __('app.fields.actions') }}</th></tr></thead><tbody>@forelse($employees as $employee)<tr><td>{{ $employee->employee_no }}</td><td>{{ $employee->job_title }}</td><td>{{ $employee->branch?->name }}</td><td><span class="badge badge-pill badge-soft">{{ $employee->status }}</span></td><td class="d-flex"><a href="{{ route('employees.edit', $employee) }}" class="btn btn-sm btn-info mr-2">{{ __('app.actions.edit') }}</a><form method="POST" action="{{ route('employees.destroy', $employee) }}" onsubmit="return confirm('{{ __('app.messages.confirm_delete') }}')">@csrf @method('DELETE')<button class="btn btn-sm btn-danger">{{ __('app.actions.delete') }}</button></form></td></tr>@empty<tr><td colspan="5" class="text-center text-muted">{{ __('app.messages.no_data') }}</td></tr>@endforelse</tbody></table></div>{{ $employees->links() }}</div></div>
@endsection
