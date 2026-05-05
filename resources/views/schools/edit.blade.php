@extends('dashboard.layouts.master')
@section('title', $school->name)
@section('page-header')
    @include('dashboard.partials.page-header', ['title' => $school->name, 'subtitle' => __('app.resources.school')])
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-8 mb-30">
            <div class="card resource-card"><div class="card-body">
                <form method="POST" action="{{ route('schools.update', $school) }}">
                    @include('schools._form')
                </form>
            </div></div>
        </div>
        <div class="col-xl-4 mb-30">
            <div class="card resource-card"><div class="card-body">
                <h5 class="card-title">{{ __('app.dashboard.quick_actions') }}</h5>
                <p>{{ __('app.menu.branches') }}: {{ $school->branches_count }}</p>
                <a href="{{ route('schools.branches.index', $school) }}" class="btn btn-secondary btn-block">{{ __('app.actions.manage_branches') }}</a>
            </div></div>
        </div>
    </div>
@endsection
