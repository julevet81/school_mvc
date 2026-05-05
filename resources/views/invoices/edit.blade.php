@extends('dashboard.layouts.master')
@section('title', $invoice->invoice_no)
@section('page-header')
    @include('dashboard.partials.page-header', ['title' => $invoice->invoice_no, 'subtitle' => __('app.resources.invoice').' - '.__('app.fields.total').': '.number_format($invoice->total, 2)])
@endsection
@section('content')<div class="card resource-card"><div class="card-body"><form method="POST" action="{{ route('invoices.update', $invoice) }}">@include('invoices._form')</form></div></div>@endsection
