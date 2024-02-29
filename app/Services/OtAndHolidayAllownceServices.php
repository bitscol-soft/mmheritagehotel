<?php


namespace App\Services;


use Module\HRM\Models\Attendance\Holiday;
use App\Models\Company;
use Module\HRM\Models\Department;
use Module\HRM\Models\Designation;
use Module\HRM\Models\Employee\Employee;




use Module\HRM\Models\Salary\Disbursement;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Module\HRM\Models\Ot\OtAndHoliday;
use Module\HRM\Models\Ot\OtAndHolidayAllowance;
use Module\HRM\Models\Ot\OtHolidayHour;
use Module\HRM\Models\Ot\OtShowHoliday;
use Module\HRM\Models\Salary\OtConfig;

class OtAndHolidayAllownceServices
{
    public function indexData($request)
    {
        $data = [];
        $data['companies']         = Company::userCompanies();
        $data['departments']       = Department::userDepartments();
        $data['designations']      = Designation::userDesignations();

        $data['OtAndHolidays'] = OtAndHoliday::with('otAndHolidayAllowance', 'otShowHoliday', 'company', 'department', 'designation');

        if ($request->filled('company')){
            $data['OtAndHolidays']->where('company_id', $request->company);
        }

        if ($request->filled('department')){
            $data['OtAndHolidays']->where('department_id', $request->department);
        }


        if ($request->filled('designation')){
            $data['OtAndHolidays']->where('designation_id', $request->designation);
        }


        if ($request->filled('employee_id')){
            $data['OtAndHolidays']->where('otAndHolidayAllowance', function ($q) use($request){
                $q->whereHas('employee', function ($sQ) use($request){
                    $sQ->where('employee_full_id', $request->employee_id);
                });
            });
        }

        if ($request->filled('month')){
            $data['OtAndHolidays']->where('effected_month', $request->month);
        }


        $data['OtAndHolidays'] = $data['OtAndHolidays']->paginate(30);


        return $data;
    }

    public function createData($request)
    {
        $data['companies']         = Company::userCompanies();
        $data['departments']       = Department::userDepartments();
        $data['designations']      = Designation::userDesignations();

        $data['employees'] = Employee::with('company', 'department', 'designation','salary', 'salary.bank_salary', 'salary.cash_salary')
            ->where('status', 1)
            ->whereHas('salary');

        if ($request->filled('company') && $request->filled('department') && $request->filled('month')){

            $month = $request->month;
            $firstDate = Carbon::parse($month)->firstOfMonth();
            $lastDate = Carbon::parse($month)->lastOfMonth();
            $data['totalMonthDays'] = Carbon::parse($month)->daysInMonth;

            $data['employees']->where('company_id', $request->company)
                ->where('department_id', $request->department)
                ->with(['attendances' => function ($q) use ($firstDate, $lastDate) {
                    $q->whereBetween('date', [$firstDate, $lastDate]);
                }])
                ->with(['schedule' => function($q){
                    $q->distinct('shift_id')->select('employee_id', 'shift_id');
                }])
                ->whereDoesntHave('otHolidayAndTiffins', function ($q) use($request){
                    $q->where('effected_month', $request->month);
                })
            ;

            $data['otConfig'] = OtConfig::where('company_id', $request->company)->first();

            $data['weekends'] = $this->getWeeklyHolidayDate($request->company, $request->month);
            $data['dayToDays'] = $this->getDayToDayHolidayDate($request->company, $request->month);
            $data['singleDays'] = $this->getSingleDayHolidayDate($request->company, $request->month);
        }

        if ($request->filled('designation')){
            $data['employees']->where('designation_id', $request->designation);
        }

        if ($request->filled('employee_id')){
            $data['employees']->where('employee_full_id', $request->employee_id);
        }

        if ($request->filled('company') && $request->filled('department') && $request->filled('month')){

            $data['employees'] = $data['employees']->paginate(20);
        }


        return $data;

    }

    public function storeData($request)
    {
        $otAndHolidayId = $this->storeOtAndHoliday($request);

        $this->storeOtHolidayShow($request, $otAndHolidayId);

        $this->storeHolidayHouresAndAllowance($request, $otAndHolidayId);
    }

    private function storeOtAndHoliday($request)
    {
        $otAndHolidayId = OtAndHoliday::create([

            'company_id' => $request->company,
            'department_id' => $request->department,
            'designation_id' => $request->designation,
            'effected_month' => $request->effected_month,

        ])->id;

        return $otAndHolidayId;
    }

    private function storeOtHolidayShow($request, $otAndHolidayId)
    {
        $data = [];

        if ($request->get_holiday != null)
        {
            foreach ($request->get_holiday as $key => $get_holiday)
            {
                $data[] = [

                    'ot_and_holiday_id' =>$otAndHolidayId,
                    'holiday_date' => $get_holiday,

                ];
            }
        }

        OtShowHoliday::insert($data);
    }

    private function storeHolidayHouresAndAllowance($request, $otAndHolidayId)
    {
        if ($request->check != null)
        {
            $data = [];
            foreach ($request->check as $key => $check)
            {

                $data['otHolidayAllowance'] = [
                    'employee_id' => $request->employee_id[$check],
                    'effected_month' => $request->effected_month,
                    'over_time_hours' => $request->ot_hour[$check],
                    'over_time_total_amount' => $request->ot_hour_total_value[$check],
                    'tiffin_amount' => $request->tiffin_amount[$check],
                    'tiffin_day' => $request->tiffin_day[$check],
                    'bank_amount' => $request->bank_value[$check],
                    'cash_amount' => $request->cash_value[$check],
                    'get_salary_shit' => $request->get_in_salary_shit,
                    'ot_and_holiday_id' => $otAndHolidayId,
                    'per_hour_salary' => $request->per_hour_salary[$check],
                ];

                if ($request->ot_hour[$check] != null && $request->get_in_salary_shit)
                {

                    $data['disbursmentData'] = [

                        'effected_month'        => $request->effected_month,
                        'ot_hour'              => (float) $request->ot_hour[$check],
                        'total_value'          => $request->ot_hour_total_value[$check] ?: 0,
                        'bank_value'           => $request->bank_value[$check] ?: 0,
                        'cash_value'           => $request->cash_value[$check] ?: 0,
                        'employee_id'          => $request->employee_id[$check],
                        'generated_by'         => auth()->id(),
                        'salary_details_id'    => 0,
                        'disbursement_type_id' => 2,
                        'created_by' => auth()->id(),
                        'updated_by' => auth()->id(),

                    ];

                    Disbursement::insert($data['disbursmentData']);

                }

                $otAndHolidayAllowanceId = OtAndHolidayAllowance::insertGetId($data['otHolidayAllowance']);


                $this->storeHolidayHour($request, $otAndHolidayAllowanceId, $check);
            }


        }
    }

    private function storeHolidayHour($request, $otAndHolidayAllowanceId, $check)
    {
        if ($request->holidayHour != null)
        {
            $data = [];

            foreach ($request->holidayHour[$check] as $sKey => $hour)
            {
                $data[] = [

                    'ot_and_holiday_allowance_id' => $otAndHolidayAllowanceId,
                    'employee_id' => $request->employee_id[$check],
                    'holiday_date' => $request->holidayDate[$check][$sKey],
                    'ot_hour' => $request->holidayHour[$check][$sKey],

                ];
            }


            OtHolidayHour::insert($data);

        }
    }


    public function exportData($id)
    {
       $otAndHolidays = OtAndHoliday::with('otAndHolidayAllowance.otHolidayHours', 'otShowHoliday', 'company', 'department', 'designation')
           ->findOrFail($id);

        return $otAndHolidays;
    }





    private function getWeeklyHolidayDate($company, $month)
    {
        $daysName = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $weeklyHoliday = Holiday::where('company_id', $company)->where('day_type', 3)->pluck('start');

        $year = date('Y', strtotime($month));
        $monthName = date('m', strtotime($month));
        $days = cal_days_in_month(CAL_GREGORIAN, $monthName, $year);
        $data = [];

        foreach ($weeklyHoliday as $val) {
            for ($i2 = 1; $i2 <= $days; $i2++) {
                $ddd = $month . '-' . $i2;
                $date = Carbon::parse(date('D', strtotime($ddd)))->dayOfWeek;
                if ($date == $val) {
                    array_push($data, date('Y-m-d', strtotime($ddd)));
                }
            }
        }
        return $data;
    }

    private function getDayToDayHolidayDate($company, $month)
    {
        $dayTodayHoliday = Holiday::where('company_id', $company)->where('day_type', 2)->where(DB::raw('substr(start, 1, 7)'), '=',$month)->select('start', 'end')->get();
        $data = [];

        foreach ($dayTodayHoliday as $dayTodayHoliday) {
            for ($i = $dayTodayHoliday->start; $i <= $dayTodayHoliday->end; $i++) {
                array_push($data, $i);
            }
        }
        return $data;
    }

    private function getSingleDayHolidayDate($company, $month)
    {
        $signleDayHoliday = Holiday::where('company_id', $company)->where('day_type', 1)->where(DB::raw('substr(start, 1, 7)'), '=',$month)->select('start')->get();
        $data = [];

        foreach ($signleDayHoliday as $dayHoliday) {
            array_push($data, $dayHoliday->start);
        }
        return $data;
    }
}
