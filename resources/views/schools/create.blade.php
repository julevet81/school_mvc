@extends('dashboard.layouts.master')
@section('title', __('app.resources.school'))
@section('page-header')
    @include('dashboard.partials.page-header', ['title' => __('app.actions.add').' '.__('app.resources.school')])
@endsection
@section('content')
    <div class="card resource-card"><div class="card-body">
        <form method="POST" action="{{ route('schools.store') }}">
            @include('schools._form')
        </form>
    </div></div>
@endsection
