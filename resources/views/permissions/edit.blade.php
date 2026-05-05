@extends('dashboard.layouts.master')
@section('title', $permission->name)
@section('page-header')
    @include('dashboard.partials.page-header', ['title' => $permission->name, 'subtitle' => __('app.resources.permission')])
@endsection
@section('content')
    <div class="card resource-card"><div class="card-body">
        <form method="POST" action="{{ route('permissions.update', $permission) }}">
            @include('permissions._form')
        </form>
    </div></div>
@endsection
