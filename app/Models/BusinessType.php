<?php

namespace App\Models;

use App\Model;
use App\Traits\AutoCreatedUpdated;

class BusinessType extends Model
{
    use AutoCreatedUpdated;

    public function companies()
    {
        return $this->hasMany(Company::class);
    }
}
