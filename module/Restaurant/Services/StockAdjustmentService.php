<?php

namespace Module\Restaurant\Services;


use App\Traits\Helper;
use App\Traits\FileSaver;
use Module\Restaurant\Models\Purchase;
use App\Services\NextInvoiceNoService;
use Illuminate\Support\Facades\DB;
use Module\Product\Services\StockService;
use Module\Restaurant\Models\StockTransfer;
use Module\Restaurant\Models\PurchaseDetail;
use Module\Restaurant\Models\StockAdjustment;
use Module\Restaurant\Models\StockTransferDetail;
use Module\Restaurant\Services\TransactionService;
use Module\Restaurant\Models\StockAdjustmentDetail;
use Module\Restaurant\Services\DynamicLotManageService;

class StockAdjustmentService
{
    use FileSaver, Helper;
    public $stockAdjustment;
    public $grn;
    public $stockService;
    public $transactionService;
    public $nextInvoiceNumberService;
    public $dynamicLotManageService;




    public function __construct()
    {
        $this->stockService                 = new StockService;
        $this->transactionService           = new TransactionService;
        $this->nextInvoiceNumberService     = new NextInvoiceNoService;
        $this->dynamicLotManageService      = new DynamicLotManageService;

    }








   /*
     |--------------------------------------------------------------------------
     | STOCK ADJUSTEMNT STORE
     |--------------------------------------------------------------------------
    */

    /*----------- ADJUSTMENT -----------*/
    public function createStockAdjustment($request)
    {
        $this->stockAdjustment = StockAdjustment::create(
        [
            'company_id'                        => $request->company_id ?? optional(auth()->user())->company_id,
            'invoice_no'                        => $this->nextInvoiceNumberService->getStockTransferInvoiceNo($request->company_id ?? optional(auth()->user())->company_id, $request->from_warehouse_id),
            'warehouse_id'                      => $request->warehouse_id,
            'date'                              => $request->date,
            'total_quantity'                    => $request->total_quantity ?? 0,
            'total_amount'                      => $request->total_amount ?? 0,
            // 'total_amount'                      => $request->total_amount ?? 0,
            'current_status'                    => 'Pending',
            'note'                              => $request->note ?? null,
        ]);
    }

    /*----------- ADJUSTMENT DETAILS -----------*/
    public function createStockAdjustmentDetails($request)
    {
        foreach ($request->product_id as $key => $product_id) {

            $this->stockAdjustmentDetailsCreate($request, $product_id, $key);
        }

    }

    public function stockAdjustmentDetailsCreate($request, $product_id, $key)
    {
        StockAdjustmentDetail::create([
            'supplier_id'               => $request->supplier_id[$key] != 'null' ? $request->supplier_id[$key] : NULL,
            'lot'                       => $request->lot[$key] ?? NULL,
            'expire_date'               => $request->expire_date[$key] != 'null' ? $request->expire_date[$key] : NULL,
            'stock_adjustment_id'       => $this->stockAdjustment->id,
            'product_id'                => $product_id,
            'product_variation_id'      => $request->product_variation_id[$key],
            'purchase_price'            => $request->purchase_price[$key],
            'quantity'                  => $request->quantity[$key] ?? 0,
            'stock_type'                => $request->stock_type,
            'adjustment_reason'         => $request->adjustment_reason,
        ]);
    }

    /*----------- END STORE STOCK ADJUSTEMNT -----------*/












    /*
     |--------------------------------------------------------------------------
     | APPROVE STOCK ADJUSTEMNT
     |--------------------------------------------------------------------------
    */

    /*----------- APPROVE ADJUSTMENT -----------*/
    public function approveStockAdjustment($request, $stockTransfer)
    {
        $stockTransfer->update([
            'approve_date'              => date('Y-m-d'),
            'approved_by'               => auth()->id(),
            'current_status'            => 'Approved',
            'total_quantity'            => $request->total_quantity,
        ]);
    }


    /*----------- APPROVE ADJUSTMENT DETAILS -----------*/
    public function approveStockAdjustmentDetails($request, $stockTransfer)
    {
        foreach($request->stock_transfer_detail_id as $key => $stock_transfer_detail_id) {

            $stockTransferDetail = StockTransferDetail::find($stock_transfer_detail_id);

            $stockTransferDetail->update([
                'approved_quantity' => $request->approved_quantity[$key]
            ]);

            $this->dynamicLotManageService->stockOut($stockTransferDetail, $request->approved_quantity[$key], $stockTransfer->company_id, $stockTransfer->from_warehouse_id, $stockTransferDetail->product_id, $stockTransferDetail->product_variation_id, $stockTransfer->invoice_no, 0);
        }
    }

    /*----------- END APPROVE STOCK ADJUSTEMNT -----------*/










    /*
     |--------------------------------------------------------------------------
     | DELETE STOCK ADJUSTEMNT
     |--------------------------------------------------------------------------
    */

    function deleteAdjustment($id){
        $stockAdjustment = StockAdjustment::with('stock_adjustment_details')->find($id);
        foreach($stockAdjustment->stock_adjustment_details as $key => $stockAdjustmentDetail) {
            $this->dynamicLotManageService->stockCancel($stockAdjustmentDetail, $stockAdjustmentDetail->quantity, $stockAdjustment->company_id, $stockAdjustment->warehouse_id, $stockAdjustmentDetail->product_id, $stockAdjustmentDetail->product_variation_id, $stockAdjustmentDetail->invoice_no, 0, 'Module\Inventory\Models\StockAdjustmentDetail');
        }
        $stockAdjustment->stock_adjustment_details->each->delete();
        $stockAdjustment->delete();
    }








    /*
     |--------------------------------------------------------------------------
     | CANCEL STOCK ADJUSTEMNT
     |--------------------------------------------------------------------------
    */
    function cancelAdjustment($id){

        DB::transaction(function () use ($id) {

            $stockAdjustment = StockAdjustment::with('stock_adjustment_details')->find($id);

            foreach($stockAdjustment->stock_adjustment_details as $key => $stockAdjustmentDetail)
            {
                // $this->dynamicLotManageService->stockCancel($stockAdjustmentDetail, $stockAdjustmentDetail->quantity, $stockAdjustment->company_id, $stockAdjustment->warehouse_id, $stockAdjustmentDetail->product_id, $stockAdjustmentDetail->product_variation_id, $stockAdjustmentDetail->invoice_no, 0, 'Module\Inventory\Models\StockAdjustmentDetail');
                $this->dynamicLotManageService->stockCancel($stockAdjustmentDetail, $stockAdjustmentDetail->quantity, $stockAdjustment->company_id, $stockAdjustment->warehouse_id, $stockAdjustmentDetail->product_id, $stockAdjustmentDetail->product_variation_id, $stockAdjustmentDetail->invoice_no, 0, 'Stock Adjustemt');
            }

            $stockAdjustment->update([
                'current_status' => 'Cancelled'
            ]);

        });


    }
}
