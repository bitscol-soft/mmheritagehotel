<?php

namespace Module\Restaurant\Models;

use App\Model;
use App\Traits\AutoCreatedUpdatedWithCompany;

class ProductBatch extends Model
{
    use AutoCreatedUpdatedWithCompany;

    protected $table = 'rst_product_batches';

    /**
     * The products that carry this batch/lot number (docs/BUGS.md #71). The
     * list screen counts them, so the query must not hit a missing method.
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'batch_id');
    }
}
