<?php

namespace Module\Restaurant\Models;

use App\Traits\AutoCreatedUpdated;
use App\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RstFinishGoodDetails extends Model
{
    use HasFactory;

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}
