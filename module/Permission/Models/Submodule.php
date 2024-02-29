<?php

namespace Module\Permission\Models;

use App\Model;

class Submodule extends Model
{

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function parent_permissions ()
    {
        return $this->hasMany(ParentPermission::class);
    }
}
