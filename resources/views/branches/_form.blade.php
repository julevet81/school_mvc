@csrf
@if(isset($branch))
    @method('PUT')
@endif
<div class="row">
    <div class="col-md-6 mb-3"><label>{{ __('app.fields.code') }}</label><input type="text" name="code" class="form-control" value="{{ old('code', $branch->code ?? '') }}" required></div>
    <div class="col-md-6 mb-3"><label>{{ __('app.fields.name') }}</label><input type="text" name="name" class="form-control" value="{{ old('name', $branch->name ?? '') }}" required></div>
    <div class="col-md-6 mb-3"><label>{{ __('app.fields.email') }}</label><input type="email" name="email" class="form-control" value="{{ old('email', $branch->email ?? '') }}"></div>
    <div class="col-md-6 mb-3"><label>{{ __('app.fields.phone') }}</label><input type="text" name="phone" class="form-control" value="{{ old('phone', $branch->phone ?? '') }}"></div>
    <div class="col-md-12 mb-3"><label>{{ __('app.fields.address') }}</label><textarea name="address" class="form-control" rows="4">{{ old('address', $branch->address ?? '') }}</textarea></div>
    <div class="col-md-6"><div class="form-check"><input class="form-check-input" type="checkbox" name="is_main" value="1" @checked(old('is_main', $branch->is_main ?? false))><label class="form-check-label">{{ __('app.fields.is_main') }}</label></div></div>
    <div class="col-md-6"><div class="form-check"><input class="form-check-input" type="checkbox" name="is_active" value="1" @checked(old('is_active', $branch->is_active ?? true))><label class="form-check-label">{{ __('app.fields.is_active') }}</label></div></div>
</div>
<button type="submit" class="btn btn-primary mt-3">{{ __('app.actions.save') }}</button>
