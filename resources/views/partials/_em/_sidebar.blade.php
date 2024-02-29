<div id="sidebar" class="sidebar responsive ace-save-state menu-min em-sidebar">
    <script type="text/javascript">
        try {
            ace.settings.loadState('sidebar')
        } catch (e) {

        }
    </script>

    <ul class="nav nav-list">
        <li class="{{ request()->segment(1) == 'em' && request()->segment(2) == 'dashboard' ? 'active' : '' }}">
            <a href="{{ route('em.dashboard') }}">
                <i class="menu-icon fa fa-tachometer"></i>
                <span class="menu-text"> Dashboard </span>
            </a>
            <b class="arrow"></b>
        </li>

        <li>
            <a target="_blank" href="https://play.google.com/store/apps/details?id=com.banglafire.hrm">
                <i class="menu-icon fa fa-mobile"></i>
                <span class="menu-text"> Smart HRM App </span>
            </a>

            <b class="arrow"></b>
        </li>

        @if(hasPermission('profiles.index', $em_slugs))
            <li class="{{ request()->segment(1) == 'em' && request()->segment(2) == 'profile' ? 'active' : '' }}">
                <a href="{{ route('em.profile.index') }}">
                    <i class="menu-icon fa fa-user"></i>
                    <span class="menu-text"> Profile </span>
                </a>
                <b class="arrow"></b>
            </li>
        @endif


        @if (hasPermission('e.leave.applications.index', $em_slugs) || hasPermission('e.short.leaves.index', $em_slugs))
            <li class="{{ request()->segment(1) == 'em' && (request()->segment(2) == 'short-leave' || request()->segment(2) == 'leave') ? 'active open' : '' }}">
                <a href="#" class="dropdown-toggle">
                    <i class="menu-icon fa fa-calendar-minus-o"></i>
                    <span class="menu-text">
                        Leave
                    </span>
                    <b class="arrow fa fa-angle-down"></b>
                </a>
                <b class="arrow"></b>

                <ul class="submenu">
                    @if (hasPermission('e.leave.applications.create', $em_slugs))
                        <li class="{{ request()->is('em/leave/create') ? 'active' : '' }}">
                            <a href="{{ route('em.leave.create') }}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Leave Applications
                            </a>
                            <b class="arrow"></b>
                        </li>
                    @endif
                    @if (hasPermission('e.leave.applications.index', $em_slugs))
                    <li class="{{ request()->segment(2) == 'leave' ? 'active' : '' }}">
                        <a href="{{ route('em.leave.index') }}">
                            <i class="menu-icon fa fa-caret-right"></i>
                            Leave Applications List
                        </a>
                        <b class="arrow"></b>
                    </li>
                @endif

                    @if (hasPermission('e.short.leaves.index', $em_slugs))
                        <li class="{{ request()->segment(2) == 'short-leave' ? 'active' : '' }}">
                            <a href="{{ route('em.short-leave.index') }}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Short Leaves
                            </a>
                            <b class="arrow"></b>
                        </li>
                    @endif
                </ul>
            </li>
        @endif


        @if (hasPermission('e.hr.loans.index', $em_slugs))
            <li class="{{ request()->segment(2) == 'hr-loans' ? 'open' : '' }}">
                <a href="#" class="dropdown-toggle">
                    <i class="menu-icon fa fa-globe"></i>
                    HR Loans
                    <b class="arrow fa fa-angle-down"></b>
                </a>
                <b class="arrow"></b>
                <ul class="submenu">
                    @if (hasPermission('e.hr.loans.create', $em_slugs))
                        <li class="{{ request()->is('em/hr-loans/create') ? 'active' : '' }}">
                            <a href="{{ route('em.hr-loans.create') }}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Create Loan
                            </a>
                            <b class="arrow"></b>
                        </li>
                    @endif

                    <li class="{{ request()->is('em/hr-loans') ? 'active' : '' }}">
                        <a href="{{ route('em.hr-loans.index') }}">
                            <i class="menu-icon fa fa-caret-right"></i>
                            Loan List
                        </a>
                        <b class="arrow"></b>
                    </li>
                </ul>
            </li>
        @endif


        @if (hasPermission('e.out.works.index', $em_slugs))
            <li class="{{ request()->segment(2) == 'out-works' ? 'open' : '' }}">
                <a href="#" class="dropdown-toggle">
                    <i class="menu-icon fa fa-external-link"></i>
                    Out Work
                    <b class="arrow fa fa-angle-down"></b>
                </a>
                <b class="arrow"></b>

                <ul class="submenu">
                    @if (hasPermission('e.out.works.create', $em_slugs))
                        <li class="{{ request()->is('em/out-works/create') ? 'active' : '' }}">
                            <a href="{{ route('em.out-works.create') }}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                O/W Create
                            </a>
                            <b class="arrow"></b>
                        </li>
                    @endif

                    <li class="{{ request()->is('em/out-works') ? 'active' : '' }}">
                        <a href="{{ route('em.out-works.index') }}">
                            <i class="menu-icon fa fa-caret-right"></i>
                            O/W List
                        </a>
                        <b class="arrow"></b>
                    </li>
                    {{-- @if (hasPermission("e.out.works.approve", $em_slugs)) --}}
                    <li class="{{ request()->is('em/pending-out-work?is_not_approved*') ? 'active' : '' }}">
                        <a href="{{ route('em.outwork.pending',['is_not_approved' => 1]) }}" title="Out Work List">
                            <i class="menu-icon fa fa-caret-right"></i>
                           Pending List
                        </a>
                        <b class="arrow"></b>
                    </li>
                {{-- @endif --}}
                </ul>
            </li>
        @endif


        @if (hasPermission('e.other.expenses.index', $em_slugs))
            <li class="{{ request()->segment(2) == 'other-expenses' ? 'open' : '' }}">
                <a href="#" class="dropdown-toggle">
                    <i class="menu-icon fa fa-external-link"></i>
                    Other Expense
                    <b class="arrow fa fa-angle-down"></b>
                </a>
                <b class="arrow"></b>

                <ul class="submenu">
                    @if (hasPermission('e.other.expenses.create', $em_slugs))
                        <li class="{{ request()->is('em/hrm/other-expenses/create') ? 'active' : '' }}">
                            <a href="{{ route('em.other-expenses.create') }}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Other Expense Create
                            </a>
                            <b class="arrow"></b>
                        </li>
                    @endif

                    <li class="{{ request()->is('em/hrm/other-expenses') ? 'active' : '' }}">
                        <a href="{{ route('em.other-expenses.index') }}">
                            <i class="menu-icon fa fa-caret-right"></i>
                            Other Expense List
                        </a>
                        <b class="arrow"></b>
                    </li>
                </ul>
            </li>
        @endif


        @if (hasPermission('e.daily.works.index', $em_slugs))
            <li class="{{ request()->segment(2) == 'daily-works' ? 'open' : '' }}">
                <a href="#" class="dropdown-toggle">
                    <i class="menu-icon fa fa-external-link"></i>
                    Daily Work
                    <b class="arrow fa fa-angle-down"></b>
                </a>
                <b class="arrow"></b>

                <ul class="submenu">
                    @if (hasPermission('e.daily.works.create', $em_slugs))
                        <li class="{{ request()->is('em/daily-works/create') ? 'active' : '' }}">
                            <a href="{{ route('em.daily-works.create') }}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                D/W Create
                            </a>
                            <b class="arrow"></b>
                        </li>
                    @endif

                    <li class="{{ request()->is('em/daily-works') ? 'active' : '' }}">
                        <a href="{{ route('em.daily-works.index') }}">
                            <i class="menu-icon fa fa-caret-right"></i>
                            D/W List
                        </a>
                        <b class="arrow"></b>
                    </li>

                </ul>
            </li>
        @endif

        {{-- @if(hasPermission('e.employees.index', $em_slugs)) --}}
            <li class="{{ request()->is('em/employees') && (!request()->has('all')) ? 'active' : '' }}">
                <a href="{{ route('em.employees.index') }}">
                    <i class="menu-icon fa fa-users"></i>
                    Employee List
                </a>
                <b class="arrow"></b>
            </li>
            <li class="{{ request()->is('em/employees') && (request()->has('all')) ? 'active' : '' }}">
                <a href="{{ route('em.employees.index',['all'=>'true']) }}">
                    <i class="menu-icon fa fa-users"></i>
                   All Employee
                </a>
                <b class="arrow"></b>
            </li>
        {{-- @endif --}}
        @if(hasPermission('e.attendances.index', $em_slugs))
            <li class="{{ request()->is('em/attendance') ? 'active' : '' }}">
                <a href="{{ route('em.attendance') }}?month={{ fdate(now(), 'Y-m') }}">
                    <i class="menu-icon fa fa-file-o"></i>
                    Attendance
                </a>
                <b class="arrow"></b>
            </li>
        @endif

        @if (hasPermission('e.payslips.index', $em_slugs))
            <li class="{{ request()->is('em/payroll/payslip') ? 'active' : '' }}">
                <a href="{{ route('em.payroll.payslip.index') }}">
                    <i class="menu-icon fa fa-money"></i>
                    Payslip
                </a>
                <b class="arrow"></b>
            </li>
        @endif

        @if (hasPermission('e.notices.index', $em_slugs))
            <li class="{{ request()->is('em/employee-notic*') ? 'active' : '' }}">
                <a href="{{ route('employee.notices.index') }}">
                    <i class="menu-icon fa fa-bell"></i>
                    Notice
                </a>
                <b class="arrow"></b>
            </li>
        @endif

        <li class="{{ request()->is('em/employee-password-change') ? 'active' : '' }}">
            <a href="{{ route('employee.password.change') }}">
                <i class="menu-icon fa fa-lock"></i>
                Change Password
            </a>
            <b class="arrow"></b>
        </li>


    </ul>

    <div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
        <i id="sidebar-toggle-icon" class="ace-icon fa fa-angle-double-left ace-save-state"
           data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
    </div>
</div>
