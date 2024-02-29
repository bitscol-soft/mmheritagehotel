<?php 

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;

class ApiDashboardController extends Controller
{

    public function index()
    {
        try {
          
            $data['status'] = true;
            $data['message'] = '';
            
            
            $employee = auth()->user()->employee;

            $attendance = $employee->attendances->where('date', date('Y-m-d'))->first();

            $shift = optional($employee->schedule
                            ->where('employee_id', $employee->id)
                            ->where('from_date', '>=', date('Y-m-d'))
                            ->where('from_date', '<=', date('Y-m-d'))->first())->shift 
                            ?? optional(optional($employee->active_employment)->company)->shift;

            $data['data'] = [
                'profile_image'     => optional($employee)->image,
                'name'              => optional($employee)->name,
                'designation'       => optional($employee)->getDesignationName(),
                'emp_id'            => optional($employee)->employee_full_id,
                'email'             => optional($employee)->email,
                'shift_in_time'     => optional($shift)->in_start . ' - ' . optional($shift)->in_end,
                'shift_out_time'    => optional($shift)->out_start . ' - ' . optional($shift)->out_end,
                'attend_in_time'    => optional($attendance)->check_in_time ? fdate(optional($attendance)->check_in_time, 'h:i:s A') : '',
                'attend_out_time'   => optional($attendance)->check_out_time ? fdate(optional($attendance)->check_out_time, 'h:i:s A') : '',
            ];
          
        } catch (\Exception $ex) {
            
            $data['status']     = false;
            $data['message']    = $ex->getMessage();
            $data['data']       = [];
        }

        return $data;
    }



    public function allUserList()
    {
        try {
            
            $users = User::whereHas('employee')
                    ->with(['employee' => function($q) {
                        $q->select('name', 'id')->with(['attendances' => function($qr) {
                            $qr->whereHas('mobile_ttendance')->with('mobile_ttendance');
                        }]);
                    }])
                    ->get()->map(function($item) {
                        return [
                            'id'            => $item->id,
                            'device_token'  => $item->device_token,
                            'employee_id'   => optional($item->employee)->employee_full_id,
                            'name'          => optional($item->employee)->name,
                            'designation'   => optional($item->employee)->getDesignationName(),
                            'image'         => optional($item->employee)->image,
                            'last_location' => optional(optional(optional($item->employee)->attendances->first())->mobile_ttendance)->location_in,
                            'latitude'      => optional(optional(optional($item->employee)->attendances->first())->mobile_ttendance)->current_latitude,
                            'longitude'      => optional(optional(optional($item->employee)->attendances->first())->mobile_ttendance)->current_longitude,
                        ];
                    });
                
            $data['status'] = true;
            $data['message'] = '';
            $data['data'] = $users;

        } catch (\Exception $ex) {
            $data['status'] = false;
            $data['message'] = $ex->getMessage();
            $data['data'] = [];
        }
        return $data;
    }
}