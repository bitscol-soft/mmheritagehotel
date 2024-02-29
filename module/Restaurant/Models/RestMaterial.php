<?php

namespace Module\Restaurant\Models;

use App\Models\Company;
use App\Traits\AutoCreatedUpdated;
use Module\Restaurant\Models\Model;
// use Illuminate\Database\Eloquent\Model;

class RestMaterial extends Model
{
    use AutoCreatedUpdated;

    protected $guarded = [];



    public function item_unit()
    {
        return $this->belongsTo(RestMaterialUnit::class, 'units_id', 'id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    //  return items only permitted companies
    public function scopeItems($q)
    {
        return $q->whereIn('company_id', Company::userCompanyId());
    }


    public function stock()
    {
        return $this->hasMany(Stock::class, 'product_id');
    }


    public function stocks()
    {
        return $this->hasMany(Stock::class, 'product_id');
    }


}
