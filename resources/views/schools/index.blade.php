@extends('dashboard.layouts.master')

@section('title', __('schools.title'))

@section('content')
<div class="container mx-auto px-4 py-6">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">{{ __('schools.title') }}</h1>
        @can('create', \App\Models\School::class)
            <a href="{{ route('schools.create') }}"
               class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
                + {{ __('schools.add_new') }}
            </a>
        @endcan
    </div>

    {{-- Flash messages --}}
    @include('partials.flash')

    {{-- Filters --}}
    <form method="GET" action="{{ route('schools.index') }}" class="mb-4 flex flex-wrap gap-3">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="{{ __('schools.search_placeholder') }}"
               class="border rounded-lg px-3 py-2 text-sm w-64 focus:ring-2 focus:ring-blue-400">

        <select name="is_active" class="border rounded-lg px-3 py-2 text-sm">
            <option value="">{{ __('general.all_statuses') }}</option>
            <option value="1" @selected(request('is_active') === '1')>{{ __('general.active') }}</option>
            <option value="0" @selected(request('is_active') === '0')>{{ __('general.inactive') }}</option>
        </select>

        <button type="submit"
                class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm transition">
            {{ __('general.filter') }}
        </button>
        <a href="{{ route('schools.index') }}"
           class="text-sm text-gray-500 hover:text-gray-700 self-center">{{ __('general.reset') }}</a>
    </form>

    {{-- Table --}}
    <div class="overflow-x-auto bg-white rounded-xl shadow">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                <tr>
                    <th class="px-4 py-3 text-left">{{ __('schools.code') }}</th>
                    <th class="px-4 py-3 text-left">{{ __('schools.name') }}</th>
                    <th class="px-4 py-3 text-left">{{ __('schools.email') }}</th>
                    <th class="px-4 py-3 text-left">{{ __('schools.country') }}</th>
                    <th class="px-4 py-3 text-center">{{ __('schools.branches') }}</th>
                    <th class="px-4 py-3 text-center">{{ __('general.status') }}</th>
                    <th class="px-4 py-3 text-center">{{ __('general.actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($schools as $school)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 font-mono text-gray-600">{{ $school->code }}</td>
                        <td class="px-4 py-3 font-medium text-gray-800">{{ $school->name }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $school->email ?? '—' }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $school->country }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full text-xs font-medium">
                                {{ $school->branches_count }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if ($school->is_active)
                                <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded-full text-xs">{{ __('general.active') }}</span>
                            @else
                                <span class="bg-red-100 text-red-600 px-2 py-0.5 rounded-full text-xs">{{ __('general.inactive') }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex justify-center items-center gap-2">
                                <a href="{{ route('schools.show', $school) }}"
                                   class="text-blue-600 hover:underline text-xs">{{ __('general.view') }}</a>

                                @can('update', $school)
                                    <a href="{{ route('schools.edit', $school) }}"
                                       class="text-yellow-600 hover:underline text-xs">{{ __('general.edit') }}</a>

                                    <form method="POST" action="{{ route('schools.toggle-active', $school) }}">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="text-xs {{ $school->is_active ? 'text-orange-500' : 'text-green-600' }} hover:underline">
                                            {{ $school->is_active ? __('general.deactivate') : __('general.activate') }}
                                        </button>
                                    </form>
                                @endcan

                                @can('delete', $school)
                                    <form method="POST" action="{{ route('schools.destroy', $school) }}"
                                          onsubmit="return confirm('{{ __('schools.confirm_delete') }}')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline text-xs">{{ __('general.delete') }}</button>
                                    </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-gray-400">
                            {{ __('schools.no_records') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $schools->links() }}
    </div>
</div>
@endsection