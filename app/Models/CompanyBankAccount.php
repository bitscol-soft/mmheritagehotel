<?php

namespace App\Models;

use App\Model;

class CompanyBankAccount extends Model
{

    public function company()
    {
        return $this->belongsTo(Company::class);
    }


}
