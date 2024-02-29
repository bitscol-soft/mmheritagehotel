<?php

namespace App\Models;

use App\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ActivityLog extends Model
{




    public function causer(): MorphTo
    {
        return $this->morphTo();
    }
}
