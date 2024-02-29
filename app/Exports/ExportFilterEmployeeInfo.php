<?php

namespace App\Exports;

use Module\HRM\Models\Employee\Employee;
use Illuminate\Database\Query\Builder;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportFilterEmployeeInfo implements FromQuery, WithHeadings, ShouldAutoSize
{
    use Exportable;

    public function __construct($company,$department,$designation = null,$employee_type = null)
    {
        $this->company = $company;
        $this->department = $department;
        $this->designation = $designation;
        $this->employee_type = $employee_type;
    }

    /**
     * @return Builder
     */
    public function query()
    {

        if ($this->designation != null){
            return Employee::query()->where('company_id',$this->company)
                ->where('department_id', $this->department)
                ->where('designation_id',$this->designation)
                ->select('id','company_id','employee_full_id','name');
        }elseif ($this->employee_type != null){
            return Employee::query()->where('company_id',$this->company)
                ->where('department_id', $this->department)
                ->where('employee_type',$this->employee_type)
                ->select('id','company_id','employee_full_id','name');
        }elseif ($this->employee_type != null && $this->designation != null){
            return Employee::query()->where('company_id',$this->company)
                ->where('department_id', $this->department)
                ->where('designation_id',$this->designation)
                ->where('employee_type',$this->employee_type)
                ->select('id','company_id','employee_full_id','name');
        }else{
            return Employee::query()->where('company_id',$this->company)
                ->where('department_id', $this->department)
                ->select('id','company_id','employee_full_id','name');
        }
    }

    public function headings():array
    {
        return [
            'id',
            'company_id',
            'employee_full_id',
            'name',
            'mfs_type',
            'mfs',
            'national_id',
            'grade_id',
            'card_no',
            'bank_id',
            'account_number',

        ];
    }

}
