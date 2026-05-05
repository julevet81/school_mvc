@extends('dashboard.layouts.master')
@section('title', __('app.resources.branch'))
@section('page-header')
    @include('dashboard.partials.page-header', ['title' => __('app.actions.add').' '.__('app.resources.branch')])
@endsection
@section('content')
    <div class="card resource-card"><div class="card-body">
        <form method="POST" action="{{ route('schools.branches.store', $school) }}">
            @include('branches._form')
        </form>
    </div></div>
@endsection
