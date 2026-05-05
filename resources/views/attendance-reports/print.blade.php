<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ App::isLocale('ar') ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <title>{{ __('app.menu.attendance_reports') }}</title>
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
</head>
<body onload="window.print()">
    <div class="container mt-4">
        <h3>{{ __('app.menu.attendance_reports') }}</h3>
        <table class="table table-bordered">
            <thead><tr><th>{{ __('app.fields.attendance_date') }}</th><th>{{ __('app.fields.student_no') }}</th><th>{{ __('app.fields.name') }}</th><th>{{ __('app.fields.branch') }}</th><th>{{ __('app.fields.section') }}</th><th>{{ __('app.fields.status') }}</th><th>{{ __('app.fields.remarks') }}</th></tr></thead>
            <tbody>
                @foreach($rows as $row)
                    <tr>
                        <td>{{ $row->session?->attendance_date?->format('Y-m-d') }}</td>
                        <td>{{ $row->student?->student_no }}</td>
                        <td>{{ $row->student?->full_name }}</td>
                        <td>{{ $row->session?->branch?->name }}</td>
                        <td>{{ $row->session?->section?->name }}</td>
                        <td>{{ __('app.status.'.$row->status) }}</td>
                        <td>{{ $row->remarks }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
