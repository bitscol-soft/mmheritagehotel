<?php

namespace App\Exports;

use Module\HRM\Models\Employee\Employee;
use Illuminate\Database\Query\Builder;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportFilterEmployeeListCSV implements FromQuery, WithHeadings
{
    use Exportable;

    public function __construct($company,$department,$designation=null)
    {
        $this->company = $company;
        $this->department = $department;
        $this->designation = $designation;
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
                ->select('id','company_id','id_number','given_id_number','name');
        }
        return Employee::query()->where('company_id',$this->company)
            ->where('department_id', $this->department)
            ->select('id','company_id','id_number','given_id_number','name');
    }

    public function headings():array
    {
        return [
            'id',
            'company_id',
            'id_number',
            'given_id_number',
            'name',
            'salary_type_id',
            'permanent_date',
            'effect_from_date',
            'disbursement_date',
            'promotion_date',
            'increment',
            'total_gross',
            'bank_basic',
            'bank_house_rent',
            'bank_conveyance',
            'bank_medical',
            'bank_gross',
            'cash_basic',
            'cash_house_rent',
            'cash_conveyance',
            'cash_medical',
            'cash_gross'

        ];
    }

}
