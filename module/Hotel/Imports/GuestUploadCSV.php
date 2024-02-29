<?php

namespace Module\Hotel\Imports;

use Module\Hotel\Models\Guest;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class GuestUploadCSV implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {

        return new Guest([

            'name'                      => $row['name'],
            'email'                     => $row['email'] != '' ? $row['email'] : NULL,
            'phone_no'                  => $row['phone_no'] != NULL ? $row['phone_no'] : NULL,
            'nid_no'                    => $row['nid_no'] != '' ? $row['nid_no'] : NULL,
            'passport_expiry_date'      => $row['passport_expiry_date'] != '' ? $row['passport_expiry_date'] : 'None',
            'country_id'                => $row['country_id'] != '' ? $row['country_id'] : NULL,
            'city_id'                   => $row['city_id'] != '' ? $row['city_id'] : 1,
            'company_id'                => $row['company_id'] != '' ? $row['company_id'] : NULL,
            'gender'                    => $row['gender'] != '' ? $row['gender'] : NULL,
            'father_name'               => $row['father_name'] != '' ? $row['father_name'] : NULL,
            'age'                       => $row['age'] != '' ? $row['age'] : 0,
            'image'                     => $row['image'] != '' ? $row['image'] : NULL,
            'address'                   => $row['address'] != '' ? $row['address'] : NULL,
            'reference'                 => $row['reference'] != '' ? $row['reference'] : NULL,
            'profession'                => $row['profession'] != '' ? $row['profession'] : NULL,
            'spouse_name'               => $row['spouse_name'] != '' ? $row['spouse_name'] : NULL,
            'nid_front'                 => $row['nid_front'] != '' ? $row['nid_front'] : NULL,
            'nid_back'                  => $row['nid_back'] != '' ? $row['nid_back'] : NULL,
            'spouse_nid_front'          => $row['spouse_nid_front'] != '' ? $row['spouse_nid_front'] : NULL,
            'spouse_nid_back'           => $row['spouse_nid_back'] != '' ? $row['spouse_nid_back'] : NULL,
            'status'                    => $row['status'] != '' ? $row['status'] : NULL,
            'is_stuff'                  => $row['is_stuff'] != '' ? $row['is_stuff'] : 0,
            'created_by'                => $row['created_by'] != '' ? $row['created_by'] : NULL,
        ]);
    }
}
