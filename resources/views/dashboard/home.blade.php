@extends('dashboard.layouts.master')

@section('title', __('app.menu.dashboard'))

@section('page-header')
    @include('dashboard.partials.page-header', [
        'title' => __('app.menu.dashboard'),
        'subtitle' => __('app.dashboard.subtitle'),
    ])
@endsection

@section('content')
    <div class="row">
        @foreach ([
            ['label' => __('app.menu.schools'), 'value' => $stats['schools'], 'icon' => 'ti-direction-alt', 'color' => 'primary'],
            ['label' => __('app.menu.students'), 'value' => $stats['students'], 'icon' => 'ti-user', 'color' => 'success'],
            ['label' => __('app.menu.employees'), 'value' => $stats['employees'], 'icon' => 'ti-id-badge', 'color' => 'warning'],
            ['label' => __('app.menu.invoices'), 'value' => $stats['active_invoices'], 'icon' => 'ti-receipt', 'color' => 'danger'],
            ['label' => __('app.dashboard.today_absences'), 'value' => $stats['today_absences'], 'icon' => 'ti-alert', 'color' => 'danger'],
            ['label' => __('app.dashboard.today_attendance_sessions'), 'value' => $stats['today_attendance_sessions'], 'icon' => 'ti-check-box', 'color' => 'info'],
        ] as $metric)
            <div class="col-xl-3 col-md-6 mb-30">
                <div class="card card-statistics h-100 resource-card metric-card">
                    <div class="card-body">
                        <div class="clearfix">
                            <div class="float-left text-{{ $metric['color'] }}">
                                <i class="{{ $metric['icon'] }} metric-icon"></i>
                            </div>
                            <div class="float-right text-right">
                                <p class="card-text text-dark">{{ $metric['label'] }}</p>
                                <h4>{{ number_format($metric['value']) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row">
        <div class="col-xl-4 mb-30">
            <div class="card h-100 resource-card">
                <div class="card-body">
                    <h5 class="card-title">{{ __('app.dashboard.financial_overview') }}</h5>
                    <div class="mb-3">
                        <strong>{{ __('app.fields.total_due') }}:</strong>
                        {{ number_format($stats['revenue_due'], 2) }}
                    </div>
                    <div class="mb-3">
                        <strong>{{ __('app.fields.total_paid') }}:</strong>
                        {{ number_format($stats['revenue_paid'], 2) }}
                    </div>
                    <div class="mb-3">
                        <strong>{{ __('app.menu.branches') }}:</strong>
                        {{ number_format($stats['branches']) }}
                    </div>
                    <div>
                        <strong>{{ __('app.menu.academic_years') }}:</strong>
                        {{ number_format($stats['academic_years']) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-8 mb-30">
            <div class="card h-100 resource-card">
                <div class="card-body">
                    <h5 class="card-title">{{ __('app.dashboard.recent_invoices') }}</h5>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>{{ __('app.fields.invoice_no') }}</th>
                                    <th>{{ __('app.fields.student') }}</th>
                                    <th>{{ __('app.fields.branch') }}</th>
                                    <th>{{ __('app.fields.total') }}</th>
                                    <th>{{ __('app.fields.status') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentInvoices as $invoice)
                                    <tr>
                                        <td>{{ $invoice->invoice_no }}</td>
                                        <td>{{ $invoice->student?->full_name }}</td>
                                        <td>{{ $invoice->branch?->name }}</td>
                                        <td>{{ number_format($invoice->total, 2) }}</td>
                                        <td><span class="badge badge-pill badge-soft">{{ $invoice->status }}</span></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center text-muted">{{ __('app.messages.no_data') }}</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6 mb-30">
            <div class="card resource-card">
                <div class="card-body">
                    <h5 class="card-title">{{ __('app.dashboard.recent_schools') }}</h5>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>{{ __('app.fields.name') }}</th>
                                    <th>{{ __('app.fields.code') }}</th>
                                    <th>{{ __('app.menu.branches') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentSchools as $school)
                                    <tr>
                                        <td>{{ $school->name }}</td>
                                        <td>{{ $school->code }}</td>
                                        <td>{{ $school->branches_count }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-center text-muted">{{ __('app.messages.no_data') }}</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 mb-30">
            <div class="card resource-card">
                <div class="card-body">
                    <h5 class="card-title">{{ __('app.dashboard.recent_students') }}</h5>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>{{ __('app.fields.student_no') }}</th>
                                    <th>{{ __('app.fields.name') }}</th>
                                    <th>{{ __('app.fields.school') }}</th>
                                    <th>{{ __('app.fields.status') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentStudents as $student)
                                    <tr>
                                        <td>{{ $student->student_no }}</td>
                                        <td>{{ $student->full_name }}</td>
                                        <td>{{ $student->school?->name }}</td>
                                        <td><span class="badge badge-pill badge-soft">{{ $student->status }}</span></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center text-muted">{{ __('app.messages.no_data') }}</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
