<?php

namespace App\Exports;


use Module\HRM\Models\Employee\Employee;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportEmployeeListCSV implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Employee::select('id','company_id','id_number','given_id_number','name')->get();
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
