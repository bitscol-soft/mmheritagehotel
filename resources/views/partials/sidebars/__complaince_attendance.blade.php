

@if(hasAnyPermission(['compliance.attendances', 'compliance.shifts', 'compliance.reports'], $slugs))
    <li class="{{ request('report_type') == 'complaince' && (request()->is('hrm/attendance/today') || request()->is('hrm/attendance/monthly')  || request()->is('hrm/attendance/monthly-summary') || request()->is('hrm/attendance/employee-monthly-attendance')) ?  'open' : '' }}">
        <a href="#" class="dropdown-toggle" title="Complaince Attendance Report">
            <i class="menu-icon fa fa-caret-right"></i>
            Comp. Att.
            <b class="arrow fa fa-angle-down"></b>
        </a>
        <b class="arrow"></b>
        <ul class="submenu">
            @if (hasPermission("compliance.shifts", $slugs))
                <li class="{{ request()->is('hrm/hr-setup/complaince-shifts') ? 'active' : '' }}">
                    <a href="{{ route('complaince-shifts.index') }}">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Shifts
                    </a>
                    <b class="arrow"></b>
                </li>
            @endif

            @if (hasPermission("compliance.reports", $slugs))
                <li class="{{ request()->is('hrm/attendance/today') && request('report_type') == 'complaince' ? 'active' : '' }}">
                    <a href="{{ route('hrm.attendance.today') }}?report_type=complaince" title="Today Attendance Report">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Today Attd
                    </a>
                    <b class="arrow"></b>
                </li>

                <li class="{{ request()->is('hrm/attendance/monthly') && request('report_type') == 'complaince' ? 'active' : '' }}">
                    <a href="{{ route('hrm.attendance.monthly') }}?report_type=complaince" title="Monthly Attendance Report">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Monthly Attd
                    </a>
                    <b class="arrow"></b>
                </li>

                <li class="{{ request()->is('hrm/attendance/monthly-summary') && request('report_type') == 'complaince' ? 'active' : '' }}">
                    <a href="{{ route('hrm.attendance.monthly.summary') }}?report_type=complaince" title="Monthly Attendance Summury">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Monthly Sum
                    </a>
                    <b class="arrow"></b>
                </li>
            @endif
        </ul>
    </li>
@endif

