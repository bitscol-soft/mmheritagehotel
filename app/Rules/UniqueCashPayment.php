<?php

namespace App\Rules;

use App\Models\Payment\CashPayment;
use Illuminate\Contracts\Validation\Rule;

class UniqueCashPayment implements Rule
{
    private $ignoreId, $date, $orderTypeIds, $supplierId;

    public function __construct($date, $supplierId, $orderTypeIds, $ignoreId = null)
    {
        $this->date = $date;
        $this->ignoreId = $ignoreId;
        $this->supplierId = $supplierId;
        $this->orderTypeIds = $orderTypeIds;
    }

    public function passes($attribute, $value): bool
    {
        return CashPayment::query()
            ->whereIn('order_type_id', $this->orderTypeIds)
            ->where('date', $this->date)
            ->where('supplier_id', $this->supplierId)
            ->where('bill_no', $value)
            ->when($this->ignoreId, function ($q) {
                $q->where('id', '<>', $this->ignoreId);
            })
            ->count() == 0;
    }

    public function message(): string
    {
        return 'Bill No & Bill Date can not be same for the supplier. Pls check.';
    }
}
