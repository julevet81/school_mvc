@extends('layouts.app')
@section('title', __('branches.title'))
@section('content')
    <div class="container mx-auto px-4 py-6">

        <div class="flex items-center justify-between mb-6">
            <div>
                <a href="{{ route('schools.show', $school) }}" class="text-sm text-gray-500 hover:text-gray-700">←
                    {{ $school->name }}</a>
                <h1 class="text-2xl font-bold text-gray-800 mt-1">{{ __('branches.title') }}</h1>
            </div>
            @can('create', [\App\Models\Branch::class, $school])
                <a href="{{ route('schools.branches.create', $school) }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition">
                    + {{ __('branches.add_new') }}
                </a>
            @endcan
        </div>

        @include('partials.flash')

        {{-- Filters --}}
        <form method="GET" action="{{ route('schools.branches.index', $school) }}" class="mb-4 flex flex-wrap gap-3">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="{{ __('branches.search_placeholder') }}" class="border rounded-lg px-3 py-2 text-sm w-64">
            <select name="is_active" class="border rounded-lg px-3 py-2 text-sm">
                <option value="">{{ __('general.all_statuses') }}</option>
                <option value="1" @selected(request('is_active') === '1')>{{ __('general.active') }}</option>
                <option value="0" @selected(request('is_active') === '0')>{{ __('general.inactive') }}</option>
            </select>
            <button type="submit"
                class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm transition">
                {{ __('general.filter') }}
            </button>
            <a href="{{ route('schools.branches.index', $school) }}"
                class="text-sm text-gray-500 hover:text-gray-700 self-center">{{ __('general.reset') }}</a>
        </form>

        {{-- Table --}}
        <div class="overflow-x-auto bg-white rounded-xl shadow">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3 text-left">{{ __('branches.code') }}</th>
                        <th class="px-4 py-3 text-left">{{ __('branches.name') }}</th>
                        <th class="px-4 py-3 text-left">{{ __('branches.email') }}</th>
                        <th class="px-4 py-3 text-left">{{ __('branches.phone') }}</th>
                        <th class="px-4 py-3 text-center">{{ __('branches.main') }}</th>
                        <th class="px-4 py-3 text-center">{{ __('general.status') }}</th>
                        <th class="px-4 py-3 text-center">{{ __('general.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($branches as $branch)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 font-mono text-gray-600">{{ $branch->code }}</td>
                            <td class="px-4 py-3 font-medium text-gray-800">{{ $branch->name }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $branch->email ?? '—' }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $branch->phone ?? '—' }}</td>
                            <td class="px-4 py-3 text-center">
                                @if ($branch->is_main)
                                    <span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full text-xs">✓</span>
                                @else
                                    <span class="text-gray-300">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if ($branch->is_active)
                                    <span
                                        class="bg-green-100 text-green-700 px-2 py-0.5 rounded-full text-xs">{{ __('general.active') }}</span>
                                @else
                                    <span
                                        class="bg-red-100 text-red-600 px-2 py-0.5 rounded-full text-xs">{{ __('general.inactive') }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex justify-center items-center gap-2">
                                    <a href="{{ route('schools.branches.show', [$school, $branch]) }}"
                                        class="text-blue-600 hover:underline text-xs">{{ __('general.view') }}</a>

                                    @can('update', [$branch, $school])
                                        <a href="{{ route('schools.branches.edit', [$school, $branch]) }}"
                                            class="text-yellow-600 hover:underline text-xs">{{ __('general.edit') }}</a>

                                        @unless ($branch->is_main)
                                            <form method="POST" action="{{ route('schools.branches.set-main', [$school, $branch]) }}">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="text-blue-500 hover:underline text-xs">
                                                    {{ __('branches.set_as_main') }}
                                                </button>
                                            </form>
                                        @endunless

                                        <form method="POST"
                                            action="{{ route('schools.branches.toggle-active', [$school, $branch]) }}">
                                            @csrf @method('PATCH')
                                            <button type="submit"
                                                class="text-xs {{ $branch->is_active ? 'text-orange-500' : 'text-green-600' }} hover:underline">
                                                {{ $branch->is_active ? __('general.deactivate') : __('general.activate') }}
                                            </button>
                                        </form>
                                    @endcan

                                    @can('delete', [$branch, $school])
                                        <form method="POST" action="{{ route('schools.branches.destroy', [$school, $branch]) }}"
                                            onsubmit="return confirm('{{ __('branches.confirm_delete') }}')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="text-red-600 hover:underline text-xs">{{ __('general.delete') }}</button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-400">
                                {{ __('branches.no_records') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $branches->links() }}</div>
    </div>
@endsection