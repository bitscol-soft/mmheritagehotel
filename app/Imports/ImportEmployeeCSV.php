<?php

namespace App\Imports;

use Module\HRM\Models\Employee\EmployeeUpload;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportEmployeeCSV implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new EmployeeUpload([

            'company_id'                => $row['company_id'],
            'department_id'             => $row['department_id'],
            'designation_id'            => $row['designation_id'],
            'line_id'                   => $row['section_id'] != '' ? $row['section_id'] : null,
            'grade_id'                  => $row['grade_id'],
            'joining_date'              => $row['joining_date'] != '' ? fdate(str_replace('-', '-', $row['joining_date']), 'Y-m-d') : '',
            'previous_id_number'        => $row['previous_id_number'],
            'id_number'                 => $row['id_number'],
            'given_id_number'           => $row['given_id_number'],
            'name'                      => $row['name'],
            'father_or_husband_name'    => $row['father_or_husband_name'],
            'mother_name'               => $row['mother_name'],
            'date_of_birth'             => $row['date_of_birth'] != '' ? fdate(str_replace('-', '-', $row['date_of_birth']), 'Y-m-d') : '',
            'gender'                    => $row['gender'],
            'marital_status'            => $row['marital_status'],
            'religion'                  => $row['religion'],
            'present_address'           => $row['present_address'],
            'present_phone_number'      => $row['present_phone_number'],
            'permanent_address'         => $row['permanent_address'],
            'permanent_phone_number'    => $row['permanent_phone_number'],
            'email'                     => $row['email'],
            'nationality'               => $row['nationality'],
            'national_id'               => $row['national_id'],
            'employee_type'             => $row['employee_type'],
            'p_bonus_type'              => $row['p_bonus_type'],

            'height'                    => $row['height'],
            'weight'                    => $row['weight'],
            'phone_no'                  => $row['phone_no'],
            'blood_group'               => $row['blood_group'],

            'guardian_name'             => $row['guardian_name'],
            'guardian_phone_no_1'       => $row['guardian_phone_no_1'],
            'guardian_phone_no_2'       => $row['guardian_phone_no_2'],
            'guardian_relation'         => $row['guardian_relation'],
            'guardian_address'          => $row['guardian_address'],

            'references_name'           => $row['references_name'],
            'references_phone_no_1'     => $row['references_phone_no_1'],
            'references_phone_no_2'     => $row['references_phone_no_2'],
            'references_relation'       => $row['references_relation'],
            'references_address'        => $row['references_address'],

            'bank_id'                   => $row['bank_id'],
            'bank_account_no'           => $row['bank_account_no'],
            'e_tin_number'              => $row['e_tin_number'],

            'examination'               => $row['examination'],
            'number'                    => $row['number'],
            'passing_year'              => $row['passing_year'],
            'board'                     => $row['board'],

            'company_name'              => $row['company_name'],
            'designation'               => $row['designation'],
            'duration'                  => $row['duration'],

        ]);
    }


}
