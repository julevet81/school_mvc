@extends('layouts.app')
@section('title', __('branches.edit'))
@section('content')
    <div class="container mx-auto px-4 py-6 max-w-3xl">
        <div class="mb-4">
            <a href="{{ route('schools.branches.show', [$school, $branch]) }}"
                class="text-sm text-gray-500 hover:text-gray-700">← {{ $branch->name }}</a>
        </div>
        <div class="bg-white rounded-xl shadow p-6">
            <h1 class="text-xl font-bold text-gray-800 mb-6">{{ __('branches.edit') }}</h1>
            <form method="POST" action="{{ route('schools.branches.update', [$school, $branch]) }}" novalidate>
                @csrf @method('PUT')
                @include('branches._form', ['branch' => $branch])
                <div class="mt-6 flex gap-3">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg text-sm transition">
                        {{ __('general.update') }}
                    </button>
                    <a href="{{ route('schools.branches.show', [$school, $branch]) }}"
                        class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-5 py-2 rounded-lg text-sm transition">
                        {{ __('general.cancel') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection