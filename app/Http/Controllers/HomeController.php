<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Models\SystemSetting;
use App\Models\UserLoginStatus;
use App\Services\DashboardService;
use Illuminate\Support\Facades\DB;
use Module\HRM\Models\News\Notice;
use Module\HRM\Models\Salary\HrLoan;
use Module\Permission\Models\Module;
use Module\HRM\Models\Employee\Employee;
use Module\HRM\Models\Attendance\Holiday;
use Module\Hotel\Services\RoomStatusService;
use Module\HRM\Models\Attendance\Attendance;
use Module\HRM\Models\Leave\LeaveApplication;
use Module\HRM\Models\Attendance\OutsideWork;
use Module\HRM\Models\Leave\ShortLeaveApplication;
use Module\HRM\Models\Salary\SalaryGeneratedDetails;



class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function dashboard()
    {
        return $this->index(request());
    }






    public function index(Request $request)
    {

        $modules                = Module::active()->get();
        $dashboardService       = new DashboardService();
        $permitted_company_ids  = Company::userCompanyId();


        $data = $dashboardService->getBookingData();

        $dashboard  = SystemSetting::where('key', 'dashboard')->first()->value;

        if ($dashboard == '1') {
            if ($modules->where('name', 'HRM')->count() > 0) {
                return $this->getHrmDashboardData($request);
            }
        }

        if (auth()->user()->permissions()->count() < 1 && auth()->id() != 1) {
            return redirect()->route('em.dashboard');
        }


        $data['settings']          = SystemSetting::get();
        $data['logged_in_users']   = UserLoginStatus::whereIn('company_id', $permitted_company_ids)->has('user')->with('user')->get();

        if (setting('visible_booking_ui_dashboard') == 1) {
            $data['categories']    = $this->bookingUI($request);
        }

        $data['mix_date']          = fdate(today_from_system(),'m/d/Y') . ' - ' . Carbon::parse(today_from_system())->addDay()->format('m/d/Y');

        return view('home.hotel-dashboard', $data);
    }






    public function bookingUI(Request $request)
    {

        $check_in                   = fdate(today_from_system(), 'Y-m-d');
        $check_out                  = Carbon::parse(today_from_system())->addDay()->format('Y-m-d');

        $data['mix_date']           = $check_in . ' - ' . $check_out;


        if ($request->filled('booking_date')) {
            $date       = explode('-', $request->booking_date);
            $check_in   = Carbon::parse(trim($date[0]))->format('Y-m-d');
            $check_out  = Carbon::parse(trim($date[1]))->format('Y-m-d');
        }

        return (new RoomStatusService())->availableRoom($check_in, $check_out);

    }

    public function getHrLoans()
    {
        $loans = HrLoan::orderByDesc('id')
            ->where('employee_id', auth()->user()->employee_id)
            ->withCount(['loan_details as paid_amount' => function ($q) {
                $q->where('status', 1)->select(DB::Raw('SUM(installment_amount)'));
            }])
            ->where('approval_receive_on', '<=', date('Y-m-d'))
            ->get();

        $data['total_amount'] = optional($loans)->sum('amount') ?? 0;
        $data['paid_amount'] = optional($loans)->sum('paid_amount') ?? 0;

        return $data;
    }


    public function getShortLeaves()
    {
        $year = Date('Y');
        // $year = 2020;

        $data['adjusted'] = ShortLeaveApplication::where('employee_id', auth()->user()->employee_id)->where('adjust', 1)->where('year', $year)->count();
        $data['availed'] = ShortLeaveApplication::where('employee_id', auth()->user()->employee_id)->where('adjust', 0)->where('year', $year)->count();

        return $data;
    }

    public function getPayslip()
    {
        return SalaryGeneratedDetails::where('employee_id', auth()->user()->employee_id)->orderByDesc('month')->first();
    }


    public function getHrmDashboardData($permitted_company_ids)
    {

        $joining_date   = Carbon::parse(now())->subMonth(1)->format('Y-m-d');
        $companies      = Company::CompanyByShort();
        $employees      = $this->employees();

        $oursideWorkQuery = OutsideWork::query()->where('date', date('Y-m-d'))->get();

        $leave_application_query = $this->leave();


        foreach ($companies as $key => $value) {


            $data['companies'][]                    = $value;
            $data['company_ids'][]                  = $key;
            $data['total_employees'][]              = $employees->where('active_employment.company_id',$key)->count();
            $data['today_attendance'][]             = $employees->where('company_id', $key)->where('today_attend', '>', 0)->count();
            $data['out_work'][]                     = $oursideWorkQuery->where('company_id', $key)->count();
            $data['today_leave'][]                  = $leave_application_query->where('company_id', $key)->count();
        }


        $data['new_employees']                      = $employees->where('joining_date', '>=', $joining_date);

        $data['monthly_attendance']                 = $this->monthlyAttendance();




        $data['notice']                             = Notice::orderByDesc('id')
                                                        ->where('expire_at', '>=', fdate(now(), 'Y-m-d H:i:s'))
                                                        ->where('publish_at', '<=', fdate(now(), 'Y-m-d H:i:s'))
                                                        ->take(10)
                                                        ->pluck('title', 'id');
        $data['settings']                           = SystemSetting::get();
        $data['holidays']                           = Holiday::where('company_id', auth()->user()->company_id)->latest('id')->get();

        return view('home.hrm-dashboard-new', $data);
    }








    public function getLatestNotices()
    {
        return Notice::where('publish_at', '<=', fdate(now(), 'Y-m-d H:i:s'))
            ->where('expire_at', '>', fdate(now(), 'Y-m-d H:i:s'))
            ->whereDoesntHave('all_views', function ($q) {
                $q->where('user_id', auth()->id());
            })
            ->orderByDesc('publish_at')
            ->get();
    }






    /**
     * --------------------------------------------------
     * TODAY ATTENDANCE
     * --------------------------------------------------
     */
    public function employees()
    {

        return Employee::query()
            ->permissionEmployment()
            ->active()
            ->employment()
            ->with('department:name,id')
            ->withCount(['attendances as today_attend' => function ($q) {
                $q->where('date', date('Y-m-d'));
            }])
            ->get();
    }






    /**
     * --------------------------------------------------
     * LEAVE APPLICATION METHOD
     * --------------------------------------------------
     */
    public function leave()
    {
        return LeaveApplication::where('from', '>=', date('Y-m-d'))->where('to', '<=', date('Y-m-d'))->get();
    }






    /**
     * --------------------------------------------------
     * MONTHLY EMPLOYEE ATTENDANCE COUNT METHOD
     * --------------------------------------------------
     */
    public function monthlyAttendance()
    {

        $firstday = Carbon::parse(now())->startOfMonth()->format('Y-m-d');
        $lastday  = Carbon::parse(now())->endOfMonth()->format('Y-m-d');

        return Attendance::where('date', '>=', $firstday)->where('date', '<=', $lastday)->select('id','company_id','date')->get();

    }

}
