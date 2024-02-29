<?php

namespace App\Imports;

use Module\HRM\Models\Employee\EmploymentInfo;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EmployeeInfoImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new EmployeeUploadInfo([

            'employee_id' => $row['id'],
            'national_id' => $row['national_id'],
            'grade_id' => $row['grade_id'],
            'mfs_type' => $row['mfs_type'],
            'mfs' => $row['mfs'],
            'card_no' => $row['card_no'],
            'bank_id' => $row['bank_id'],
            'account_number' => $row['account_number'],

        ]);
    }
}
