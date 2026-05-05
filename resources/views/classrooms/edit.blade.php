@extends('dashboard.layouts.master')
@section('title', $classroom->name)
@section('page-header')
    @include('dashboard.partials.page-header', ['title' => $classroom->name, 'subtitle' => __('app.resources.classroom')])
@endsection
@section('content')<div class="card resource-card"><div class="card-body"><form method="POST" action="{{ route('classrooms.update', $classroom) }}">@include('classrooms._form')</form></div></div>@endsection
