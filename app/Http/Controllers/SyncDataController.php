<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Module\HRM\Models\Employee\Employee;
use Module\HRM\Models\Sync\EmployeeAttendanceSyncData;
use Module\HRM\Services\AttendanceService;
use Module\HRM\Services\AttendanceServiceV2;

class SyncDataController extends Controller
{





    //--------------------------------------------------------------------------
    //                          SYNC DATA METHOD
    //--------------------------------------------------------------------------
    public function syncData()
    {

        if (! class_exists(\Module\HRM\Models\Employee\Employee::class)) {
            abort(404, 'HRM module is not installed.');
        }


        $data['activeEmployeeCount']    = Employee::active()->count();
        $data['employees']              = Employee::active()->permissionEmployment()->get(['employee_full_id', 'id', 'name']);

        return view('system.sync-data', $data);
    }





    //--------------------------------------------------------------------------
    //                          SYNC DATA V2 METHOD
    //--------------------------------------------------------------------------
    public function syncDataV2()
    {

        if (! class_exists(\Module\HRM\Models\Employee\Employee::class)) {
            abort(404, 'HRM module is not installed.');
        }

        // $employees = (new AttendanceServiceV2())->getEmployees(0, 50, '2022-08-03');

        // return (new AttendanceServiceV2())->setFallbackAttendances($employees, '2022-08-03', '2022-08-03');

        $data['activeEmployeeCount']    = Employee::active()->count();
        $data['employees']              = Employee::active()->get(['employee_full_id', 'id', 'name']);

        return view('system.sync-data-v2', $data);
    }


    //--------------------------------------------------------------------------
    //                          SYNC DATA PROCESS METHOD
    //--------------------------------------------------------------------------
    public function syncDataProcess()
    {

        if (! class_exists(\Module\HRM\Models\Employee\Employee::class)) {
            abort(404, 'HRM module is not installed.');
        }

        $sync_data                      = EmployeeAttendanceSyncData::query()->pending()->orderBy('date')->get()->groupBy('date');
        $data['total_pending']          = EmployeeAttendanceSyncData::query()->where('is_completed', 0)->count();
        $data['sync_datas']             = view('system._inc.rendering-sync-data', compact('sync_data'))->render();
        return $data;
    }





    //--------------------------------------------------------------------------
    //                          SYNC DATA FALLBACK METHOD
    //--------------------------------------------------------------------------
    public function syncAttendaceFallback(Request $request)
    {

        ini_set('max_execution_time', '0');

        $date = fdate($request->month ?? now());

        $from_date = fdate($date, 'Y-m') . '-01';

        $to_date = fdate($date, 'Y-m') . '-' . totalDaysInMonth($from_date);

        (new AttendanceService)->setFallbackAttendances($from_date, $to_date);

        return redirect()->back()->withMessage('Attendance Fallback Successfully Synced');
    }





    //--------------------------------------------------------------------------
    //                          SYNC MONTHLY SUMMARY DATA METHOD
    //--------------------------------------------------------------------------
    public function syncMonthlySummery(Request $request)
    {

        (new AttendanceService)->syncMonthlySummeryData();

        return redirect()->back()->withMessage('Monthly Summery Successfully Synced');
    }
}
