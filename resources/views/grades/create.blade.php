@extends('dashboard.layouts.master')
@section('title', __('app.resources.grade'))
@section('page-header')
    @include('dashboard.partials.page-header', ['title' => __('app.actions.add').' '.__('app.resources.grade')])
@endsection
@section('content')<div class="card resource-card"><div class="card-body"><form method="POST" action="{{ route('grades.store') }}">@include('grades._form')</form></div></div>@endsection
