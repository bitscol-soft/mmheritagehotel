<?php

namespace App\Models;


use Module\HRM\Models\Attendance\HolidayAdjust;

use Module\HRM\Models\Attendance\MonthlyLateDeduction;
use Module\HRM\Models\Attendance\OutsideWork;
use Module\HRM\Models\Attendance\Shift;
use App\Models\Company;
use Module\HRM\Models\Department;
use Module\HRM\Models\Designation;
use Module\HRM\Models\Grade;
use Module\HRM\Models\Line;
use Module\HRM\Models\Leave\LeaveApplication;
use Module\HRM\Models\Leave\ShortLeaveApplication;
use Module\HRM\Models\Ot\OtAndHolidayAllowance;
use App\Models\ProductionSalary\ProductionSalaryGenerateDetails;
use Module\HRM\Models\Salary\BankSalary;
use Module\HRM\Models\Salary\CashSalary;
use Module\HRM\Models\Salary\Salary;
use Module\HRM\Models\Salary\Disbursement;
use Module\HRM\Models\Salary\HrLoan;
use Module\HRM\Models\Salary\SalaryGeneratedDetails;

use App\Models\User;
use App\Traits\AutoCreatedUpdated;
use App\Model;
use Module\HRM\Models\Attendance\EmployeeHoliday;
use Module\HRM\Models\Attendance\HolidayAssign;
use Module\HRM\Models\Attendance\Attendance;
use Module\HRM\Models\Attendance\AttendanceBonusDetails;
use Module\HRM\Models\Attendance\ManualAll;
use Module\HRM\Models\FixedBonusDetail;
use Module\HRM\Models\HoldDate;
use Module\HRM\Models\PF\ProvidentFundEnrol;
use Module\HRM\Models\ProductionSalary\EmployeesSampleRate;
use Module\HRM\Models\ProductionSalary\EmployeeBasicRate;
use Module\HRM\Models\ProductionSalary\EmployeesProductionRate;
use Module\HRM\Models\Schedule;
use Module\HRM\Models\Training\TrainingEnrollment;

class Employee extends Model
{
    protected $fillable = [
        'created_by',  'updated_by',  'company_id','shift_id' , 'department_id',  'designation_id', 'grade_id','employee_group_id',
        'joining_date', 'previous_id_number', 'id_number', 'given_id_number', 'employee_full_id', 'name', 'image',
        'father_or_husband_name', 'mother_name', 'date_of_birth', 'gender', 'marital_status',
        'religion', 'present_address', 'present_phone_number', 'permanent_address', 'permanent_phone_number',
        'email', 'nationality', 'national_id', 'employee_type', 'p_bonus_type', 'status', 'device_id', 'finger_print_id', 'mfs_type', 'mfs','card_no',
        'over_time_status', 'signature', 'line_id', 'spouse_name', 'employment_status', 'cv', 'employee_facility'
    ];

    use AutoCreatedUpdated;

    public function getEmpFullIdAttribute()
    {
        return "{$this->name}->{$this->employee_full_id}";
    }


    public function company()
    {

        return $this->belongsTo(Company::class);
    }

    public function scopeActive($query)
    {
        $query->where('status', 1);
    }

    public function created_user()
    {

        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updated_user()
    {

        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    public function department()
    {

        return $this->belongsTo(Department::class);
    }

    public function designation()
    {

        return $this->belongsTo(Designation::class);
    }

    public function grade()
    {

        return $this->belongsTo(Grade::class);
    }

    public function line()
    {

        return $this->belongsTo(Line::class);
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public function educational_qualification()
    {

        return $this->hasMany(EducationalQualification::class);
    }
    public function last_educational_qualification()
    {

        return $this->educational_qualification()->latest()->first();
    }

    public function experience()
    {

        return $this->hasMany(Experience::class);
    }

    public function lastExperience()
    {
        return $this->experience()->latest()->first();
    }



    public function personal_information()
    {

        return $this->hasOne(PersonalInformation::class);
    }

    public function guardian()
    {

        return $this->hasOne(Guardian::class);
    }

    public function reference_person()
    {

        return $this->hasOne(ReferencePerson::class);
    }

    public function bank_information()
    {

        return $this->hasOne(BankInformation::class);
    }

    public function salaries()
    {
        return $this->hasMany(Salary::class);
    }

    public function salary()
    {
        return $this->hasOne(Salary::class)->where('status', 1);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'employee_id');
    }

    public function attendances_late()
    {
        return $this->hasMany(Attendance::class, 'employee_id');
    }

    public function today_attendances()
    {
        return $this->hasOne(Attendance::class);
    }

    public function manual_all_attendance()
    {
        return $this->hasMany(ManualAll::class);
    }

    public function manual_all_month()
    {
        return $this->hasOne(ManualAll::class);
    }

    public function attendanceBonus()
    {
        return $this->hasMany(AttendanceBonusDetails::class);
    }

    public function attendance_bonus_details()
    {
        return $this->hasOne(AttendanceBonusDetails::class);
    }

    public function attBonus()
    {
        return $this->hasOne(AttendanceBonusDetails::class);
    }


    // akash ...
    public function disbursements()
    {
        return $this->hasMany(Disbursement::class);
    }

    public function overTime()
    {
        return $this->disbursements()->where('disbursement_type_id', 2);
    }

    public function arrear()
    {
        return $this->disbursements()->where('disbursement_type_id', 3);
    }

    public function advanced()
    {
        return $this->disbursements()->where('disbursement_type_id', 1);
    }

    public function incometax()
    {
        return $this->disbursements()->where('disbursement_type_id', 4);
    }

    public function loan()
    {
        return $this->hr_loans()->where('is_approved', 1);
    }

    public function monthly_late_deduction()
    {
        return $this->hasOne(MonthlyLateDeduction::class);
    }




    public function disbursement_advance($effected_month)
    {
        return $this->hasOne(Disbursement::class)->where('effected_month', '=', $effected_month)->where('disbursement_type_id', '=', '1')->first();
    }

    public function disbursement_ot_hour($effected_month)
    {
        return $this->hasOne(Disbursement::class)->where('effected_month', '=', $effected_month)->where('disbursement_type_id', '=', '2')->first();
    }
    public function disbursement_arrear($effected_month)
    {
        return $this->hasOne(Disbursement::class)->where('effected_month', '=', $effected_month)->where('disbursement_type_id', '=', '3')->first();
    }
    public function disbursement_tax($effected_month)
    {
        return $this->hasOne(Disbursement::class)->where('effected_month', '=', $effected_month)->where('disbursement_type_id', '=', '4')->first();
    }
    public function hr_loans()
    {
        return $this->hasMany(HrLoan::class);
    }

    public function loneAmount($month)
    {
        return $this->hr_loans->where('applicable_month', $month)->where('is_approved', 1)->first();
    }


    // .. akash //

    public function out_workers()
    {
        return $this->hasMany(OutsideWork::class);
    }


    public function user()
    {
        return $this->hasOne(User::class);
    }
    public function leaves()
    {
        return $this->hasMany(LeaveApplication::class);
    }

    public function leavesWithOpening()
    {
        return $this->leaves()->where('opening_leave', 1);
    }

    public function monthly_leaves()
    {
        return $this->leaves()->where('recommended_by', '!=', '')
            ->where('approved_by', '!=', '');
    }

    public function joining_salary()
    {
        return $this->hasOne(Salary::class)->orderBy('id');
    }
    public function current_salary()
    {
        return $this->hasOne(Salary::class)->where('status', 1);
    }

    public function increment_salary()
    {
        return $this->hasOne(Salary::class)->where('status', 0)->where('approve', 0)->orderBy('id', 'DESC');
    }

    public function current_bank_salary()
    {
        return $this->hasOne(BankSalary::class)->orderBy('id', 'DESC');
    }
    public function current_cash_salary()
    {
        return $this->hasOne(CashSalary::class)->orderBy('id', 'DESC');
    }

    public function fixed_bonuses()
    {
        return $this->hasMany(FixedBonusDetail::class, 'employee_id');
    }
    // end

    public function leave_count($id, $month)
    {
        $year = date('Y', strtotime($month . '-01'));
        $leaves = $this->leaves->where('leave_type_id', $id)
            ->where('year', $year)
            ->where('recommended_by', '!=', '')
            ->where('approved_by', '!=', '');

        $totalCl = 0;
        foreach ($leaves as $leave) {
            $from = \Carbon\Carbon::parse($leave->from);
            $to = \Carbon\Carbon::parse($leave->to);
            $totalDay = $to->diffInDays($from);
            $totalCl += ($totalDay + 1);
        }
        return $totalCl;
    }

    public function gross()
    {
        return $this->salaries->where('status', 1)->first();
    }

    public function leaveCount()
    {
        return $this->hasMany(LeaveApplication::class)
            ->selectRaw('count(leave_applications.id) as count, leave_types.id, leave_types.name,
            DATEDIFF(leave_applications.to, leave_applications.from) as dateDiff')
            ->join('leave_types', 'leave_types.id', '=', 'leave_type_id')
            ->groupBy('leave_types.id', 'leave_types.name', 'leave_applications.from', 'leave_applications.to');
    }

    public function salary_generated_details()
    {
        return $this->hasMany(SalaryGeneratedDetails::class);
    }

    public function shortLeaveCount()
    {
        return $this->hasOne(ShortLeaveApplication::class)
            ->where('adjust',0)
            ->groupBy('employee_id')
            ->selectRaw('count(id) as countEmployee, employee_id');
    }

    public function unSeenShortleave()
    {
        return $this->hasOne(ShortLeaveApplication::class)
            ->where('seen_by',null)
            ->groupBy('employee_id')
            ->selectRaw('count(id) as countEmployee, employee_id');
    }


    public function late_deduction()
    {
        return $this->hasMany(MonthlyLateDeduction::class);
    }


    public function company_department($company_id)
    {
        return $this->where('company_id', $company_id)->distinct()->select('department_id')->get();
    }

    public function department_designation($company_id,$department_id)
    {
        return $this->where('company_id', $company_id)->where('department_id', $department_id)->distinct()->select('designation_id')->get();
    }

    public function getActiveEmployee($company_id,$department_id,$designation_id)
    {
        return $this->where('company_id', $company_id)->where('department_id', $department_id)->where('designation_id', $designation_id)->where('status', 1)->count();
    }

    public function getInActiveEmployee($company_id,$department_id,$designation_id)
    {
        return $this->where('company_id', $company_id)->where('department_id', $department_id)->where('designation_id', $designation_id)->where('status', 2)->count();
    }

    public function company_designation($company_id)
    {
        return $this->where('company_id', $company_id)->distinct()->select('designation_id')->count();
    }

    public function holidayAdjust()
    {
        return $this->hasMany(HolidayAdjust::class, 'employee_id', 'id');
    }


    public function holds()
    {
        return $this->hasMany(HoldDate::class, 'employee_id', 'id');
    }



    // ======================  Production salary methods
    public function productions()
    {
        return $this->hasMany(EmployeesProductionRate::class, 'employee_id', 'id');
    }

    public function productionBody()
    {
        return $this->hasMany(EmployeesProductionRate::class);
    }

    public function productionNonBody()
    {
        return $this->hasMany(EmployeesProductionRate::class);
    }

    public function productionSample()
    {
        return $this->hasMany(EmployeesSampleRate::class);
    }

    public function basicDays()
    {
        return $this->hasMany(EmployeeBasicRate::class);
    }

    public function productionSalaries()
    {
        $this->hasMany(ProductionSalaryGenerateDetails::class);
    }







    //Schedule

    public function schedule()
    {
        return $this->hasMany(Schedule::class);
    }

    public function schedule_last()
    {
        return $this->hasOne(Schedule::class);
    }


    //Provident Fund

    public function providentFundEnrol()
    {
        return $this->hasOne(ProvidentFundEnrol::class);
    }

    public function otHolidayAndTiffins()
    {
        return $this->hasMany(OtAndHolidayAllowance::class, 'employee_id', 'id');
    }

    public function scopeCompanies($query)
    {
        return $query->whereIn('company_id', Company::userCompanyId());
    }

    public function scopeDepartments($query)
    {
        return $query->whereIn('department_id', Department::userDepartmentId());
    }

    public function scopeDesignations($query)
    {
        return $query->whereIn('designation_id', Designation::userDesignationId());
    }

    public function holiday_asigns()
    {
        return $this->hasMany(HolidayAssign::class, 'employee_id', 'id');
    }

    public function empployee_assigned_holidays()
    {
        return $this->hasMany(EmployeeHoliday::class, 'employee_id', 'id');
    }


    public function getEmployeeTitle()
    {
        if(strtoupper(optional($this)->gender) == 'MALE')
            return 'Mr. ';
        else if(strtoupper(optional($this)->marital_status) == 'MARRIED')
            return 'Mrs. ';
        else if(optional($this)->gender != '')
            return 'Miss. ';
        else
            return '';
    }

    //    ###############       EMPLOYMENT      ####################

    public function scopeSearchByField($query, $filed_name)
    {
        $query->when(request()->filled($filed_name), function($qr) use($filed_name) {
           $qr->where($filed_name, request()->$filed_name);
        });
    }

    public function scopeSearchByDate($query, $database_field, $filed_name, $condition = '=')
    {
        $query->when(request()->filled($filed_name), function($qr) use($database_field, $filed_name, $condition) {
           $qr->whereDate($database_field, $condition, request()->$filed_name);
        });
    }

    public function scopeSearchSalary($query)
    {
        $query->when(request()->filled('salary_type_id'), function($qr) {
            $qr->whereHas('salaries', function ($q) {
                $q->where('salary_type_id', request()->salary_type_id)->where('status', 1);
            });
        });
    }

    public function scopeEmployment($query)
    {
        $query->with(['active_employment' => function($q) {
            $q->with('company:name,id', 'department:name,id', 'designation:name,id');
        }]);
    }


    public function scopePermissionEmployment($query)
    {
        return $query->whereHas('active_employment', function($q) {
            $q->whereIn('company_id', Company::userCompanyId())
            ->whereIn('department_id', Department::userDepartmentId())
            ->whereIn('designation_id', Designation::userDesignationId());
        });
    }

    public function scopeSearchCompany($query)
    {
        return $query->whereHas('active_employment', function($q) {
            $q->when(request()->filled('company_id'), function($qr) {
               $qr->where('company_id', request()->company_id);
            });
        });
    }



    public function scopeSearchDepartment($query)
    {
        return $query->whereHas('active_employment', function($q) {
            $q->when(request()->filled('department_id'), function($qr) {
               $qr->where('department_id', request()->department_id);
            });
        });
    }

    public function scopeSearchDepartmentIds($query)
    {
        return $query->whereHas('active_employment', function($q) {
            $q->when(request()->filled('department_id'), function($qr) {
               $qr->whereIn('department_id', request()->department_id);
            });
        });
    }


    public function scopeSearchDesignation($query)
    {
        return $query->whereHas('active_employment', function($q) {
            $q->when(request()->filled('designation_id'), function($qr) {
               $qr->where('designation_id', request()->designation_id);
            });
        });
    }






    public function employments()
    {
        return $this->hasMany(EmploymentInfo::class, 'employee_id', 'id');
    }

    public function active_employment()
    {
        return $this->hasOne(EmploymentInfo::class, 'employee_id', 'id')->where('is_active', 1);
    }



    public function getEmploymentId()
    {
        return optional($this->active_employment)->id;
    }

    public function getCompanyName()
    {
        return optional(optional($this->active_employment)->company)->name;
    }

    public function getDepartmentName()
    {
        return optional(optional($this->active_employment)->department)->name;
    }

    public function getDesignationName()
    {
        return optional(optional($this->active_employment)->designation)->name;
    }

    public static function updateEmploymentInfo()
    {
        $employees = self::whereDoesntHave('employments')->select('id as employee_id', 'joining_date as effective_date', 'company_id', 'department_id', 'designation_id', 'created_by', 'updated_by', 'created_at', 'updated_at')->get()->toArray();

        $status = EmploymentInfo::insert($employees);
    }

    public function enrollment()
    {
        return $this->hasMany(TrainingEnrollment::class, 'employee_id');
    }

}
