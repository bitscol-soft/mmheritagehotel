<?php

namespace Module\Restaurant\Models;

use App\Traits\AutoCreatedUpdated;
use App\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RstMetrialDetails extends Model
{
    use HasFactory;

    public function production()
    {
        return $this->belongsTo(RstProduction::class);
    }


    public function product()
    {
        return $this->belongsTo(Product::class);
    }


}
