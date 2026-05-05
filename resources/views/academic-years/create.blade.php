@extends('dashboard.layouts.master')
@section('title', __('app.resources.academic_year'))
@section('page-header')
    @include('dashboard.partials.page-header', ['title' => __('app.actions.add').' '.__('app.resources.academic_year')])
@endsection
@section('content')<div class="card resource-card"><div class="card-body"><form method="POST" action="{{ route('academic-years.store') }}">@include('academic-years._form')</form></div></div>@endsection
