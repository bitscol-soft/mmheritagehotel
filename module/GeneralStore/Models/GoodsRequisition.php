<?php

namespace Module\GeneralStore\Models;

use App\Models\Company;
use Module\HRM\Models\Department;
use App\Traits\AutoCreatedUpdated;
use App\Model;

class GoodsRequisition extends Model
{
    // fillup created and updated fields, and add created_user, updated_user method
    use AutoCreatedUpdated;

    public function goods_requisition_details()
    {
        return $this->hasMany(GoodsRequisitionDetails::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }


    public function task_notifications()
    {
        return $this->morphMany('Module\HRM\Models\News\TaskNotification', 'taskable');
    }
}
