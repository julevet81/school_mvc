@extends('dashboard.layouts.master')
@section('title', __('app.resources.fee'))
@section('page-header')
    @include('dashboard.partials.page-header', ['title' => __('app.actions.add').' '.__('app.resources.fee')])
@endsection
@section('content')<div class="card resource-card"><div class="card-body"><form method="POST" action="{{ route('fees.store') }}">@include('fees._form')</form></div></div>@endsection
