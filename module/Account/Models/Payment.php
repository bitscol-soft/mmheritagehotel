<?php


namespace Module\Account\Models;

use App\Models\Group;
use App\Traits\AutoCreatedUpdatedWithCompany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Symfony\Component\CssSelector\Node\FunctionNode;

class Payment extends Model
{
    use AutoCreatedUpdatedWithCompany;

    public function transactions(): MorphMany
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }

    public Function group()
    {
        return $this->belongsTo(Group::class, 'group_id', 'id');
    }
}
