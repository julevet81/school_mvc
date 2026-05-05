@extends('dashboard.layouts.master')
@section('title', $role->name)
@section('page-header')
    @include('dashboard.partials.page-header', ['title' => $role->name, 'subtitle' => __('app.resources.role')])
@endsection
@section('content')
    <div class="card resource-card"><div class="card-body">
        <form method="POST" action="{{ route('roles.update', $role) }}">
            @include('roles._form')
        </form>
    </div></div>
@endsection
