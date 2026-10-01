
@php
    $notificationService = new App\Services\AppNotificationService();

    $systemSettings = App\Models\SystemSetting::whereIn('key', ['topbar_background_color', 'topbar_text_color'])->get();

    $bg_color = $systemSettings->where('key', 'topbar_background_color')->first()->value ?? '#dfe2cd' ;
    $text_color = ($systemSettings->where('key', 'topbar_text_color')->first()->value ?? '#478FCA') . ' !important';


@endphp

<style>
    .navbar {
        background-color: {{ $bg_color }} !important;
        /* background-color: #ccc !important; */
    }
    .navbar-header-title{
        /* color: #28282B !important; */
    }
    .hotel-title-name{
        font-size: 34px
    }

    .topbar-text-color {
        color: {{ $text_color }};
    }
    .navbar .navbar-brand {
        color: #FFF;
        font-size: 24px;
        text-shadow: none;
        padding-top: 0px;
        padding-bottom: 0px;
        height: auto;
    }
    @media (max-width:575px){
        .hotel-title-name{
            font-size: 24px;
        }
        .navbar .navbar-toggle {
            height: 30px;
        }
        .notification-dropdown a {
            min-width: 40px !important;
        }
        .notification-dropdown i{
            margin-top: 0 !important;
        }
    }
    @media (min-width:576px) and (max-width:767px){
        .hotel-title-name{
            font-size: 24px;
        }
        .navbar .navbar-toggle {
            height: 30px;
        }
        .notification-dropdown a {
            min-width: 40px !important;
        }
        .notification-dropdown i{
            margin-top: 0 !important;
        }
    }
    @media (min-width:768px) and (max-width:991px){}
    @media (min-width:992px) and (max-width:1199px){}
    @media (min-width:1200px){}


        .colorful-border {
                border: 2px solid transparent;
                transition: border-color 0.3s, width 0.3s, height 0.3s;
            }

        .colorful-border:hover {
                border-color: #ff0000;
                background-color: #f0f0f0;
            }

        .colorful-border:hover {
                border-color: rgb(
                    <?php echo rand(0, 255) ?>,
                    <?php echo rand(0, 255) ?>,
                    <?php echo rand(0, 255) ?>
                );
            }


</style>

<div id="navbar" class="navbar navbar-default ace-save-state navbar-fixed-top">
    <div class="navbar-container ace-save-state" id="navbar-container">
        <button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">
            <span class="sr-only">Toggle sidebar</span>

            <span class="icon-bar"></span>

            <span class="icon-bar"></span>

            <span class="icon-bar"></span>
        </button>

        <div class="navbar-header pull-left">
            <a href="{{ url('home') }}" class="navbar-brand" style="font-size:60px">
                <small class="text-primary font-weight-bold" style="font-weight: 700">

                    @if(file_exists('uploads/group/'. optional(optional(optional(auth()->user())->company)->group)->logo))
                        <img style="height: 50px !important" class="logo" src="{{ asset('uploads/group/'. optional(optional(optional(auth()->user())->company)->group)->logo) }}" alt="">
                    @else
                        <span class="white navbar-header-title hotel-title-name" style="font-size:34px">

                            {{ optional(optional(optional(auth()->user())->company)->group)->name }}
                        </span>
                    @endif
                </small>
            </a>
        </div>

        <div class="navbar-buttons navbar-header pull-right" role="navigation">
            <ul class="nav ace-nav">

                <!-- Booking -->
                {{-- <li class="light-10 dropdown-modal" title="Booking">
                    <a href="{{ route('rst.sales-v2.create') }}">
                       <i class="fa fa-2x fa-refresh dark" style="margin-top: 10px;">
                        Booking
                    </i>
                   </a>
                </li> --}}

                    <!-- Rst Sale -->
                    <li class="light-10 colorful-border" title="Rst Sale">
                        <a href="{{ route('rst.sales-v2.create') }}">
                            <i class="fa fa-2x fa fa-cutlery dark" style="margin-top: 7px;">
                                RST
                            </i>
                        </a>
                    </li>

                    <!-- Bar Sale -->
                    <li class="light-10 colorful-border" title="Bar Sale">
                        <a href="{{ route('bar.sales-v2.create') }}">
                            <i class="fa-2x fa fa-glass dark" style="margin-top: 7px;">
                                BAR
                            </i>
                        </a>
                    </li>

                {{-- <li style="padding-left: 10px"></li> --}}
                <!-- optimizeClear -->
                <li class="light-10 dropdown-modal" title="Optimize Clear">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="javascript:void(0)" onclick="optimizeClear()">
                       <i class="fa fa-2x fa-refresh dark" style="margin-top: 10px;"></i>
                   </a>
                </li>


                <li class="light-10 dropdown-modal notification-dropdown" title="Recommend Notifications">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <i class="fa fa-2x fa-bell dark" style="margin-top: 10px;"></i>
                        @if (optional($notificationService)->totalNotificationCount > 0)
                            <sup style="color: white;font-size: 12px;margin-left: -16px;background-color: red;padding: 2px;border-radius: 50%;">
                                <b>{{ optional($notificationService)->totalNotificationCount }}</b>
                            </sup>
                        @endif

                    </a>

                    <ul class="dropdown-menu-right dropdown-navbar navbar-default dropdown-menu dropdown-caret dropdown-close">
                        <li class="dropdown-header">
                            <i class="ace-icon fa fa-bell-o"></i>
                            {{ optional($notificationService)->totalNotificationCount }}
                            Total Notifications
                        </li>

                        <li class="dropdown-content">
                            <ul class="dropdown-menu dropdown-navbar navbar-default">


                                <!-- Leave Recommend Count -->
                                @if (hasPermission("l.applications.recommend", $slugs))
                                    @if (optional($notificationService)->leave_recommend_count > 0)
                                        <li>
                                            <a href="{{ route('hrm.pending.recommend') }}">
                                                <div class="clearfix">
                                                    <span class="pull-left dark">
                                                        Leave Recommend
                                                    </span>
                                                    <span class="pull-right">
                                                    <span class="badge badge-danger" style="border-radius: 50%;">{{ optional($notificationService)->leave_recommend_count }}</span>
                                                </span>
                                                </div>
                                            </a>
                                        </li>
                                    @endif
                                @endif


                                <!-- Training Approval Count -->
                                {{-- @if (hasPermission("training.approve", $slugs)) --}}
                                    @if (optional($notificationService)->training_approval_count > 0)
                                        <li>
                                            <a href="{{ route('trainings.index',['is_approve'=> 1]) }}">
                                                <div class="clearfix">
                                                    <span class="pull-left dark">
                                                        Training Approval
                                                    </span>
                                                    <span class="pull-right">
                                                    <span class="badge badge-danger" style="border-radius: 50%;">{{ $notificationService->training_approval_count }}</span>
                                                </span>
                                                </div>
                                            </a>
                                        </li>
                                    @endif
                                {{-- @endif --}}

                                <!-- Leave Approve Count -->
                                @if (hasPermission("l.applications.approve", $slugs))
                                    @if (optional($notificationService)->earn_leave_approval_count > 0)
                                        <li>
                                            <a href="{{ route('earn-leaves.pending') }}">
                                                <div class="clearfix">
                                                    <span class="pull-left dark">
                                                        Earn Leave Approve
                                                    </span>
                                                    <span class="pull-right">
                                                    <span class="badge badge-danger" style="border-radius: 50%;">{{ $notificationService->earn_leave_approval_count }}</span>
                                                </span>
                                                </div>
                                            </a>
                                        </li>
                                    @endif
                                @endif

                                <!-- Leave Approve Count -->
                                @if (hasPermission("l.applications.approve", $slugs))
                                    @if (optional($notificationService)->leave_approve_count > 0)
                                        <li>
                                            <a href="{{ route('hrm.pending.approved') }}">
                                                <div class="clearfix">
                                                    <span class="pull-left dark">
                                                        Leave Approve
                                                    </span>
                                                    <span class="pull-right">
                                                    <span class="badge badge-danger" style="border-radius: 50%;">{{ optional($notificationService)->leave_approve_count }}</span>
                                                </span>
                                                </div>
                                            </a>
                                        </li>
                                    @endif
                                @endif


                                <!-- Short Leave Application Count -->
                                @if (hasPermission("short.leave.applications.recommend", $slugs))
                                    @if (optional($notificationService)->short_leave_application_count > 0)
                                        <li>
                                            <a href="{{ route('short-leave-application-recent') }}?unseen=0">
                                                <div class="clearfix">
                                                    <span class="pull-left dark">
                                                        Short Leave Application
                                                    </span>
                                                    <span class="pull-right">
                                                    <span class="badge badge-danger" style="border-radius: 50%;">{{ optional($notificationService)->short_leave_application_count }}</span>
                                                </span>
                                                </div>
                                            </a>
                                        </li>
                                    @endif
                                @endif

                                @if (optional($notificationService)->leave_application_count > 0)
                                    <li>
                                        <a href="{{ request()->segment(1) == 'em' ? route('em.approved-leave-application') : route('approved-leave-application') }}">
                                            <div class="clearfix">
                                                <span class="pull-left dark">
                                                    Leave Application
                                                </span>
                                                <span class="pull-right">
                                                    <span class="badge badge-danger" style="border-radius: 50%;">{{ optional($notificationService)->leave_application_count }}</span>
                                                </span>
                                            </div>
                                        </a>
                                    </li>
                                @endif

                                @if (hasPermission("out.works.approve", $slugs))
                                    @if (optional($notificationService)->out_of_works_count > 0)
                                        <li class="bg-info">
                                            <a href="{{ route('out-work.index',['is_not_approved' => '1']) }}">
                                                <div class="clearfix">
                                                    <span class="pull-left dark">
                                                        Outside Work
                                                    </span>
                                                    <span class="pull-right">
                                                    <span class="badge badge-danger" style="border-radius: 50%;">{{ optional($notificationService)->out_of_works_count }}</span>
                                                </span>
                                                </div>
                                            </a>
                                        </li>
                                    @endif
                                @endif






                                @if (hasPermission("purchases.approve", $slugs) && file_exists(base_path() . '/module/GeneralStore/routes/web_generalstore.php'))
                                    @if (optional($notificationService)->gs_purchase_approve_count > 0)
                                        <li class="bg-info">
                                            <a href="{{ route('purchases.index') }}?is_not_approved=1">
                                                <div class="clearfix">
                                                    <span class="pull-left dark">
                                                        GS Purchase
                                                    </span>
                                                    <span class="pull-right">
                                                    <span class="badge badge-danger" style="border-radius: 50%;">{{ optional($notificationService)->gs_purchase_approve_count }}</span>
                                                </span>
                                                </div>
                                            </a>
                                        </li>
                                    @endif
                                @endif

                                @if (hasPermission("create.requisitions.approve", $slugs) && file_exists(base_path() . '/module/GeneralStore/routes/web_generalstore.php'))
                                    @if (optional($notificationService)->gs_requisition_approve_count > 0)
                                        <li>
                                            <a href="{{ route('goods-requisitions.index') }}?is_not_approved=1">
                                                <div class="clearfix">
                                                    <span class="pull-left dark">
                                                        GS Requisition
                                                    </span>
                                                    <span class="pull-right">
                                                    <span class="badge badge-danger" style="border-radius: 50%;">{{ optional($notificationService)->gs_requisition_approve_count }}</span>
                                                </span>
                                                </div>
                                            </a>
                                        </li>
                                    @endif
                                @endif


                                @if(optional($notificationService)->news_notification_count > 0)
                                    <li class="bg-info">
                                        <a href="{{ route('notices.index') }}">
                                            <div class="clearfix">
                                                <span class="pull-left dark">Notice</span>
                                                <span class="pull-right">
                                                <span class="badge badge-danger" style="border-radius: 50%;">{{ optional($notificationService)->news_notification_count }}</span>
                                            </span>
                                            </div>
                                        </a>
                                    </li>
                                @endif

                            </ul>
                        </li>

                    </ul>
                </li>

                <li class="light-10 dropdown-modal"

                    @if(strlen(optional(auth()->user())->name) >= 10)
                        style="width: 350x"
                    @endif
                >
                    <a data-toggle="dropdown" href="#" class="dropdown-toggle dark">
                        @if (optional(auth()->user())->employee)
                            @if (auth()->user()->employee->image != 'default.png')
                                <img class="nav-user-photo" style="height:40px; width:40px" src="{{ asset(auth()->user()->employee->image) }}" alt="User Photo" />
                            @else
                                <img class="nav-user-photo" src="{{ asset('default-user.png') }}" alt="User Photo" />
                            @endif
                        @else
                            <img class="nav-user-photo" src="{{ asset('default-user.png') }}" alt="User Photo" />
                        @endif

                        <span class="user-info">
                            <small>Welcome,</small>
                            {{ optional(auth()->user())->name }}
                        </span>

                        <i class="ace-icon dark fa fa-caret-down"></i>
                    </a>


                    <ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">

                        <li>
                            <a href="{{ route('user.password.edit') }}">
                                <i class="ace-icon fa fa-user"></i>
                                Change Password
                            </a>
                        </li>

                        <li class="divider"></li>

                        <li>
                            <a href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                <i class="ace-icon fa fa-power-off"></i>
                                Logout
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>

    </div>
</div>
