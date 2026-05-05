<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ App::isLocale('ar') ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <title>{{ __('app.menu.attendance_register') }}</title>
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
</head>
<body onload="window.print()">
    <div class="container mt-4">
        <h3>{{ __('app.menu.attendance_register') }}</h3>
        <p>{{ __('app.fields.attendance_date') }}: {{ $attendance->attendance_date?->format('Y-m-d') }}</p>
        <p>{{ __('app.fields.school') }}: {{ $attendance->school?->name }} | {{ __('app.fields.branch') }}: {{ $attendance->branch?->name }}</p>
        <p>{{ __('app.fields.section') }}: {{ $attendance->section?->classroom?->name }} / {{ $attendance->section?->name }}</p>
        <table class="table table-bordered">
            <thead><tr><th>{{ __('app.fields.student_no') }}</th><th>{{ __('app.fields.name') }}</th><th>{{ __('app.fields.status') }}</th><th>{{ __('app.fields.remarks') }}</th></tr></thead>
            <tbody>
                @foreach($attendance->studentAttendances as $row)
                    <tr>
                        <td>{{ $row->student?->student_no }}</td>
                        <td>{{ $row->student?->full_name }}</td>
                        <td>{{ __('app.status.'.$row->status) }}</td>
                        <td>{{ $row->remarks }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
