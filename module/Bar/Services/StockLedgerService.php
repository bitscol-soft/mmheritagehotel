<?php
namespace Module\Bar\Services;

use Module\Bar\Models\ProductLedger;

class StockLedgerService
{
    public function stockLedger($source_id,$source_type, $product_id, $in, $out, $is_bar, $company_id)
    {
        ProductLedger::create([
            'product_id'        => $product_id,
            'company_id'        => $company_id,
            'sourceable_type'   => $source_type,
            'sourceable_id'     => $source_id,
            'quantity'          => $in > 0 ? $in : $out,
            'in'                => $in,
            'out'               => $out,
            'date'              => date('Y-m-d'),
            'wastage'           => 0,
            'created_by'        => auth()->id(),
            'updated_by'        => auth()->id(),
            'is_bar'            => $is_bar,
        ]);
    }
}