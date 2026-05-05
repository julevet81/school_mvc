@extends('dashboard.layouts.master')
@section('title', __('app.menu.invoices'))
@section('page-header')
@include('dashboard.partials.page-header', ['title' => __('app.menu.invoices'), 'actions' => '<a
    href="'.route('invoices.create').'" class="btn btn-primary btn-sm">'.__('app.actions.add').'</a>'])
@endsection
@section('content')
<div class="card resource-card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>{{ __('app.fields.invoice_no') }}</th>
                        <th>{{ __('app.fields.student') }}</th>
                        <th>{{ __('app.fields.total') }}</th>
                        <th>{{ __('app.fields.paid_amount') }}</th>
                        <th>{{ __('app.fields.status') }}</th>
                        <th>{{ __('app.fields.actions') }}</th>
                    </tr>
                </thead>
                <tbody>@forelse($invoices as $invoice)<tr>
                        <td>{{ $invoice->invoice_no }}</td>
                        <td>{{ $invoice->student?->full_name }}</td>
                        <td>{{ number_format($invoice->total, 2) }}</td>
                        <td>{{ number_format($invoice->paid_amount, 2) }}</td>
                        <td><span class="badge badge-pill badge-soft">{{ $invoice->status }}</span></td>
                        <td class="d-flex"><a href="{{ route('invoices.edit', $invoice) }}"
                                class="btn btn-sm btn-info mr-2">{{ __('app.actions.edit') }}</a>
                            <form method="POST" action="{{ route('invoices.destroy', $invoice) }}"
                                onsubmit="return confirm('{{ __('app.messages.confirm_delete') }}')">@csrf
                                @method('DELETE')<button
                                    class="btn btn-sm btn-danger">{{ __('app.actions.delete') }}</button></form>
                        </td>
                    </tr>@empty<tr>
                        <td colspan="6" class="text-center text-muted">{{ __('app.messages.no_data') }}</td>
                    </tr>@endforelse</tbody>
            </table>
        </div>{{ $invoices->links() }}
    </div>
</div>
@endsection