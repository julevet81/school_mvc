@extends('dashboard.layouts.master')
@section('title', __('app.menu.fees'))
@section('page-header')
    @include('dashboard.partials.page-header', ['title' => __('app.menu.fees'), 'actions' => '<a href="'.route('fees.create').'" class="btn btn-primary btn-sm">'.__('app.actions.add').'</a>'])
@endsection
@section('content')
    <div class="card resource-card"><div class="card-body"><div class="table-responsive"><table class="table table-hover"><thead><tr><th>{{ __('app.fields.code') }}</th><th>{{ __('app.fields.name') }}</th><th>{{ __('app.fields.amount') }}</th><th>{{ __('app.fields.frequency') }}</th><th>{{ __('app.fields.actions') }}</th></tr></thead><tbody>@forelse($fees as $fee)<tr><td>{{ $fee->code }}</td><td>{{ $fee->name }}</td><td>{{ number_format($fee->amount, 2) }}</td><td>{{ $fee->frequency }}</td><td class="d-flex"><a href="{{ route('fees.edit', $fee) }}" class="btn btn-sm btn-info mr-2">{{ __('app.actions.edit') }}</a><form method="POST" action="{{ route('fees.destroy', $fee) }}" onsubmit="return confirm('{{ __('app.messages.confirm_delete') }}')">@csrf @method('DELETE')<button class="btn btn-sm btn-danger">{{ __('app.actions.delete') }}</button></form></td></tr>@empty<tr><td colspan="5" class="text-center text-muted">{{ __('app.messages.no_data') }}</td></tr>@endforelse</tbody></table></div>{{ $fees->links() }}</div></div>
@endsection
