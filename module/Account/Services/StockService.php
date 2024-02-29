<?php

namespace Module\Account\Services;

use Module\Account\Models\PurchaseDetail;
use Module\Account\Models\SaleDetail;
use Module\Account\Models\Stock;
use Module\Account\Models\StockSummary;

class StockService
{
    /*
    |--------------------------------------------------------------------------
    | CREATE STOCK METHOD
    |--------------------------------------------------------------------------
    */
    public function createStock($source, $company_id, $branch_id, $warehouse_id, $product_id, $stock_type, $date, $price, $quantity, $actual_qty)
    {
        $source->stock()->create([
            'company_id'        => $company_id,
            'branch_id'         => $branch_id,
            'warehouse_id'      => $warehouse_id,
            'product_id'        => $product_id,
            'stock_type'        => $stock_type,
            'date'              => $date,
            'price'             => $price,
            'quantity'          => $quantity,
            'actual_qty'        => $actual_qty,
        ]);
    }




    /*
    |--------------------------------------------------------------------------
    | UPDATE STOCK METHOD
    |--------------------------------------------------------------------------
    */
    public function updateStock($product_id, $company_id, $branch_id, $warehouse_id, $batch_number, $source_type, $source_id, $stock_type, $price, $quantity, $total_amount)
    {
        Stock::where([
            'product_id'        => $product_id,
            'company_id'        => $company_id,
            'branch_id'         => $branch_id,
            'warehouse_id'      => $warehouse_id,
            'batch_number'      => $batch_number,
            'source_type'       => $source_type,
            'source_id'         => $source_id,
            'stock_type'        => $stock_type,
        ]) ->update([
            'price'             => $price,
            'quantity'          => $quantity,
            'total_amount'      => $total_amount,
        ]);
    }




    /*
    |--------------------------------------------------------------------------
    | DELETE STOCK METHOD
    |--------------------------------------------------------------------------
    */
    public function deleteStock($product_id, $stockable_type, $stockable_id, $stock_type)
    {
        Stock::where([
            'product_id'        => $product_id,
            'stockable_type'    => $stockable_type,
            'stockable_id'      => $stockable_id,
            'stock_type'        => $stock_type,
        ])->delete();
    }




    /*
    |--------------------------------------------------------------------------
    | STOCK SUMMARY METHOD
    |--------------------------------------------------------------------------
    */
    public function stockSummary($product_id, $company_id, $branch_id, $warehouse_id)
    {
        $getPurchaseData = Stock::select('id', 'quantity', 'amount')
            ->where([
                'product_id'        => $product_id,
                'company_id'        => $company_id,
                'branch_id'         => $branch_id,
                'warehouse_id'      => $warehouse_id,
                'stockable_type'    => PurchaseDetail::class,
                'stock_type'        => 'In',
            ])
            ->get();

        $getSaleData = Stock::select('quantity', 'amount')
            ->where([
                'product_id'        => $product_id,
                'company_id'        => $company_id,
                'branch_id'         => $branch_id,
                'warehouse_id'      => $warehouse_id,
                'stockable_type'    => SaleDetail::class,
                'stock_type'        => 'Out',
            ])
            ->get();

        $purchaseQty = 0;
        $purTotalAmount = 0;
        if (!empty($getPurchaseData)) {
            foreach ($getPurchaseData as $val) {
                $purchaseQty += $val->quantity;
                $purTotalAmount += $val->amount;
            }
        }


        $saleQty = 0;
        $saleTotalAmount = 0;
        if (!empty($getSaleData)) {
            foreach ($getSaleData as $val) {
                $saleQty += $val->quantity;
                $saleTotalAmount += $val->amount;
            }
        }


        $openingQty = 0;
        $issueQty = 0;
        $purchaseReturnQty = 0;
        $saleReturnQty = 0;
        $transferInQty = 0;
        $transferOutQty = 0;
        $totalAmount = $saleTotalAmount - $purTotalAmount;


        // UPDATE OR CREATE STOCK SUMMARY
        $this->updateOrCreateStockSummary($product_id, $company_id, $branch_id, $warehouse_id, $openingQty, $purchaseQty, $saleQty, $issueQty, $purchaseReturnQty, $saleReturnQty, $transferInQty, $transferOutQty, $totalAmount);
    }




    /*
    |--------------------------------------------------------------------------
    | UPDATE OR CREATE STOCK SUMMARY METHOD
    |--------------------------------------------------------------------------
    */
    protected function updateOrCreateStockSummary($product_id, $company_id, $branch_id, $warehouse_id, $opening_qty, $purchase_qty, $sale_qty, $issue_qty, $purchase_return_qty, $sale_return_qty, $transfer_in_qty, $transfer_out_qty, $total_amount)
    {
        $stockSummary = StockSummary::updateOrCreate([
            'product_id'                => $product_id,
            'company_id'                => $company_id,
            'branch_id'                 => $branch_id,
            'warehouse_id'              => $warehouse_id,
        ],
            [
                'opening_qty'               => $opening_qty ?? 0,
                'purchase_qty'              => $purchase_qty ?? 0,
                'sale_qty'                  => $sale_qty ?? 0,
                'issue_qty'                 => $issue_qty ?? 0,
                'purchase_return_qty'       => $purchase_return_qty ?? 0,
                'sale_return_qty'           => $sale_return_qty ?? 0,
                'transfer_in_qty'           => $transfer_in_qty ?? 0,
                'transfer_out_qty'          => $transfer_out_qty ?? 0,
                'total_amount'              => $total_amount,
            ]);
    }
}
