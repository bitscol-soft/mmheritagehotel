<?php

namespace Module\Permission\Models;

use App\Models\User;
use App\Model;
use App\Traits\AutoCreatedUpdated;

class Permission extends Model
{

    use AutoCreatedUpdated;

    protected $table = 'permissions';


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function users()
    {
        return $this->hasMany(PermissionUser::class);
    }

    public function parent_permission()
    {
        return $this->belongsTo(ParentPermission::class);
    }

    public function employee_permission()
    {
        return $this->hasMany(EmployeePermission::class, 'permission_id', 'id');
    }
}
