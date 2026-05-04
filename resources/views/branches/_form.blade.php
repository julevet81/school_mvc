@props(['branch' => null])

<div class="space-y-5">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('branches.code') }} *</label>
            <input type="text" name="code" value="{{ old('code', $branch?->code) }}"
                class="w-full border rounded-lg px-3 py-2 text-sm @error('code') border-red-500 @enderror"
                maxlength="30" required>
            @error('code') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('branches.name') }} *</label>
            <input type="text" name="name" value="{{ old('name', $branch?->name) }}"
                class="w-full border rounded-lg px-3 py-2 text-sm @error('name') border-red-500 @enderror"
                maxlength="255" required>
            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('branches.email') }}</label>
            <input type="email" name="email" value="{{ old('email', $branch?->email) }}"
                class="w-full border rounded-lg px-3 py-2 text-sm @error('email') border-red-500 @enderror">
            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('branches.phone') }}</label>
            <input type="text" name="phone" value="{{ old('phone', $branch?->phone) }}"
                class="w-full border rounded-lg px-3 py-2 text-sm" maxlength="30">
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('branches.address') }}</label>
        <textarea name="address" rows="3"
            class="w-full border rounded-lg px-3 py-2 text-sm">{{ old('address', $branch?->address) }}</textarea>
    </div>

    <div class="flex items-center gap-6">
        <div class="flex items-center gap-2">
            <input type="hidden" name="is_main" value="0">
            <input type="checkbox" id="is_main" name="is_main" value="1" @checked(old('is_main', $branch?->is_main))
                class="rounded border-gray-300">
            <label for="is_main" class="text-sm text-gray-700">{{ __('branches.set_as_main') }}</label>
        </div>
        <div class="flex items-center gap-2">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" id="is_active" name="is_active" value="1" @checked(old('is_active', $branch?->is_active ?? true)) class="rounded border-gray-300">
            <label for="is_active" class="text-sm text-gray-700">{{ __('general.active') }}</label>
        </div>
    </div>
</div>