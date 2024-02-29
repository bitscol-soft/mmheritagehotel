<?php

namespace Module\Account\Models;

use App\Model;
use App\Traits\AutoCreatedUpdated;

class CustomerLedger extends Model
{
    use AutoCreatedUpdated;

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
