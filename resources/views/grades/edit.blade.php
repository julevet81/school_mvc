@extends('dashboard.layouts.master')
@section('title', $grade->name)
@section('page-header')
    @include('dashboard.partials.page-header', ['title' => $grade->name, 'subtitle' => __('app.resources.grade')])
@endsection
@section('content')<div class="card resource-card"><div class="card-body"><form method="POST" action="{{ route('grades.update', $grade) }}">@include('grades._form')</form></div></div>@endsection
