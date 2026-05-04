@extends('layouts.app')
@section('title', __('schools.create'))
@section('content')
    <div class="container mx-auto px-4 py-6 max-w-3xl">
        <div class="mb-4">
            <a href="{{ route('schools.index') }}" class="text-sm text-gray-500 hover:text-gray-700">←
                {{ __('schools.back_to_list') }}</a>
        </div>
        <div class="bg-white rounded-xl shadow p-6">
            <h1 class="text-xl font-bold text-gray-800 mb-6">{{ __('schools.create') }}</h1>
            <form method="POST" action="{{ route('schools.store') }}" novalidate>
                @csrf
                @include('schools._form', ['timezones' => $timezones])
                <div class="mt-6 flex gap-3">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg text-sm transition">
                        {{ __('general.save') }}
                    </button>
                    <a href="{{ route('schools.index') }}"
                        class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-5 py-2 rounded-lg text-sm transition">
                        {{ __('general.cancel') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
