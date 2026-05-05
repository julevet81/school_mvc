@extends('dashboard.layouts.master')
@section('title', $fee->name)
@section('page-header')
    @include('dashboard.partials.page-header', ['title' => $fee->name, 'subtitle' => __('app.resources.fee')])
@endsection
@section('content')<div class="card resource-card"><div class="card-body"><form method="POST" action="{{ route('fees.update', $fee) }}">@include('fees._form')</form></div></div>@endsection
