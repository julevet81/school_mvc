@extends('dashboard.layouts.master')
@section('title', __('app.resources.classroom'))
@section('page-header')
    @include('dashboard.partials.page-header', ['title' => __('app.actions.add').' '.__('app.resources.classroom')])
@endsection
@section('content')<div class="card resource-card"><div class="card-body"><form method="POST" action="{{ route('classrooms.store') }}">@include('classrooms._form')</form></div></div>@endsection
