@extends('dashboard.layouts.master')
@section('title', __('app.resources.attendance'))
@section('page-header')
    @include('dashboard.partials.page-header', ['title' => __('app.actions.add').' '.__('app.resources.attendance')])
@endsection
@section('content')
    <div class="card resource-card"><div class="card-body">
        <form method="POST" action="{{ route('attendances.store') }}">
            @include('attendances._form')
        </form>
    </div></div>
@endsection
