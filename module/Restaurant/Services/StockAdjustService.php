<?php

namespace Module\Restaurant\Services;

use Carbon\Carbon;
use Module\Restaurant\Models\Stock;
use Module\Restaurant\Models\Purchase;
use Module\Restaurant\Models\Supplier;
use Module\Restaurant\Models\StockAdjustment;
use Module\Bar\Services\BarTransectionService;

class StockAdjustService
{

    public $adjustment;
    public $transection_service;
    public $invoice_no;


    public function __construct()
    {
        $this->transection_service = new BarTransectionService();
        $this->invoice_no   = $this->transection_service->getInvoiceNo('Stock Adjust');
        // $this->transection_service = new ResturentTransectionService();
    }
        // $this->sale->update([
        //             'invoice_no' => $this->invoice_no,
        //         ]);


    /*
     |--------------------------------------------------------------------------
     | STORE Adjustment
     |--------------------------------------------------------------------------
    */
    public function storeAdjustment()
    {
        $request = \request();

        if($request->current_status == 'Approved'){
            $this->adjustment = StockAdjustment::query()->create([

                'company_id'        => auth()->user()->company_id,
                'invoice_no'        => $this->invoice_no,
                'supplier_id'       => $request->supplier_id,
                'total_qty'         => $request->total_quantity,
                'total_amount'      => $request->subtotal,
                'note'              => $request->note ?? null,
                'date'              => $request->date,
                'is_bar'            => $request->is_bar ?? 1,
                'current_status'    => 'Approved',
                'approve_date'      => date('Y-m-d'),
                'cancel_date'       => $request->cancel_date ?? null,
                'approved_by'       => auth()->id(),
                'canceled_by'       => null,
                'created_by'        => auth()->id(),
                'updated_by'        => auth()->id(),
            ]);
        }else{
            $this->adjustment = StockAdjustment::query()->create([

                'company_id'        => auth()->user()->company_id,
                'invoice_no'        => $this->invoice_no,
                'supplier_id'       => $request->supplier_id,
                'total_qty'         => $request->total_quantity,
                'total_amount'      => $request->subtotal,
                'note'              => $request->note ?? null,
                'date'              => $request->date,
                'is_bar'            => $request->is_bar ?? 1,
                'current_status'    => 'Pending',
                'approve_date'      => $request->approve_date ?? null,
                'cancel_date'       => $request->cancel_date ?? null,
                'approved_by'       => null,
                'canceled_by'       => null,
                'created_by'        => auth()->id(),
                'updated_by'        => auth()->id(),
            ]);
        }

        $this->transection_service->setNextInvoiceNo('Stock Adjust', date('Y-m'));

    }






    /*
     |--------------------------------------------------------------------------
     | STORE Adjustment DETAILS
     |--------------------------------------------------------------------------
    */
    public function storeAdjustmentDetails()
    {
        $request = \request();

        foreach ($request->product_id as $key => $id) {

            $this->adjustment->adjustment_details()->create([
                'product_id'               => $id,
                'supplier_id'              => $request->supplier_id[$key] ?? null,
                'expire_date'              => $request->expire_date[$key] ?? null,
                'stock_adjustment_id'      => $this->adjustment->id,
                'purchase_price'           => $request->purchase_price[$key],
                'quantity'                 => $request->approvrd_quantity[$key],
                'stock_type'               => $request->stock_type[$key],
                'adjustment_reason'        => $request->adjustment_reason[$key],
                'is_bar'                   => $request->is_bar[$key] ?? 1,
                'status'                   => $request->status[$key] ?? 1,
            ]);

            if($request->current_status == 'Approved' ){

                $this->manageStock($request->stock_type[$key], $request->approvrd_quantity[$key], $id);
            }

        }
    }






    public function UpdateAdjustment(){
            $request = \request();

            foreach ($request->product_id as $key => $id) {
            $this->adjustment = StockAdjustment::find($request->adjustment_id);
            $this->adjustment->update(
                [
                'current_status'    => 'Approved',
                'approve_date'      => date('Y-m-d'),
                'cancel_date'       => null,
                'approved_by'       => auth()->id(),
                'canceled_by'       => null,
                'created_by'        => auth()->id(),
                'updated_by'        => auth()->id(),
                ]
            );
            $this->manageStock($request->stock_type[$key], $request->quantity[$key], $id);
        }
    }




    /*
     |--------------------------------------------------------------------------
     | MANAGE STOCK
     |--------------------------------------------------------------------------
    */
    public function manageStock($stock_type, $qty, $product_id)
    {
        $productStock   = Stock::query()->where('company_id', auth()->user()->company_id)
                        ->where('product_id', $product_id)->first();

        if ($productStock) {
            $this->stockUpdate($stock_type, $productStock, $qty);
        } else {
            $this->stockCreate($stock_type, $qty);
        }
    }



    /*
     |--------------------------------------------------------------------------
     | CANCEL STOCK
     |--------------------------------------------------------------------------
    */
    public function stockCancel($qty, $product_id)
    {

        $productStock   = Stock::query()
                        ->where('product_id', $product_id)->first();

        if ($productStock) {
                $productStock->decrement('purchased_quantity', $qty);

            }
    }










    /*
     |--------------------------------------------------------------------------
     | UPDATE STOCK
     |--------------------------------------------------------------------------
    */
    private function stockUpdate($stock_type, $productStock, $qty)
    {

        if($stock_type == 'In'){
            $productStock->increment('purchased_quantity', $qty);
        }else{
            $productStock->increment('sold_quantity', $qty);

        }
        // if($request->stock_type = 'Out'){
            // $productStock->decrement('purchased_quantity', $qty);
        // }
        // $productStock->increment('available_quantity', $qty);
    }




    /*
     |--------------------------------------------------------------------------
     | CREATE STOCK
     |--------------------------------------------------------------------------
    */
    private function stockCreate($request, $key)
    {
        Stock::create([
            'product_id'            => $request->product_id[$key],
            'opening_quantity'      => 0,
            'purchased_quantity'    => $request->approvrd_quantity[$key],
            'sold_quantity'         => 0,
            // 'available_quantity'    => $request->approvrd_quantity[$key],
        ]);
    }










    /*
     |--------------------------------------------------------------------------
     | UPDATE TRANSACTION
     |--------------------------------------------------------------------------
    */
    public function updateTransaction()
    {
        (new TransactionService)->storeTransaction(

            $this->adjustment,
            $this->adjustment->challan_id,
            $this->adjustment->account_id ?? defaultAccount()->id,
            request()->subtotal,
            $this->adjustment->date,
            'Bar Adjustment',
            $this->adjustment->id
        );

        // (new HotelTransactionService())->storeTransaction(
        //     $this->sale,
        //     $source_id,
        //     request('is_bar') == 1 ? 'Bar Sale' : 'Restaurant Sale',
        //     $account_type_id, // AccountType::first()->id,
        //     $payable_amount,
        //     $discount,
        //     $paid_amount,
        //     request('vat') ?? request('vat_amount') ?? 0,
        //     request('service_charge') ?? request('service_amount') ?? 0,
        //     $booking_id,
        //     $this->invoice_no,
        //     fdate($this->sale->date ?? date('Y-m-d'),'Y-m-d'),
        // );
    }












    /*
     |--------------------------------------------------------------------------
     | UPDATE LEDGER
     |--------------------------------------------------------------------------
    */
    public function updateSuplierBalance($request)
    {
        // supplier balance update
        // $supplier = Supplier::find($request->supplier_id);


        // $supplier->update(['previous_due' => $supplier->current_balance]);
        // $supplier->increment('current_balance', $request->due_amount ?? 0);
    }


      /*
     |--------------------------------------------------------------------------
     | DELETE STOCK ADJUSTEMNT
     |--------------------------------------------------------------------------
    */

    function deleteAdjustment($id){
        $stockAdjustment = StockAdjustment::with('adjustment_details')->find($id);
        foreach($stockAdjustment->adjustment_details as $key => $stock) {
            $this->stockCancel($stock->quantity, $stock->product_id);
        }
        $stockAdjustment->adjustment_details->each->delete();

        $stockAdjustment->delete();
    }

}
