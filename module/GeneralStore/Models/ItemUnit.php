<?php

namespace Module\GeneralStore\Models;

use App\Traits\AutoCreatedUpdated;
use App\Model;

class ItemUnit extends Model
{
    // fillup created and updated fields, and add created_user, updated_user method
    use AutoCreatedUpdated;

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
