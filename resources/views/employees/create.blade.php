@extends('dashboard.layouts.master')
@section('title', __('app.resources.employee'))
@section('page-header')
    @include('dashboard.partials.page-header', ['title' => __('app.actions.add').' '.__('app.resources.employee')])
@endsection
@section('content')<div class="card resource-card"><div class="card-body"><form method="POST" action="{{ route('employees.store') }}">@include('employees._form')</form></div></div>@endsection
