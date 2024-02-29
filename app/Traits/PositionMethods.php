<?php

namespace App\Traits;

use App\Models\Company;
use Module\HRM\Models\Department;
use Module\HRM\Models\Designation;
use Module\HRM\Models\Employee\EmploymentInfo;

trait PositionMethods
{

    public function position()
    {
        return $this->belongsTo(EmploymentInfo::class, 'position_id', 'id');
    }

    public function getCompanyName()
    {
        return optional(optional($this->position)->company)->name;
    }

    public function getDepartmentName()
    {
        return optional(optional($this->position)->department)->name;
    }

    public function getDesignationName()
    {
        return optional(optional($this->position)->designation)->name;
    }


    public function scopeEmployment($query)
    {
        $query->with(['position' => function($q) {
            $q->with('company:name,id', 'department:name,id', 'designation:name,id');
        }]);
    }


    public function scopePermissionEmployment($query)
    {
        $query->whereHas('position', function($q) {
            $q->whereIn('company_id', Company::userCompanyId())
            ->whereIn('department_id', Department::userDepartmentId())
            ->whereIn('designation_id', Designation::userDesignationId());
        });
    }
    
    public function scopeSearchCompany($query)
    {
        $query->whereHas('position', function($q) {
            $q->when(request()->filled('company_id'), function($qr) {
               $qr->where('company_id', request()->company_id);
            });
        });
    }



    public function scopeSearchDepartment($query)
    {
        $query->whereHas('position', function($q) {
            $q->when(request()->filled('department_id'), function($qr) {
               $qr->where('department_id', request()->department_id);
            });
        });
    }


    public function scopeSearchDesignation($query)
    {
        return $query->whereHas('position', function($q) {
            $q->when(request()->filled('designation_id'), function($qr) {
               $qr->where('designation_id', request()->designation_id);
            });
        });
    }

}