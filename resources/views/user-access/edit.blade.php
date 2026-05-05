@extends('dashboard.layouts.master')
@section('title', $user->name)
@section('page-header')
    @include('dashboard.partials.page-header', ['title' => $user->name, 'subtitle' => __('app.resources.user_access')])
@endsection
@section('content')
    <form method="POST" action="{{ route('user-access.update', $user) }}">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-xl-4 mb-30">
                <div class="card resource-card h-100">
                    <div class="card-body">
                        <h5 class="card-title">{{ __('app.fields.roles') }}</h5>
                        @foreach($roles as $role)
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $role->name }}" id="role_{{ $role->id }}"
                                    @checked(in_array($role->name, old('roles', $assignedRoles), true))>
                                <label class="form-check-label" for="role_{{ $role->id }}">{{ $role->name }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-xl-8 mb-30">
                <div class="card resource-card h-100">
                    <div class="card-body">
                        <h5 class="card-title">{{ __('app.fields.direct_permissions') }}</h5>
                        <div class="row">
                            @foreach($permissions as $permission)
                                <div class="col-md-6 col-xl-4 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->name }}" id="permission_{{ $permission->id }}"
                                            @checked(in_array($permission->name, old('permissions', $directPermissions), true))>
                                        <label class="form-check-label" for="permission_{{ $permission->id }}">{{ $permission->name }}</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button class="btn btn-primary">{{ __('app.actions.save') }}</button>
    </form>
@endsection
