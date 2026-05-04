@extends('layouts.app')
@section('title', $school->name)
@section('content')
    <div class="container mx-auto px-4 py-6">

        <div class="mb-4 flex items-center justify-between">
            <a href="{{ route('schools.index') }}" class="text-sm text-gray-500 hover:text-gray-700">←
                {{ __('schools.back_to_list') }}</a>
            <div class="flex gap-2">
                @can('update', $school)
                    <a href="{{ route('schools.edit', $school) }}"
                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm">
                        {{ __('general.edit') }}
                    </a>
                @endcan
                @can('delete', $school)
                    <form method="POST" action="{{ route('schools.destroy', $school) }}"
                        onsubmit="return confirm('{{ __('schools.confirm_delete') }}')">
                        @csrf @method('DELETE')
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm">
                            {{ __('general.delete') }}
                        </button>
                    </form>
                @endcan
            </div>
        </div>

        @include('partials.flash')

        {{-- School Details --}}
        <div class="bg-white rounded-xl shadow p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <h1 class="text-2xl font-bold text-gray-800">{{ $school->name }}</h1>
                @if ($school->is_active)
                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">{{ __('general.active') }}</span>
                @else
                    <span class="bg-red-100 text-red-600 px-3 py-1 rounded-full text-sm">{{ __('general.inactive') }}</span>
                @endif
            </div>
            <dl class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                <div>
                    <dt class="text-gray-500">{{ __('schools.code') }}</dt>
                    <dd class="font-mono font-medium">{{ $school->code }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">{{ __('schools.legal_name') }}</dt>
                    <dd>{{ $school->legal_name ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">{{ __('schools.email') }}</dt>
                    <dd>{{ $school->email ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">{{ __('schools.phone') }}</dt>
                    <dd>{{ $school->phone ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">{{ __('schools.country') }}</dt>
                    <dd>{{ $school->country }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">{{ __('schools.currency') }}</dt>
                    <dd>{{ $school->currency }}</dd>
                </div>
                <div class="col-span-2">
                    <dt class="text-gray-500">{{ __('schools.timezone') }}</dt>
                    <dd>{{ $school->timezone }}</dd>
                </div>
            </dl>
        </div>

        {{-- Branches List --}}
        <div class="bg-white rounded-xl shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-800">{{ __('branches.title') }}</h2>
                @can('create', [\App\Models\Branch::class, $school])
                    <a href="{{ route('schools.branches.create', $school) }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
                        + {{ __('branches.add_new') }}
                    </a>
                @endcan
            </div>

            @forelse ($school->branches as $branch)
                <div class="flex items-center justify-between py-3 border-b last:border-0">
                    <div>
                        <span class="font-medium text-gray-800">{{ $branch->name }}</span>
                        <span class="text-xs text-gray-400 ml-2 font-mono">{{ $branch->code }}</span>
                        @if ($branch->is_main)
                            <span
                                class="ml-2 bg-blue-100 text-blue-700 text-xs px-2 py-0.5 rounded-full">{{ __('branches.main') }}</span>
                        @endif
                    </div>
                    <div class="flex items-center gap-3">
                        @if ($branch->is_active)
                            <span class="text-xs text-green-600">{{ __('general.active') }}</span>
                        @else
                            <span class="text-xs text-red-500">{{ __('general.inactive') }}</span>
                        @endif
                        <a href="{{ route('schools.branches.show', [$school, $branch]) }}"
                            class="text-blue-600 hover:underline text-xs">{{ __('general.view') }}</a>
                    </div>
                </div>
            @empty
                <p class="text-gray-400 text-sm">{{ __('branches.no_records') }}</p>
            @endforelse
        </div>
    </div>
@endsection