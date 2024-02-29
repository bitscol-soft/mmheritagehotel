<?php

namespace App\Models;

use App\Model;

class UserLoginStatus extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
