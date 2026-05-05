@csrf
@if(isset($role)) @method('PUT') @endif
<div class="row">
    <div class="col-md-6 mb-3">
        <label>{{ __('app.fields.name') }}</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $role->name ?? '') }}" required>
    </div>
    <div class="col-12">
        <p class="text-muted">{{ __('app.messages.permission_hint') }}</p>
    </div>
    @foreach($permissionGroups as $group => $permissions)
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card resource-card h-100">
                <div class="card-body">
                    <h6 class="mb-3 text-capitalize">{{ str_replace('-', ' ', $group) }}</h6>
                    @foreach($permissions as $permission)
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission }}" id="perm_{{ md5($group.$permission) }}"
                                @checked(in_array($permission, old('permissions', $assignedPermissions ?? []), true))>
                            <label class="form-check-label" for="perm_{{ md5($group.$permission) }}">{{ $permission }}</label>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach
</div>
<button class="btn btn-primary">{{ __('app.actions.save') }}</button>
