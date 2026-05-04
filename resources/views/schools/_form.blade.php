@props(['school' => null, 'timezones' => []])

<div class="space-y-5">

    {{-- Code --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('schools.code') }} *</label>
        <input type="text" name="code"
               value="{{ old('code', $school?->code) }}"
               class="w-full border rounded-lg px-3 py-2 text-sm @error('code') border-red-500 @enderror"
               maxlength="30" required>
        @error('code') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Name --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('schools.name') }} *</label>
        <input type="text" name="name"
               value="{{ old('name', $school?->name) }}"
               class="w-full border rounded-lg px-3 py-2 text-sm @error('name') border-red-500 @enderror"
               maxlength="255" required>
        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Legal Name --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('schools.legal_name') }}</label>
        <input type="text" name="legal_name"
               value="{{ old('legal_name', $school?->legal_name) }}"
               class="w-full border rounded-lg px-3 py-2 text-sm"
               maxlength="255">
    </div>

    {{-- Email & Phone --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('schools.email') }}</label>
            <input type="email" name="email"
                   value="{{ old('email', $school?->email) }}"
                   class="w-full border rounded-lg px-3 py-2 text-sm @error('email') border-red-500 @enderror">
            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('schools.phone') }}</label>
            <input type="text" name="phone"
                   value="{{ old('phone', $school?->phone) }}"
                   class="w-full border rounded-lg px-3 py-2 text-sm"
                   maxlength="30">
        </div>
    </div>

    {{-- Country, Timezone, Currency --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('schools.country') }} *</label>
            <input type="text" name="country"
                   value="{{ old('country', $school?->country ?? 'NG') }}"
                   class="w-full border rounded-lg px-3 py-2 text-sm uppercase @error('country') border-red-500 @enderror"
                   maxlength="2" required>
            @error('country') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('schools.currency') }} *</label>
            <input type="text" name="currency"
                   value="{{ old('currency', $school?->currency ?? 'NGN') }}"
                   class="w-full border rounded-lg px-3 py-2 text-sm uppercase @error('currency') border-red-500 @enderror"
                   maxlength="3" required>
            @error('currency') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('schools.timezone') }} *</label>
            <select name="timezone"
                    class="w-full border rounded-lg px-3 py-2 text-sm @error('timezone') border-red-500 @enderror" required>
                @foreach ($timezones as $tz)
                    <option value="{{ $tz }}" @selected(old('timezone', $school?->timezone) === $tz)>{{ $tz }}</option>
                @endforeach
            </select>
            @error('timezone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
    </div>

    {{-- Is Active --}}
    <div class="flex items-center gap-2">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" id="is_active" name="is_active" value="1"
               @checked(old('is_active', $school?->is_active ?? true))
               class="rounded border-gray-300">
        <label for="is_active" class="text-sm text-gray-700">{{ __('general.active') }}</label>
    </div>
</div>