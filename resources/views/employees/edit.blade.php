@extends('dashboard.layouts.master')
@section('title', $employee->employee_no)
@section('page-header')
    @include('dashboard.partials.page-header', ['title' => $employee->employee_no, 'subtitle' => __('app.resources.employee')])
@endsection
@section('content')<div class="card resource-card"><div class="card-body"><form method="POST" action="{{ route('employees.update', $employee) }}">@include('employees._form')</form></div></div>@endsection
