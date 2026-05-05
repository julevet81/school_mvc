<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ App::isLocale('ar') ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    @include('dashboard.layouts.head')
</head>

<body>
    <div class="wrapper">

        <div id="pre-loader">
            <img src="{{ asset('assets/images/pre-loader/loader-01.svg') }}" alt="loader">
        </div>

        {{-- Header --}}
        @include('dashboard.layouts.main-header')

        {{-- Sidebar --}}
        @include('dashboard.layouts.main-sidebar')

        {{-- Main Content --}}
        <div class="content-wrapper">

            @yield('page-header')

            <div class="container-fluid">
                @include('dashboard.partials.alerts')
                @yield('content')
            </div>

            @include('dashboard.layouts.footer')

        </div>

    </div>

    @include('dashboard.layouts.footer-scripts')
</body>

</html>