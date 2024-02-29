<?php

namespace App\Models;

use App\Model;

class CompanyDetails extends Model
{

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

}
