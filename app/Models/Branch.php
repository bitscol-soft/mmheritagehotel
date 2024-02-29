<?php

namespace App\Models;

use App\Traits\AutoCreatedUpdatedWithCompany;
use App\Model;

class Branch extends Model
{
    use AutoCreatedUpdatedWithCompany;


   public function scopeBranchs($q)
   {
       return $q->where('company_id', auth()->user()->company_id);
   }
}
