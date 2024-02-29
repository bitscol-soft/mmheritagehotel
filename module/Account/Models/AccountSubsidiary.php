<?php


namespace Module\Account\Models;


use App\Traits\AutoCreatedUpdatedWithCompany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountSubsidiary extends Model
{
    use AutoCreatedUpdatedWithCompany;

    public function accountGroup(): BelongsTo
    {
        return $this->belongsTo(AccountGroup::class, 'account_group_id');
    }

    public function accountControl(): BelongsTo
    {
        return $this->belongsTo(AccountControl::class, 'account_control_id');
    }

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }
}
