@csrf
@if(isset($school))
    @method('PUT')
@endif
<div class="row">
    <div class="col-md-6 mb-3">
        <label>{{ __('app.fields.code') }}</label>
        <input type="text" name="code" class="form-control" value="{{ old('code', $school->code ?? '') }}" required>
    </div>
    <div class="col-md-6 mb-3">
        <label>{{ __('app.fields.name') }}</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $school->name ?? '') }}" required>
    </div>
    <div class="col-md-6 mb-3">
        <label>{{ __('app.fields.legal_name') }}</label>
        <input type="text" name="legal_name" class="form-control" value="{{ old('legal_name', $school->legal_name ?? '') }}">
    </div>
    <div class="col-md-6 mb-3">
        <label>{{ __('app.fields.email') }}</label>
        <input type="email" name="email" class="form-control" value="{{ old('email', $school->email ?? '') }}">
    </div>
    <div class="col-md-4 mb-3">
        <label>{{ __('app.fields.phone') }}</label>
        <input type="text" name="phone" class="form-control" value="{{ old('phone', $school->phone ?? '') }}">
    </div>
    <div class="col-md-4 mb-3">
        <label>{{ __('app.fields.country') }}</label>
        <input type="text" name="country" class="form-control" value="{{ old('country', $school->country ?? 'FR') }}" required>
    </div>
    <div class="col-md-4 mb-3">
        <label>{{ __('app.fields.currency') }}</label>
        <input type="text" name="currency" class="form-control" value="{{ old('currency', $school->currency ?? 'EUR') }}" required>
    </div>
    <div class="col-md-9 mb-3">
        <label>{{ __('app.fields.timezone') }}</label>
        <select name="timezone" class="form-control" required>
            @foreach ($timezones as $timezone)
                <option value="{{ $timezone }}" @selected(old('timezone', $school->timezone ?? 'Europe/Paris') === $timezone)>{{ $timezone }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3 mb-3 d-flex align-items-center">
        <div class="form-check mt-4">
            <input class="form-check-input" type="checkbox" name="is_active" value="1" @checked(old('is_active', $school->is_active ?? true))>
            <label class="form-check-label">{{ __('app.fields.is_active') }}</label>
        </div>
    </div>
</div>
<button type="submit" class="btn btn-primary">{{ __('app.actions.save') }}</button>
