@php

$group = App\Models\Group::first();

$fav_icon = file_exists($group->fav_icon) ? asset($group->fav_icon) : '/icon.png';

$dashboard = App\Models\SystemSetting::where('key', 'dashboard')
    ->where('value', 1)
    ->first();

$isAdminHeader = !request()->is('hrm/payroll/master-salary/*') && !request()->is('hrm/payroll/bank-salary/*') && !request()->is('hrm/payroll/cash-salary/*') && !request()->is('hrm/payroll/master-salary-without-payslip/*') && !request()->is('hrm/payroll/master-salary-with-payslip/*') && !request()->is('hrm/bonus/fixed/bonus/details/*') && !request()->is('*/sales-v2*') && request()->segment(1) != 'em';

$isEmployeeHeader = request()->segment(1) == 'em';

$isAdminSidebar = !request()->is('hrm/payroll/master-salary/*') && !request()->is('hrm/payroll/bank-salary/*') && !request()->is('hrm/payroll/cash-salary/*') && !request()->is('hrm/payroll/master-salary-without-payslip/*') && !request()->is('hrm/payroll/master-salary-with-payslip/*') && !request()->is('hrm/bonus/fixed/bonus/details/*') && !request()->is('*/sales-v2*') && request()->segment(1) !== 'em';

$isShowFooter = !request()->is('hrm/payroll/master-salary/*') && !request()->is('hrm/payroll/bank-salary/*') && !request()->is('hrm/payroll/cash-salary/*') && !request()->is('hrm/payroll/master-salary-without-payslip/*') && !request()->is('hrm/payroll/master-salary-with-payslip/*');

$mmShell = config('ui.admin_shell', true) && $isAdminHeader && $isAdminSidebar;

@endphp




<!DOCTYPE html>


<html lang="en">


@include('layouts.includes.head')




<body class="no-skin{{ $mmShell ? ' mm-shell' : '' }}" style="font-family: 'Fira Sans', sans-serif;">



    @if ($mmShell)
        <a href="#mm-main-content" class="mm-shell-skip">Skip to content</a>
        <button type="button" class="mm-shell-backdrop" data-mm-close aria-label="Close navigation" hidden></button>
    @endif
    <!-- header -->
    @if ($isAdminHeader)

        @include('partials._header')

    @elseif($isEmployeeHeader)

        @include('partials._em._header')

    @endif


    <div class="main-container ace-save-state" id="main-container">


        <input type="hidden" class="sidebar-type" value="{{ request()->segment(1) }}">




        <!-- sidebar -->
        @if ($isAdminSidebar)

            @include('partials._sidebar')

        @elseif(request()->segment(1) == 'em')

            @include('partials._em._sidebar')

        @endif








        <!-- main content -->
        <div class="main-content" @if ($mmShell) id="mm-main-content" tabindex="-1" @endif>

            <div class="main-content-inner" @if ($dashboard && (request()->is('/') || request()->is('home'))) style="background: #f2f2f2" @endif>

                <div class="page-content" @if ($dashboard && (request()->is('/') || request()->is('home')))  style="background: transparent; padding-bottom: 0;" @endif>






                    @if ($mmShell)
                        @include('layouts.shell.toolbar')
                    @endif
                    <!-- MAIN / DYNAMIC CONTENT -->
                    @yield('content', 'Default Content')





                </div>
            </div>
        </div>





        <!-- footer -->
        @if ($isShowFooter)

            @include('partials._footer')

        @endif

    </div>





    <!-- master file script -->
    @include('layouts.includes.master-file-script')
    @if ($mmShell)
        <script src="{{ asset('assets/custom_js/shell.js') }}?v={{ filemtime(public_path('assets/custom_js/shell.js')) }}"></script>
    @endif







    <!-- delete form -->
    <form action="" id="deleteItemForm" method="POST">
        @csrf @method("DELETE")
    </form>

</body>

</html>
