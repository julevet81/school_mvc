@extends('dashboard.layouts.master')
@section('title', __('app.menu.academic_years'))
@section('page-header')
    @include('dashboard.partials.page-header', ['title' => __('app.menu.academic_years'), 'actions' => '<a href="'.route('academic-years.create').'" class="btn btn-primary btn-sm">'.__('app.actions.add').'</a>'])
@endsection
@section('content')
    <div class="card resource-card"><div class="card-body">
        <div class="table-responsive"><table class="table table-hover"><thead><tr><th>{{ __('app.fields.name') }}</th><th>{{ __('app.fields.school') }}</th><th>{{ __('app.fields.branch') }}</th><th>{{ __('app.fields.period') }}</th><th>{{ __('app.fields.status') }}</th><th>{{ __('app.fields.actions') }}</th></tr></thead><tbody>
            @forelse($academicYears as $academicYear)
                <tr><td>{{ $academicYear->name }}</td><td>{{ $academicYear->school?->name }}</td><td>{{ $academicYear->branch?->name }}</td><td>{{ $academicYear->start_date->format('Y-m-d') }} - {{ $academicYear->end_date->format('Y-m-d') }}</td><td><span class="badge badge-pill badge-soft">{{ $academicYear->is_current ? __('app.status.current') : __('app.status.archived') }}</span></td><td class="d-flex"><a href="{{ route('academic-years.edit', $academicYear) }}" class="btn btn-sm btn-info mr-2">{{ __('app.actions.edit') }}</a><form method="POST" action="{{ route('academic-years.destroy', $academicYear) }}" onsubmit="return confirm('{{ __('app.messages.confirm_delete') }}')">@csrf @method('DELETE')<button class="btn btn-sm btn-danger">{{ __('app.actions.delete') }}</button></form></td></tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted">{{ __('app.messages.no_data') }}</td></tr>
            @endforelse
        </tbody></table></div>{{ $academicYears->links() }}
    </div></div>
@endsection
