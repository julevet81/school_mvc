@extends('dashboard.layouts.master')
@section('title', $student->full_name)
@section('page-header')
    @include('dashboard.partials.page-header', ['title' => $student->full_name, 'subtitle' => __('app.resources.student')])
@endsection
@section('content')<div class="card resource-card"><div class="card-body"><form method="POST" action="{{ route('students.update', $student) }}">@include('students._form')</form></div></div>@endsection
