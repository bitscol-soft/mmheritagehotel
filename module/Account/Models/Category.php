<?php


namespace Module\Account\Models;


use App\Traits\AutoCreatedUpdatedWithCompany;

class Category extends Model
{
    use AutoCreatedUpdatedWithCompany;

    protected $table = 'acc_categories';
}
