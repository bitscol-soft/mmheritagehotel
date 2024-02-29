<?php

namespace Module\Restaurant\Models;

use App\Model;
use App\Traits\AutoCreatedUpdated;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PrductMetrial extends Model
{
    use HasFactory, AutoCreatedUpdated;
    protected $table = 'product_metrials';


    public function stock(){

        return $this->hasMany(Stock::class, 'product_id', 'material_id');
    }




}
