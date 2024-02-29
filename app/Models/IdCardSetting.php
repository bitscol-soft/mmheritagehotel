<?php

namespace App\Models;

use App\Model;
use App\Models\Company;
use App\Traits\AutoCreatedUpdated;

class IdCardSetting extends Model
{
    use AutoCreatedUpdated;

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }
}
