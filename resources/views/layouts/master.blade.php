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

@endphp




<!DOCTYPE html>


<html lang="en">


@include('layouts.includes.head')




<body class="no-skin" style="font-family: 'Fira Sans', sans-serif;">



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
        <div class="main-content">

            <div class="main-content-inner" @if ($dashboard && (request()->is('/') || request()->is('home'))) style="background: #f2f2f2" @endif>

                <div class="page-content" @if ($dashboard && (request()->is('/') || request()->is('home')))  style="background: transparent; padding-bottom: 0;" @endif>






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







    <!-- delete form -->
    <form action="" id="deleteItemForm" method="POST">
        @csrf @method("DELETE")
    </form>

</body>

</html>
