<title>@yield('title', config('app.name'))</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}" type="image/x-icon" />
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700">
<link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
<link href="{{ asset(App::isLocale('ar') ? 'assets/css/rtl.css' : 'assets/css/ltr.css') }}" rel="stylesheet">
<style>
    body { font-size: 15.5px; }
    .admin-header .nav-link, .side-menu .right-nav-text, .btn, .form-control, .dropdown-item, .badge, .table, .card-text, .page-title h4, .page-title small { font-size: 15px; }
    h1, h2, h3, h4, h5, h6 { line-height: 1.45; }
    .resource-card { border-radius: 14px; }
    .metric-card .metric-icon { font-size: 28px; }
    .sidebar-section-title { letter-spacing: .08em; text-transform: uppercase; font-size: 12px; }
    .badge-soft { background: rgba(0,0,0,.06); color: #495057; }
    .table td, .table th { vertical-align: middle; }
</style>
@yield('css')
