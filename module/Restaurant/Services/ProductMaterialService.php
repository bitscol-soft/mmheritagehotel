<?php

namespace Module\Restaurant\Services;

use Carbon\Carbon;
use Module\Restaurant\Models\Stock;
use Module\Restaurant\Models\Supplier;
use Module\Restaurant\Models\PrductMetrial;
use Module\Restaurant\Models\StockAdjustment;
use Module\Bar\Services\BarTransectionService;

class ProductMaterialService
{

    public $ProductMaterial;
    public $transection_service;
    public $invoice_no;


    public function __construct()
    {
        // $this->transection_service = new BarTransectionService();
        // $this->invoice_no   = $this->transection_service->getInvoiceNo('Stock Adjust');
        // $this->transection_service = new ResturentTransectionService();
    }


    /*
     |--------------------------------------------------------------------------
     | STORE
     |--------------------------------------------------------------------------
    */
    public function store()
    {
        $request = \request();
        foreach ($request->material_id as $key => $material) {
            $this->ProductMaterial = PrductMetrial::query()->create([

                'product_id'        => $request->product_id,
                'material_id'       => $material,
                'category_id'       => $request->category_id[$key],
                'units_id'          => $request->unit_id[$key],
                'quantity'          => $request->quantity[$key],
                'item_price'        => $request->price[$key] ?? null,
                'unit_vat_amount'   => $request->unit_vat_amount[$key] ?? 0,
                'remarks'           => $request->remarks[$key] ?? null,
                'is_bar'            => $request->is_bar ?? 0,
                'status'            => 1,
                'created_by'        => auth()->id(),
                'updated_by'        => auth()->id(),
            ]);

        }


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
