<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Module\HRM\Services\AttendanceService;
use App\Events\EmployeeAttendanceSyncEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Module\HRM\Services\AttendanceServiceV2;

class EmployeeAttendanceSyncListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public $attendanceServiceV2;

    public function __construct()
    {

        $this->attendanceServiceV2  =  new AttendanceServiceV2();
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(EmployeeAttendanceSyncEvent $event)
    {

        try {
            $sync_data = $event->sync_data;

            $request = request();

            $request->limit  = $sync_data->skip_from;
            $date            = $sync_data->date;

            $employees = $this->attendanceServiceV2->getEmployees($sync_data->skip_from, 50, $date);

            if ($sync_data->schedule_sync == 1) {
                $this->attendanceServiceV2->setFallbackAttendances($employees, $date, $date);
                $this->attendanceServiceV2->syncScheduleDataByDate($employees, $date);
            }

            if ($sync_data->holiday_sync == 1) {
                $this->attendanceServiceV2->setDayWiseHolidayAttendances($employees, $date);
            }

            if ($sync_data->leave_sync == 1) {
                $this->attendanceServiceV2->setDayWiseLeaveAttendances($employees, $date);
            }
            

            if ($sync_data->attendance_sync == 1) {
                $response = $this->attendanceServiceV2->syncDayToDayAttendance($employees, $date);
            }
            
            
            $sync_data->update([
                'is_completed'      => 1,
                'total_employees'   => $response['affected_count'] ?? 0,
                'employee_ids'      => serialize($response['employee_ids'] ?? []),
                'remarks'           => 'success',
            ]);
        } catch (\Throwable $th) {
           $sync_data->update([
            'remarks' => $th->getMessage()
        ]);
        }



        return response()->json($response);
    }
}
