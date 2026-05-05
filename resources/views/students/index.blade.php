@extends('dashboard.layouts.master')
@section('title', __('app.menu.students'))
@section('page-header')
    @include('dashboard.partials.page-header', ['title' => __('app.menu.students'), 'actions' => '<a href="'.route('students.create').'" class="btn btn-primary btn-sm">'.__('app.actions.add').'</a>'])
@endsection
@section('content')
    <div class="card resource-card"><div class="card-body"><div class="table-responsive"><table class="table table-hover"><thead><tr><th>{{ __('app.fields.student_no') }}</th><th>{{ __('app.fields.name') }}</th><th>{{ __('app.fields.school') }}</th><th>{{ __('app.fields.branch') }}</th><th>{{ __('app.fields.status') }}</th><th>{{ __('app.fields.actions') }}</th></tr></thead><tbody>@forelse($students as $student)<tr><td>{{ $student->student_no }}</td><td>{{ $student->full_name }}</td><td>{{ $student->school?->name }}</td><td>{{ $student->branch?->name }}</td><td><span class="badge badge-pill badge-soft">{{ $student->status }}</span></td><td class="d-flex"><a href="{{ route('students.edit', $student) }}" class="btn btn-sm btn-info mr-2">{{ __('app.actions.edit') }}</a><form method="POST" action="{{ route('students.destroy', $student) }}" onsubmit="return confirm('{{ __('app.messages.confirm_delete') }}')">@csrf @method('DELETE')<button class="btn btn-sm btn-danger">{{ __('app.actions.delete') }}</button></form></td></tr>@empty<tr><td colspan="6" class="text-center text-muted">{{ __('app.messages.no_data') }}</td></tr>@endforelse</tbody></table></div>{{ $students->links() }}</div></div>
@endsection
