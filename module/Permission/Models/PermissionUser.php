<?php

namespace Module\Permission\Models;

use App\Model;

class PermissionUser extends Model
{
    protected $table = 'permission_user';
    
    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }
}
