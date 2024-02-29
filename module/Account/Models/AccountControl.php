<?php


namespace Module\Account\Models;


use App\Traits\AutoCreatedUpdatedWithCompany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountControl extends Model
{
    use AutoCreatedUpdatedWithCompany;

    public function accountGroup(): BelongsTo
    {
        return $this->belongsTo(AccountGroup::class, 'account_group_id');
    }

    public function accountSubsidiaries()
    {
        return $this->hasMany(AccountSubsidiary::class);
    }

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }
}
