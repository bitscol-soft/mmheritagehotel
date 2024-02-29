<div id="navbar" class="navbar navbar-default          ace-save-state">
    <div class="navbar-container ace-save-state" id="navbar-container">

        <div class="navbar-header pull-left">
            <a href="{{ url('home') }}" class="navbar-brand">
                <small class="text-primary font-weight-bold" style="font-weight: 600">
                    {{-- @if (auth()->user()->company->group->logo == 'default.png') --}}
                    {{-- <span class="dark"> --}}
                    {{-- <i class="fa fa-flag"></i> --}}
                    {{-- {{ config('app.name') }} --}}
                    {{-- </span> --}}
                    {{-- @else --}}
                    {{-- <img class="logo" src="{{ asset('uploads/group/'. auth()->user()->company->group->logo) }}" alt="{{ auth()->user()->company->group->name }}"> --}}
                    {{-- @endif --}}

                    @if (file_exists('uploads/group/' . auth()->user()->company->group->logo))
                        <img class="logo" src="{{ asset('uploads/group/' . auth()->user()->company->group->logo) }}"
                            alt="">
                    @else
                        <span class="blue">
                            <i class="fa fa-flag"></i>
                            {{ auth()->user()->company->group->name }}
                        </span>
                    @endif


                </small>
            </a>
        </div>

        <button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">
            <span class="sr-only">Toggle sidebar</span>

            <span class="icon-bar"></span>

            <span class="icon-bar"></span>

            <span class="icon-bar"></span>
        </button>
        <div class="navbar-buttons navbar-header" style="float: right !important;" role="navigation">
            <ul class="nav ace-nav" style="background-color: transparent;">




                {{-- Leave Application Notification Start --}}

                @php
                    
                    $approveCount = 0;
                    
                    if (hasPermission('l.applications.approve', $slugs)) {
                        $approveCount = \App\Models\Leave\LeaveApplication::where('recommended_by', '!=', '')
                            ->where('approved_by', null)
                            ->where('cancel', 0)
                            ->count();
                    }
                    
                    $usersDesig = optional(auth()->user()->employee)->designation_id;
                    $usersCompany = optional(auth()->user()->employee)->company_id;
                    
                    $leaveApplicantDesig = \App\Models\Leave\ApprovalAuthor\ApplicantDesignation::with('recommender_companies', 'recommender_companies.recommender_designations')
                        ->whereHas('recommender_companies', function ($q) use ($usersCompany, $usersDesig) {
                            $q->whereHas('recommender_designations', function ($sQ) use ($usersDesig) {
                                $sQ->where('recommender_designation_id', $usersDesig);
                            })->where('company_id', $usersCompany);
                        })
                        ->pluck('applicant_designation_id');
                    
                    $leaveApplicantCompany = \App\Models\Leave\ApprovalAuthor\ApplicantDesignation::with('recommender_companies', 'recommender_companies.recommender_designations')
                        ->whereHas('recommender_companies', function ($q) use ($usersCompany, $usersDesig) {
                            $q->whereHas('recommender_designations', function ($sQ) use ($usersDesig) {
                                $sQ->where('recommender_designation_id', $usersDesig);
                            })->where('company_id', $usersCompany);
                        })
                        ->pluck('company_id');
                    
                    $leave_applications = \App\Models\Leave\LeaveApplication::with('company', 'employee', 'employee.company', 'leave_type')
                        ->whereHas('employee', function ($q) use ($leaveApplicantCompany, $leaveApplicantDesig) {
                            $q->whereIn('company_id', $leaveApplicantCompany)->whereIn('designation_id', $leaveApplicantDesig);
                        })
                        ->whereNull('recommended_by')
                        ->where('cancel', 0)
                        ->orderByDesc('employee_id')
                        ->count();
                    
                @endphp

                {{-- Short Leave --}}


                @php
                    
                    $usersDesig = optional(auth()->user()->employee)->designation_id;
                    $usersCompany = optional(auth()->user()->employee)->company_id;
                    
                    $applicantDesig = \App\Models\Leave\ShortLeaveAuthor\SLeaveAppDesig::with('s_leave_companies', 's_leave_companies.s_leave_recom_desigs')
                        ->whereHas('s_leave_companies', function ($q) use ($usersCompany, $usersDesig) {
                            $q->whereHas('s_leave_recom_desigs', function ($sQ) use ($usersDesig) {
                                $sQ->where('recommender_designation_id', $usersDesig);
                            })->where('company_id', $usersCompany);
                        })
                        ->pluck('applicant_designation_id');
                    
                    $applicantCompany = \App\Models\Leave\ShortLeaveAuthor\SLeaveAppDesig::with('s_leave_companies', 's_leave_companies.s_leave_recom_desigs')
                        ->whereHas('s_leave_companies', function ($q) use ($usersCompany, $usersDesig) {
                            $q->whereHas('s_leave_recom_desigs', function ($sQ) use ($usersDesig) {
                                $sQ->where('recommender_designation_id', $usersDesig);
                            })->where('company_id', $usersCompany);
                        })
                        ->pluck('company_id');
                    
                    $short_leave_applications = 0;
                    if (hasPermission('short.leave.applications.recommend', $slugs)) {
                        $short_leave_applications = \App\Models\Leave\ShortLeaveApplication::with('employee', 'employee.company', 'employee.department', 'employee.designation', 'employee.shortLeaveCount')
                            ->whereHas('employee', function ($q) use ($applicantCompany, $applicantDesig) {
                                $q->whereIn('company_id', $applicantCompany)->whereIn('designation_id', $applicantDesig);
                            })
                            ->where('adjust', 0)
                            ->where('seen_by', null)
                            ->count();
                    }
                    
                @endphp

                {{-- General Store --}}

                @php
                    
                    $purchaseApprove = 0;
                    if (hasPermission('purchases.approve', $slugs)) {
                        $purchaseApprove = \App\Models\Gs\Purchase::where('is_approved', 0)->count();
                    }
                    
                @endphp

                @php
                    
                    $requsitionApprove = 0;
                    if (hasPermission('create.requisitions.approve', $slugs)) {
                        $requsitionApprove = \App\Models\Gs\GoodsRequisition::where('is_approved', 0)->count();
                    }
                    
                @endphp


                @php
                    $totalCount = $leave_applications + $approveCount + $short_leave_applications + $purchaseApprove + $requsitionApprove;
                @endphp

                <li class="light-10 dropdown-modal" title="Recommend Notifications">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <i class="fa fa-2x fa-bell dark" style="vertical-align: middle; margin-top: -6px;"></i>
                        @if ($totalCount > 0)
                            <sup
                                style="color: white;font-size: 12px;margin-left: -16px;background-color: red;padding: 2px;border-radius: 50%;">
                                <b>{{ $totalCount }}</b>
                            </sup>
                        @endif

                    </a>

                    <ul
                        class="dropdown-menu-right dropdown-navbar navbar-default dropdown-menu dropdown-caret dropdown-close">
                        <li class="dropdown-header">
                            <i class="ace-icon fa fa-bell-o"></i>
                            {{ $totalCount }}
                            Total Notifications ss
                        </li>

                        <li class="dropdown-content">
                            <ul class="dropdown-menu dropdown-navbar navbar-default">
                                @if (hasPermission('l.applications.recommend', $slugs))
                                    @if ($leave_applications > 0)
                                        <li>
                                            <a href="{{ route('hrm.pending.recommend') }}">
                                                <div class="clearfix">
                                                    <span class="pull-left dark">
                                                        Leave Recommend
                                                    </span>
                                                    <span class="pull-right">
                                                        <span
                                                            class="badge {{ $leave_applications > 0 ? 'badge-danger' : 'badge-dark' }}"
                                                            style="border-radius: 50%;">{{ $leave_applications }}</span>
                                                    </span>
                                                </div>
                                            </a>
                                        </li>
                                    @endif
                                @endif

                                @if (hasPermission('l.applications.approve', $slugs))
                                    @if ($approveCount > 0)
                                        <li>
                                            <a href="{{ route('hrm.pending.approved') }}">
                                                <div class="clearfix">
                                                    <span class="pull-left dark">
                                                        Leave Approve
                                                    </span>
                                                    <span class="pull-right">
                                                        <span
                                                            class="badge {{ $approveCount > 0 ? 'badge-danger' : 'badge-dark' }}"
                                                            style="border-radius: 50%;">{{ $approveCount }}</span>
                                                    </span>
                                                </div>
                                            </a>
                                        </li>
                                    @endif
                                @endif

                                @if (hasPermission('short.leave.applications.recommend', $slugs))
                                    @if ($short_leave_applications > 0)
                                        <li>
                                            <a href="{{ route('short-leave-application.index') }}">
                                                <div class="clearfix">
                                                    <span class="pull-left dark">
                                                        Short Leave Application
                                                    </span>
                                                    <span class="pull-right">
                                                        <span
                                                            class="badge {{ $short_leave_applications > 0 ? 'badge-danger' : 'badge-dark' }}"
                                                            style="border-radius: 50%;">{{ $short_leave_applications }}</span>
                                                    </span>
                                                </div>
                                            </a>
                                        </li>
                                    @endif
                                @endif

                                @if (hasPermission('purchases.approve', $slugs))
                                    @if ($purchaseApprove > 0)
                                        <li class="bg-info">
                                            <a href="{{ route('purchases.index') }}">
                                                <div class="clearfix">
                                                    <span class="pull-left dark">
                                                        GS Purchase
                                                    </span>
                                                    <span class="pull-right">
                                                        <span
                                                            class="badge {{ $purchaseApprove > 0 ? 'badge-danger' : 'badge-dark' }}"
                                                            style="border-radius: 50%;">{{ $purchaseApprove }}</span>
                                                    </span>
                                                </div>
                                            </a>
                                        </li>
                                    @endif
                                @endif

                                @if (hasPermission('create.requisitions.approve', $slugs))
                                    @if ($requsitionApprove > 0)
                                        <li>
                                            <a href="{{ route('goods-requisitions.index') }}">
                                                <div class="clearfix">
                                                    <span class="pull-left dark">
                                                        GS Requisition
                                                    </span>
                                                    <span class="pull-right">
                                                        <span
                                                            class="badge {{ $requsitionApprove > 0 ? 'badge-danger' : 'badge-dark' }}"
                                                            style="border-radius: 50%;">{{ $requsitionApprove }}</span>
                                                    </span>
                                                </div>
                                            </a>
                                        </li>
                                    @endif
                                @endif
                            </ul>
                        </li>

                    </ul>
                </li>

                {{-- Leave Application Notification End --}}




                <li class="light-10 dropdown-modal">
                    <a data-toggle="dropdown" href="#" class="dropdown-toggle dark">
                        @if (auth()->user()->employee)
                            @if (auth()->user()->employee->image != 'default.png')
                                <img class="nav-user-photo" style="height:40px; width:40px"
                                    src="{{ asset(auth()->user()->employee->image) }}" />
                            @else
                                <img class="nav-user-photo" src="{{ asset('default-user.png') }}" />
                            @endif
                        @else
                            <img class="nav-user-photo" src="{{ asset('default-user.png') }}" />
                        @endif

                        <span class="user-info">
                            <small>Welcome,</small>
                            {{ auth()->user()->name }}
                        </span>

                        <i class="ace-icon dark fa fa-caret-down"></i>
                    </a>

                    <ul
                        class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">

                        <li>
                            <a href="{{ route('user.password.edit') }}">
                                <i class="ace-icon fa fa-user"></i>
                                Change Password
                            </a>
                        </li>

                        <li class="divider"></li>

                        <li>
                            <a href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                <i class="ace-icon fa fa-power-off"></i>
                                Logout
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                style="display: none;">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>

    </div><!-- /.navbar-container -->
</div>
