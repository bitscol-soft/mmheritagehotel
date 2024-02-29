<?php

namespace Module\Account\Models;

use App\Model;
use App\Traits\AutoCreatedUpdated;

class ProductStockDetail extends Model
{
    use AutoCreatedUpdated;


    protected $table = 'requsition_stocks';
}
