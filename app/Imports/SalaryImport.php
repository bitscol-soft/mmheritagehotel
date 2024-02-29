<?php

namespace App\Imports;

use App\Models\Salary\UploadSalary;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SalaryImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new UploadSalary([

            'company_id' => $row['company_id'],
            'employee_id' => $row['id'],
            'salary_type_id' => $row['salary_type_id'],
            'permanent_date' => Carbon::parse($row['permanent_date'])->format('Y-m-d'),
            'effect_from_date' => Carbon::parse($row['effect_from_date'])->format('Y-m-d'),
            'disbursement_date' => Carbon::parse($row['disbursement_date'])->format('Y-m-d'),
            'promotion_date' => Carbon::parse($row['promotion_date'])->format('Y-m-d'),
            'increment' => $row['increment'] ?: 0,
            'total_gross' => $row['total_gross'],
            'bank_basic' => $row['bank_basic'] ?: 0,
            'bank_house_rent' => $row['bank_house_rent'] ?: 0,
            'bank_conveyance' => $row['bank_conveyance'] ?: 0,
            'bank_medical' => $row['bank_medical'] ?: 0,
            'bank_gross' => $row['bank_gross'] ?: 0,
            'cash_basic' => $row['cash_basic'],
            'cash_house_rent' => $row['cash_house_rent'],
            'cash_conveyance' => $row['cash_conveyance'],
            'cash_medical' => $row['cash_medical'],
            'cash_gross' => $row['cash_gross'],


        ]);
    }
}
