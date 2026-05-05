@extends('dashboard.layouts.master')
@section('title', __('app.resources.invoice'))
@section('page-header')
    @include('dashboard.partials.page-header', ['title' => __('app.actions.add').' '.__('app.resources.invoice')])
@endsection
@section('content')<div class="card resource-card"><div class="card-body"><form method="POST" action="{{ route('invoices.store') }}">@include('invoices._form')</form></div></div>@endsection
