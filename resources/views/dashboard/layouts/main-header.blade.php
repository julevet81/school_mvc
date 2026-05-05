@php
    $supportedLocales = LaravelLocalization::getSupportedLocales();
@endphp
<nav class="admin-header navbar navbar-default col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    <div class="text-left navbar-brand-wrapper">
        <a class="navbar-brand brand-logo" href="{{ route('dashboard') }}">
            <img src="{{ asset('assets/images/logo-dark.png') }}" alt="logo">
        </a>
        <a class="navbar-brand brand-logo-mini" href="{{ route('dashboard') }}">
            <img src="{{ asset('assets/images/logo-icon-dark.png') }}" alt="logo">
        </a>
    </div>

    <ul class="nav navbar-nav mr-auto">
        <li class="nav-item">
            <a id="button-toggle" class="button-toggle-nav inline-block ml-20 pull-left" href="javascript:void(0);">
                <i class="zmdi zmdi-menu ti-align-right"></i>
            </a>
        </li>
    </ul>

    <ul class="nav navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <a class="nav-link top-nav" data-toggle="dropdown" href="#">
                <i class="ti-world"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                @foreach ($supportedLocales as $localeCode => $properties)
                    <a class="dropdown-item" href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                        {{ $properties['native'] }}
                    </a>
                @endforeach
            </div>
        </li>
        <li class="nav-item dropdown mr-30">
            <a class="nav-link nav-pill user-avatar" data-toggle="dropdown" href="#">
                <img src="{{ asset('assets/images/profile-avatar.jpg') }}" alt="avatar">
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <div class="dropdown-header">
                    <h6 class="mt-0 mb-0">{{ auth()->user()->name }}</h6>
                    <span>{{ auth()->user()->email }}</span>
                </div>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                    <i class="text-primary ti-user"></i> {{ __('app.actions.profile') }}
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item">
                        <i class="text-danger ti-unlock"></i> {{ __('app.actions.logout') }}
                    </button>
                </form>
            </div>
        </li>
    </ul>
</nav>
