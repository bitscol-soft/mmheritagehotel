<?php

namespace App\Exports;

use Module\HRM\Models\Salary\Salary;
use App\Models\Salary\UploadSalary;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportSalaryCSV implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return UploadSalary::all();
    }
}
