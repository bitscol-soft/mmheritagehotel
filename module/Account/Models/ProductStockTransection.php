<?php

namespace Module\Account\Models;

use App\Traits\AutoCreatedUpdated;
use Illuminate\Database\Eloquent\Model;

class ProductStockTransection extends Model
{
    use AutoCreatedUpdated;
    
    protected $fillable = ['invoice_no', 'factory_id', 'date', 'product_id', 'warehouse_id', 'company_id', 'amount', 'source_type', 'source_id', 'date', 'quantity', 'price', 'amount', 'stock_type', 'created_by', 'updated_by'];
    //
}
