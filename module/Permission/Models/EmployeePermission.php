<?php

namespace Module\Permission\Models;

use App\Model;

class EmployeePermission extends Model
{
    public function permission()
    {
        return $this->belongsTo(Permission::class, 'permission_id', 'id');
    }
}
        