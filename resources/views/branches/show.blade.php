@extends('layouts.app')
@section('title', $branch->name)
@section('content')
    <div class="container mx-auto px-4 py-6 max-w-3xl">

        <div class="mb-4 flex items-center justify-between">
            <a href="{{ route('schools.branches.index', $school) }}" class="text-sm text-gray-500 hover:text-gray-700">←
                {{ __('branches.title') }}</a>
            <div class="flex gap-2">
                @can('update', [$branch, $school])
                    <a href="{{ route('schools.branches.edit', [$school, $branch]) }}"
                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm">
                        {{ __('general.edit') }}
                    </a>
                @endcan
                @can('delete', [$branch, $school])
                    <form method="POST" action="{{ route('schools.branches.destroy', [$school, $branch]) }}"
                        onsubmit="return confirm('{{ __('branches.confirm_delete') }}')">
                        @csrf @method('DELETE')
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm">
                            {{ __('general.delete') }}
                        </button>
                    </form>
                @endcan
            </div>
        </div>

        @include('partials.flash')

        <div class="bg-white rounded-xl shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h1 class="text-2xl font-bold text-gray-800">
                    {{ $branch->name }}
                    @if ($branch->is_main)
                        <span class="ml-2 bg-blue-100 text-blue-700 text-sm px-3 py-0.5 rounded-full font-normal">
                            {{ __('branches.main') }}
                        </span>
                    @endif
                </h1>
                @if ($branch->is_active)
                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">{{ __('general.active') }}</span>
                @else
                    <span class="bg-red-100 text-red-600 px-3 py-1 rounded-full text-sm">{{ __('general.inactive') }}</span>
                @endif
            </div>

            <dl class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <dt class="text-gray-500">{{ __('branches.code') }}</dt>
                    <dd class="font-mono font-medium">{{ $branch->code }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">{{ __('schools.name') }}</dt>
                    <dd>
                        <a href="{{ route('schools.show', $school) }}" class="text-blue-600 hover:underline">
                            {{ $school->name }}
                        </a>
                    </dd>
                </div>
                <div>
                    <dt class="text-gray-500">{{ __('branches.email') }}</dt>
                    <dd>{{ $branch->email ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">{{ __('branches.phone') }}</dt>
                    <dd>{{ $branch->phone ?? '—' }}</dd>
                </div>
                @if ($branch->address)
                    <div class="col-span-2">
                        <dt class="text-gray-500">{{ __('branches.address') }}</dt>
                        <dd class="whitespace-pre-line">{{ $branch->address }}</dd>
                    </div>
                @endif
            </dl>
        </div>
    </div>
@endsection