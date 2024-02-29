<?php

namespace Module\Restaurant\Services;

use Module\Restaurant\Models\Stock;
use Module\Restaurant\Models\Supplier;
use Module\Restaurant\Models\RstProduction;
use Module\HotelService\Services\HotelTransactionService;
use Module\Restaurant\Services\ResturentTransectionService;

class ProductionService
{

    public $production;
    public $rst_production;
    public $transection_service;


    public function __construct()
    {
        $this->transection_service = new ResturentTransectionService();
    }


    /*
     |--------------------------------------------------------------------------
     | STORE PURCHASE
     |--------------------------------------------------------------------------
    */
    public function storeProduction()
    {
        $request = \request();

        $this->production = RstProduction::query()->create([

            'date'              => $request->date,
            'challan_no'        => $request->challan_id,
            'is_approved'       => 1,
        ]);

        //--------- SET INVOICE NO ---------//
        $this->transection_service->setNextInvoiceNo('Rest Production', date('Y-m'));
    }









    /*
     |--------------------------------------------------------------------------
     | STORE Material DETAILS
     |--------------------------------------------------------------------------
    */
    public function storeMaterialDetails()
    {
        $request = \request();

        foreach ($request->material_id as $key => $id) {

            $this->production->metrial_details()->create([
                'product_id'            => $id,
                'quantity'              => $request->material_quantity[$key],
            ]);

            $this->manageStock($request, $request->material_quantity[$key], $id);
        }
    }


    /*
     |--------------------------------------------------------------------------
     | STORE PRODUCT DETAILS
     |--------------------------------------------------------------------------
    */
    public function storeProductDetails()
    {

        $request = \request();

        foreach ($request->item_id as $key => $id) {

            $this->production->fgood_details()->create([
                'product_id'            => $id,
                'quantity'              => $request->item_quantity[$key],
            ]);


            $this->manageStock($request, $request->item_quantity[$key], $id);
        }
    }







    /*
     |--------------------------------------------------------------------------
     | STORE Rest ApprovedPurchase
     |--------------------------------------------------------------------------
    */
    // public function ApprovedPurchase()
    // {
    //     $request = \request();
    //     $purchase =  RstPurchase::find($request->purchase_id);
    //     foreach ($request->product_id as $key => $id) {


    //         $purchase->update([
    //             'is_approved'       => 1

    //         ]);


    //         $this->manageStock($request, $request->quantity[$key], $id);
    //     }
    // }







    /*
     |--------------------------------------------------------------------------
     | MANAGE STOCK
     |--------------------------------------------------------------------------
    */
    public function manageStock($request, $qty, $product_id)
    {
        $productStock   = Stock::query()->where('product_id', $product_id)->first();

        if ($productStock) {

            $this->stockUpdate($productStock, $qty);
        } else {
            $this->stockCreate($request,  $qty);
        }
    }







    /*
     |--------------------------------------------------------------------------
     | UPDATE STOCK
     |--------------------------------------------------------------------------
    */
    private function stockUpdate($productStock, $qty)
    {
        // $productStock->increment('available_quantity', $qty);
        $productStock->decrement('purchased_quantity', $qty);
    }







    /*
     |--------------------------------------------------------------------------
     | CREATE STOCK
     |--------------------------------------------------------------------------
    */
    private function stockCreate($request,  $key)
    {
        Stock::create([
            'product_id'            => $request->product_id[$key],
            'opening_quantity'      => 0,
            'purchased_quantity'    => $request->quantity[$key],
            'available_quantity'    => $request->quantity[$key],
            'sold_quantity'         => 0,
        ]);
    }






    /*
     |--------------------------------------------------------------------------
     | UPDATE TRANSACTION
     |--------------------------------------------------------------------------
    */
    // public function updateTransaction()
    // {
    //     (new HotelTransactionService)->storeTransaction(

    //         $this->production,
    //         $this->production->id,
    //         'Rst Material Purchases',
    //         request()->account_id,
    //         request()->grand_total,
    //         0,
    //         request()->paid_amount ?? 0, // collection
    //         0,
    //         0,
    //         null,
    //         $this->production->challan_id,
    //         $this->production->date,
    //         'Restaurant Production',
    //     );
    // }




    /*
     |--------------------------------------------------------------------------
     | UPDATE TRANSACTION
     |--------------------------------------------------------------------------
    */
    // public function updateTransactionOLD()
    // {
    //     (new TransactionService)->storeTransaction(

    //         $this->purchase,
    //         $this->purchase->challan_id,
    //         $this->purchase->account_id ?? defaultAccount()->id,
    //         request()->grand_total,
    //         $this->purchase->date,
    //         'Pharmacy Purchase',
    //         $this->purchase->id
    //     );
    // }





    /*
     |--------------------------------------------------------------------------
     | UPDATE LEDGER
     |--------------------------------------------------------------------------
    */
    // public function updateSuplierBalance($request)
    // {
    //     // supplier balance update
    //     $supplier = Supplier::find($request->supplier_id);


    //     $supplier->update(['previous_due' => $supplier->current_balance]);
    //     $supplier->increment('current_balance', $request->due_amount ?? 0);
    // }
}
