<?php

namespace Module\Account\Models;

use App\Model;
use App\Traits\AutoCreatedUpdated;

class StockSummary extends Model
{
    use AutoCreatedUpdated;

    protected $table = 'acc_stock_summaries';

    protected $guarded = [];
}
