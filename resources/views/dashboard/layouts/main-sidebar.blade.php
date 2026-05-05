@php
    $segments = request()->segments();
    $currentSegment = $segments[1] ?? $segments[0] ?? null;
@endphp
<aside class="side-menu-fixed">
    <div class="scrollbar side-menu-bg">
        <ul class="nav navbar-nav side-menu" id="sidebarnav">
            <li>
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <div class="pull-left"><i class="ti-home"></i><span class="right-nav-text">{{ __('app.menu.dashboard') }}</span></div>
                </a>
            </li>

            <li class="mt-10 mb-10 text-muted pl-4 font-medium menu-title sidebar-section-title">{{ __('app.menu.platform') }}</li>
            <li>
                <a href="{{ route('schools.index') }}" class="{{ str_contains(request()->path(), 'schools') ? 'active' : '' }}">
                    <i class="ti-direction-alt"></i><span class="right-nav-text">{{ __('app.menu.schools') }}</span>
                </a>
            </li>

            <li class="mt-10 mb-10 text-muted pl-4 font-medium menu-title sidebar-section-title">{{ __('app.menu.academics') }}</li>
            <li><a href="{{ route('academic-years.index') }}" class="{{ request()->routeIs('academic-years.*') ? 'active' : '' }}"><i class="ti-calendar"></i><span class="right-nav-text">{{ __('app.menu.academic_years') }}</span></a></li>
            <li><a href="{{ route('grades.index') }}" class="{{ request()->routeIs('grades.*') ? 'active' : '' }}"><i class="ti-medall"></i><span class="right-nav-text">{{ __('app.menu.grades') }}</span></a></li>
            <li><a href="{{ route('classrooms.index') }}" class="{{ request()->routeIs('classrooms.*') ? 'active' : '' }}"><i class="ti-blackboard"></i><span class="right-nav-text">{{ __('app.menu.classrooms') }}</span></a></li>

            <li class="mt-10 mb-10 text-muted pl-4 font-medium menu-title sidebar-section-title">{{ __('app.menu.people') }}</li>
            <li><a href="{{ route('students.index') }}" class="{{ request()->routeIs('students.*') ? 'active' : '' }}"><i class="ti-user"></i><span class="right-nav-text">{{ __('app.menu.students') }}</span></a></li>
            <li><a href="{{ route('employees.index') }}" class="{{ request()->routeIs('employees.*') ? 'active' : '' }}"><i class="ti-id-badge"></i><span class="right-nav-text">{{ __('app.menu.employees') }}</span></a></li>

            <li class="mt-10 mb-10 text-muted pl-4 font-medium menu-title sidebar-section-title">{{ __('app.menu.attendance') }}</li>
            <li><a href="{{ route('attendances.index') }}" class="{{ request()->routeIs('attendances.*') ? 'active' : '' }}"><i class="ti-check-box"></i><span class="right-nav-text">{{ __('app.menu.attendance_register') }}</span></a></li>
            <li><a href="{{ route('attendance-reports.index') }}" class="{{ request()->routeIs('attendance-reports.*') ? 'active' : '' }}"><i class="ti-bar-chart"></i><span class="right-nav-text">{{ __('app.menu.attendance_reports') }}</span></a></li>

            <li class="mt-10 mb-10 text-muted pl-4 font-medium menu-title sidebar-section-title">{{ __('app.menu.finance') }}</li>
            <li><a href="{{ route('fees.index') }}" class="{{ request()->routeIs('fees.*') ? 'active' : '' }}"><i class="ti-wallet"></i><span class="right-nav-text">{{ __('app.menu.fees') }}</span></a></li>
            <li><a href="{{ route('invoices.index') }}" class="{{ request()->routeIs('invoices.*') ? 'active' : '' }}"><i class="ti-receipt"></i><span class="right-nav-text">{{ __('app.menu.invoices') }}</span></a></li>

            <li class="mt-10 mb-10 text-muted pl-4 font-medium menu-title sidebar-section-title">{{ __('app.menu.security') }}</li>
            <li><a href="{{ route('user-access.index') }}" class="{{ request()->routeIs('user-access.*') ? 'active' : '' }}"><i class="ti-user"></i><span class="right-nav-text">{{ __('app.menu.users') }}</span></a></li>
            <li><a href="{{ route('roles.index') }}" class="{{ request()->routeIs('roles.*') ? 'active' : '' }}"><i class="ti-shield"></i><span class="right-nav-text">{{ __('app.menu.roles') }}</span></a></li>
            <li><a href="{{ route('permissions.index') }}" class="{{ request()->routeIs('permissions.*') ? 'active' : '' }}"><i class="ti-key"></i><span class="right-nav-text">{{ __('app.menu.permissions') }}</span></a></li>
        </ul>
    </div>
</aside>
