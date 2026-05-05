@extends('dashboard.layouts.master')
@section('title', __('app.resources.role'))
@section('page-header')
    @include('dashboard.partials.page-header', ['title' => __('app.actions.add').' '.__('app.resources.role')])
@endsection
@section('content')
    <div class="card resource-card"><div class="card-body">
        <form method="POST" action="{{ route('roles.store') }}">
            @include('roles._form')
        </form>
    </div></div>
@endsection
