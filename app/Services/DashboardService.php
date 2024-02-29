<?php


namespace App\Services;

use Carbon\Carbon;
use App\Models\Company;
use Module\Hotel\Models\Rooms;
use Module\Hotel\Models\Booking;
use Module\HRM\Models\Department;
use Module\HRM\Models\Attendance\Shift;
use Module\HRM\Models\Employee\Employee;
use Module\HRM\Models\Attendance\Holiday;
use Module\HRM\Models\Attendance\Attendance;
use Module\HRM\Models\Attendance\OutsideWork;
use Module\HRM\Models\Leave\LeaveApplication;
use Module\HRM\Models\Salary\SalaryGenerated;
use Module\HRM\Models\Leave\ShortLeaveApplication;

class DashboardService
{
    public function getHRMData($permitted_company_ids)
    {



        if(!view()->exists('partials.sidebars.__sidebar_hrm')) {


            $data = $this->getDefaultHRMData();
            return $data;
        }


        $data['holidays'] = Holiday::where('company_id', auth()->user()->company_id)->latest('id')->get();


        $data['short_leave']                = ShortLeaveApplication::whereIn('company_id', $permitted_company_ids)->where('leave_day', date('Y-m-d'))->count();
        $data['total_employee_count']       = Employee::whereIn('company_id', $permitted_company_ids)->where('status', 1)->count();
        $data['today_attendance']           = Attendance::whereHas('employee', function ($q) use ($permitted_company_ids) {
                                                    $q->whereIn('company_id', $permitted_company_ids);
                                                })
                                                ->whereDate('check_in_time', date('Y-m-d'))
                                                ->count();
        $data['leave']                      = LeaveApplication::whereIn('company_id', $permitted_company_ids)
                                                ->where('from', '<=', date('Y-m-d'))
                                                ->where('to', '>=', date('Y-m-d'))
                                                ->count();

        $data['top_sheets']                 = SalaryGenerated::whereIn('company_id', $permitted_company_ids)->with('department:name,id', 'netTotalDeduction', 'netTotalGross')->where('company_id', auth()->user()->company_id)->where('month', Carbon::parse(now())->subMonth(1)->format(('Y-m')))->get();

        $data['companies']                  = Company::userCompanies();
        $data['departments']                = Department::select('id', 'name')
                                                ->withCount(['employees' => function ($q) use ($permitted_company_ids) {
                                                    $q->whereIn('company_id', $permitted_company_ids)->where('status', 1);
                                                }])
                                                ->orderBy('name')
                                                ->get();

        $data['shift_wise_attendances']     = Shift::where('company_id', auth()->user()->company_id)
                                                ->withCount(['employees' => function ($q) use ($permitted_company_ids) {
                                                    $q->whereIn('company_id', $permitted_company_ids)->whereHas('attendances', function ($qr) {
                                                        $qr->whereDate('check_in_time', date('Y-m-d'));
                                                    });
                                                }])
                                                ->get();

        $data['department_wise_attndances'] = Department::withCount(['employees' => function ($q) use ($permitted_company_ids) {
                                                    $q->whereIn('company_id', $permitted_company_ids)->whereHas('attendances', function ($qr) {
                                                        $qr->whereDate('check_in_time', date('Y-m-d'));
                                                    });
                                                }])
                                                ->get();


        $data['not_assign_shift']           = Employee::where('shift_id', null)
                                                ->whereIn('company_id', $permitted_company_ids)
                                                ->whereHas('attendances', function ($qr) {
                                                    $qr->whereDate('check_in_time', date('Y-m-d'));
                                                })
                                                ->count();

        $data['new_employees']              = Employee::whereIn('company_id', $permitted_company_ids)
                                                ->where('joining_date', '>=', Carbon::parse(now())->subMonth(1)->format('Y-m-d'))
                                                ->select('id', 'name', 'employee_full_id', 'department_id')
                                                ->with('department:name,id')
                                                ->get();

        $data['out_work']                   = OutsideWork::whereIn('company_id', $permitted_company_ids)->where('date', date('Y-m-d'))->count();

        return $data;

    }


    // public function getDefaultHRMData()
    // {

    //     // collection
    //     $data['department_wise_employees']  = collect([]);
    //     $data['new_employees']              = collect([]);
    //     $data['departments']                = collect([]);
    //     $data['companies']                  = collect([]);
    //     $data['top_sheets']                 = collect([]);
    //     $data['department_wise_attndances'] = collect([]);
    //     $data['shift_wise_attendances']     = collect([]);
    //     $data['holidays']                   = collect([]);



    //     // count
    //     $data['total_employee_count']   = 0;
    //     $data['today_attendance']       = 0;
    //     $data['leave']                  = 0;
    //     $data['short_leave']            = 0;
    //     $data['out_work']               = 0;
    //     $data['not_assign_shift']       = 0;

    //     return $data;
    // }


    public function getBookingData()
    {


        $this->removeAllCookieItems();


        $today                          = date('Y-m-d');
        $yesterday                      = date("Y-m-d", strtotime('-1 day', strtotime(today())));
        $last7Days                      = date("Y-m-d", strtotime('-7 days', strtotime($today)));
        $last15Days                     = date("Y-m-d", strtotime('-15 days', strtotime($today)));
        $firstDayOfLastMonth            = date("Y-m-01", strtotime("first day of previous month"));
        $last7DaysStart                 = date("Y-m-d", strtotime('-7 days', strtotime($today)));
        $last7DaysEnd                   = $today;


        /****************************************************
        *                     BOOKING DATA                  *
        ****************************************************/
        $data['today_booking']         = Booking::query()
                                            ->when(setting('report_with_night_audit') == 1, function ($query) {
                                                return $query->whereDoesntHave('nightClosing');
                                            })
                                            ->where('booking_date', $today)
                                            ->with('bookingDetails') //with room count start
                                            ->get()
                                            ->groupBy('id')
                                            ->map(function ($booking) {
                                                return count($booking->first()->bookingDetails
                                                                        ->pluck('room_id')->unique());
                                            })
                                            ->sum(); //with room count end
                                            // ->count();
        $data['yesterday_booking'] = Booking::query()
                                            ->when(setting('report_with_night_audit') == 1, function ($query) {
                                                return $query->whereHas('nightClosing');
                                            })
                                            ->where('booking_date', $yesterday)
                                            ->with('bookingDetails')
                                            ->get()
                                            ->groupBy('id')
                                            ->map(function ($booking) {
                                                return count($booking->first()->bookingDetails->pluck('room_id')->unique());
                                            })
                                            ->sum();
                                             //with room count end
                                                // ->count();

        $data['last_7_days_booking'] = Booking::query()
                                                ->when(setting('report_with_night_audit') == 1, function ($query) {
                                                    return $query->whereHas('nightClosing');
                                                })
                                                ->where('booking_date', '>=', $last7Days)
                                                ->where('booking_date', '<=', $today)
                                                ->with('bookingDetails') //with room count start
                                                ->get()
                                                ->groupBy('id')
                                                ->map(function ($booking) {
                                                    return count($booking->first()->bookingDetails
                                                                         ->pluck('room_id')->unique());
                                                })
                                                ->sum(); //with room count end
                                                // ->count();



        /****************************************************
        *                     CHECKING DATA                  *
        ****************************************************/


        // $data['today_checkin']         = Booking::query()->where('check_in_time', 'LIKE', "%".$today."%")
        //                                 ->where('status', 1)->count();

        $data['today_checkin']      = Booking::query()
                                                ->when(setting('report_with_night_audit') == 1, function ($query) {
                                                    return $query->whereDoesntHave('nightClosing');
                                                })
                                                ->where('check_in_time', 'LIKE', "%{$today}%")
                                                ->where('status', 1)
                                                ->with('bookingDetails')
                                                ->get()
                                                ->groupBy('id')
                                                ->map(function ($booking) {
                                                    return count($booking->first()->bookingDetails
                                                                         ->pluck('room_id')->unique());
                                                })
                                                ->sum();

        $data['yesterday_checkin'] = Booking::query()
                                                ->when(setting('report_with_night_audit') == 1, function ($query) {
                                                    return $query->whereHas('nightClosing');
                                                })
                                                ->where('check_in_time', 'LIKE', "%{$yesterday}%")
                                                // ->where('status', 3)
                                                ->with('bookingDetails')
                                                ->get()
                                                ->groupBy('id')
                                                ->map(function ($booking) {
                                                    return count($booking->first()->bookingDetails
                                                                         ->pluck('room_id')->unique());
                                                })
                                                ->sum();

        $data['last_7_days_checkin'] = Booking::query()
                                                ->when(setting('report_with_night_audit') == 1, function ($query) {
                                                    return $query->whereHas('nightClosing');
                                                })
                                                ->whereBetween('check_in_time', [$last7DaysStart, $last7DaysEnd])
                                                ->with('bookingDetails')
                                                ->get()
                                                ->groupBy('id')
                                                ->map(function ($booking) {
                                                    return count($booking->first()->bookingDetails
                                                                         ->pluck('room_id')->unique());
                                                })
                                                ->sum();

        // $data['yesterday_checkin']     = Booking::query()->where('check_in_date', $yesterday)->where('status', 1)->count();
        $data['total_checkin']         = Booking::query()->where('status',1)->count();





        /****************************************************
        *                     CHECKOUT DATA                  *
        ****************************************************/
        $data['today_checkout']        = Booking::whereDate('check_out_time',$today)
                                                ->when(setting('report_with_night_audit') == 1, function ($query) {
                                                    return $query->whereDoesntHave('nightClosing');
                                                })
                                                ->where('status', 3)
                                                ->with('bookingDetails')
                                                ->get()
                                                ->groupBy('id')
                                                ->map(function ($booking) {
                                                    return count($booking->first()->bookingDetails
                                                                         ->pluck('room_id')->unique());
                                                })
                                                ->sum();

        $data['yesterday_checkout']    = Booking::whereDate('check_out_time',$yesterday)
                                                ->when(setting('report_with_night_audit') == 1, function ($query) {
                                                    return $query->whereHas('nightClosing');
                                                })
                                                ->where('status', 3)
                                                ->with('bookingDetails')
                                                ->get()
                                                ->groupBy('id')
                                                ->map(function ($booking) {
                                                    return count($booking->first()->bookingDetails
                                                                         ->pluck('room_id')->unique());
                                                })
                                                ->sum();


        $data['last_7_days_checkout']  = Booking::query()
                                                ->when(setting('report_with_night_audit') == 1, function ($query) {
                                                    return $query->whereHas('nightClosing');
                                                })
                                                ->whereBetween('check_out_time', [$last7DaysStart, $last7DaysEnd])
                                                ->where('status', 3)
                                                ->with('bookingDetails')
                                                ->get()
                                                ->groupBy('id')
                                                ->map(function ($booking) {
                                                    return count($booking->first()->bookingDetails
                                                                         ->pluck('room_id')->unique());
                                                })
                                                ->sum();


        /****************************************************
        *                     TOTAL DATA                  *
        ****************************************************/
        $data['total_room']            =  Rooms::query()->where('status', 1)->count();
        $data['today_room_booked']     =  Rooms::whereHas('booking_details', fn($q) => $q->whereDate('created_at', today())->whereIn('status', [1, 2]))->count();
        $data['today_room_ready']      =  Rooms::query()->where('status', 1)
                                        ->where(function ($q) {
                                            $q->whereHas('booking_dates', fn ($q) => $q->whereNotIn('status', [3,4]))
                                            ->orWhereDoesntHave('booking_dates');
                                        })
                                        ->count();




        return $data;
    }




    private function removeAllCookieItems()
    {
        if (isset($_COOKIE['booking_info'])) {
            unset($_COOKIE['booking_info']);
            setcookie('booking_info', '', time() - 3600, '/'); // empty value and old timestamp
        }
    }
}
