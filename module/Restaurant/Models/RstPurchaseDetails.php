<?php

namespace Module\Restaurant\Models;

use Illuminate\Support\Arr;
use App\Traits\AutoCreatedUpdated;
// use Illuminate\Database\Eloquent\Model;
use Module\Restaurant\Models\RestMaterial;
use App\Traits\AutoCreatedUpdatedWithCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Model;


class RstPurchaseDetails extends Model
{
    // use HasFactory, AutoCreatedUpdated;
    // use AutoCreatedUpdatedWithCompany;
    protected $table = 'rst_material_purchase_details';




    public function product()
    {
        return $this->belongsTo(Product::class);
    }





}
