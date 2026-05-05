@extends('dashboard.layouts.master')
@section('title', __('app.resources.permission'))
@section('page-header')
    @include('dashboard.partials.page-header', ['title' => __('app.actions.add').' '.__('app.resources.permission')])
@endsection
@section('content')
    <div class="card resource-card"><div class="card-body">
        <form method="POST" action="{{ route('permissions.store') }}">
            @include('permissions._form')
        </form>
    </div></div>
@endsection
