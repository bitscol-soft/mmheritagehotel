<?php

namespace App\Models;

use App\Model;
use App\Models\Company;
use App\Traits\AutoCreatedUpdated;
use Module\HRM\Models\Attendance\EmployeeGeneralShift;
use Module\HRM\Models\Department;
use Module\HRM\Models\Designation;
use Module\HRM\Models\EmployeeFixedHoliday;

class EmploymentInfo extends Model
{
    use AutoCreatedUpdated;

    public function company()
    {

        return $this->belongsTo(Company::class);
    }


    public function department()
    {
        if (class_exists('Module\HRM\Models\Department')) {
            return $this->belongsTo(Department::class);
        }
        return [];
    }

    public function designation()
    {

        return $this->belongsTo(Designation::class);
    }

    public function employee_general_shift()
    {
        return $this->hasOne(EmployeeGeneralShift::class, 'position_id', 'id');
    }

    public function employee_fixed_holiday()
    {
        return $this->hasOne(EmployeeFixedHoliday::class, 'position_id', 'id');
    }
}
