@extends('dashboard.layouts.master')
@section('title', $academicYear->name)
@section('page-header')
@include('dashboard.partials.page-header', ['title' => $academicYear->name, 'subtitle' =>
__('app.resources.academic_year')])
@endsection
@section('content')
<div class="card resource-card">
    <div class="card-body">
        <form method="POST" action="{{ route('academic-years.update', $academicYear) }}">
            @include('academic-years._form')</form>
    </div>
</div>@endsection