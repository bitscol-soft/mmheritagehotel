<?php


namespace Module\Account\Models;

use App\Models\Company;
use App\Traits\AutoCreatedUpdated;

class Damage extends Model
{
    use AutoCreatedUpdated;

    protected $table = 'acc_damages';



    public function company()
    {
        return $this->belongsTo(Company::class);
    }



    public function details()
    {
        return $this->hasMany(DamageDetail::class, 'damage_id');
    }

    

    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }
}
