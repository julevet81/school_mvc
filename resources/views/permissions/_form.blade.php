@csrf
@if(isset($permission)) @method('PUT') @endif
<div class="row">
    <div class="col-md-6 mb-3">
        <label>{{ __('app.fields.name') }}</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $permission->name ?? '') }}" required>
    </div>
</div>
<button class="btn btn-primary">{{ __('app.actions.save') }}</button>
