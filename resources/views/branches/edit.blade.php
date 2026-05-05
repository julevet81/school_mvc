@extends('dashboard.layouts.master')
@section('title', $branch->name)
@section('page-header')
    @include('dashboard.partials.page-header', ['title' => $branch->name, 'subtitle' => $school->name])
@endsection
@section('content')
    <div class="card resource-card"><div class="card-body">
        <form method="POST" action="{{ route('schools.branches.update', [$school, $branch]) }}">
            @include('branches._form')
        </form>
    </div></div>
@endsection
