@extends('dashboard.layouts.master')
@section('title', __('app.resources.student'))
@section('page-header')
    @include('dashboard.partials.page-header', ['title' => __('app.actions.add').' '.__('app.resources.student')])
@endsection
@section('content')<div class="card resource-card"><div class="card-body"><form method="POST" action="{{ route('students.store') }}">@include('students._form')</form></div></div>@endsection
