<?php

namespace Module\Restaurant\Services;

use Carbon\Carbon;
use Module\Restaurant\Models\Stock;
use Module\Restaurant\Models\Purchase;
use Module\Restaurant\Models\Supplier;
use Module\Restaurant\Models\RstPurchase;
use Module\Account\Services\InvoiceNumberService;
use Module\HotelService\Services\HotelTransactionService;

class PurchaseService
{

    public $purchase;
    public $rst_purchase;

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
    public function storePurchase()
    {
        $request = \request();

        $this->purchase = Purchase::query()->create([

            'supplier_id'       => $request->supplier_id,
            'date'              => $request->date,
            'challan_id'        => $request->challan_id,
            'subtotal'          => $request->subtotal,
            'payable_amount'    => $request->grand_total,
            'discount'          => $request->discount,
            'paid_amount'       => $request->paid_amount,
            'due_amount'        => $request->due_amount ?? 0,
            'change_amount'     => $request->change_amount ?? 0,
            'total_vat'         => $request->total_vat
        ]);

        $this->transection_service->setNextInvoiceNo('Rest Purchases', date('Y-m'));
    }









    /*
     |--------------------------------------------------------------------------
     | STORE PURCHASE DETAILS
     |--------------------------------------------------------------------------
    */
    public function storePurchaseDetails()
    {
        $request = \request();

        foreach ($request->product_id as $key => $id) {

            $total_qty = $request->sale_price[$key] * $request->quantity[$key];

            $this->purchase->purchase_details()->create([
                'product_id'            => $id,
                'quantity'              => $request->quantity[$key],
                'item_price'            => $request->sale_price[$key],
                'subtotal'              => $total_qty,
                'unit_vat_amount'       => $request->unit_vat[$key]
            ]);



            $this->manageStock($request, $request->quantity[$key], $id);
        }
    }











    /*
     |--------------------------------------------------------------------------
     | STORE Rest Metrial PURCHASE
     |--------------------------------------------------------------------------
    */
    public function storeRstPurchase()
    {
        $request = \request();

        $this->rst_purchase = RstPurchase::query()->create([

            'supplier_id'       => $request->supplier_id,
            'date'              => $request->date,
            'challan_id'        => $request->challan_id,
            'subtotal'          => $request->subtotal,
            'payable_amount'    => $request->grand_total,
            'discount'          => $request->discount,
            'paid_amount'       => $request->paid_amount,
            'due_amount'        => $request->due_amount ?? 0,
            'change_amount'     => $request->change_amount ?? 0,
            'total_vat'         => $request->total_vat
        ]);


    }









    /*
     |--------------------------------------------------------------------------
     | STORE Rest Metrial PURCHASE DETAILS
     |--------------------------------------------------------------------------
    */
    public function storeRstPurchaseDetails()
    {
        $request = \request();

        foreach ($request->product_id as $key => $id) {

            $total_qty = $request->sale_price[$key] * $request->quantity[$key];

            $this->rst_purchase->purchase_details()->create([
                'product_id'            => $id,
                'quantity'              => $request->quantity[$key],
                'item_price'            => $request->sale_price[$key],
                'subtotal'              => $total_qty,
                'unit_vat_amount'       => $request->unit_vat[$key]
            ]);



            // $this->manageStock($request, $request->quantity[$key], $id);
        }
    }



    /*
     |--------------------------------------------------------------------------
     | STORE Rest ApprovedPurchase
     |--------------------------------------------------------------------------
    */
    public function ApprovedPurchase()
    {
        $request = \request();
        $purchase =  RstPurchase::find($request->purchase_id);
        foreach ($request->product_id as $key => $id) {


            $purchase->update([
                'is_approved'       => 1

            ]);


            $this->manageStock($request, $request->quantity[$key], $id);
        }
    }


    /*
     |--------------------------------------------------------------------------
     | STORE Rest ApprovedPurchase
     |--------------------------------------------------------------------------
    */
    public function ApprovedRstPurchase()
    {
        $request = \request();
        $purchase =  RstPurchase::find($request->purchase_id);
        foreach ($request->product_id as $key => $id) {


            $purchase->update([
                'is_approved'       => 1

            ]);


            $this->manageStock($request, $request->quantity[$key], $id);
        }
    }





    /*
     |--------------------------------------------------------------------------
     | MANAGE STOCK
     |--------------------------------------------------------------------------
    */
    public function manageStock($request, $qty, $product_id)
    {
        $productStock   = Stock::query()->where('company_id', auth()->user()->company_id)->where('product_id', $product_id)->first();

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
        $productStock->increment('purchased_quantity', $qty);
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
     | UPDATE RST TRANSACTION
     |--------------------------------------------------------------------------
    */
    public function updateRstTransaction()
    {
        (new HotelTransactionService)->storeTransaction(

            $this->purchase,
            $this->purchase->id,
            'Purchase',
            request()->account_id,
            request()->grand_total,
            0,
            request()->paid_amount ?? 0, // collection
            0,
            0,
            null,
            $this->purchase->challan_id,
            $this->purchase->date,
            'Restaurant Purchases',
        );
    }


    /*
     |--------------------------------------------------------------------------
     | UPDATE TRANSACTION
     |--------------------------------------------------------------------------
    */
    public function updateTransaction()
    {
        (new HotelTransactionService)->storeTransaction(

            $this->rst_purchase,
            $this->rst_purchase->id,
            'Rst Material Purchases',
            request()->account_id,
            request()->grand_total,
            0,
            request()->paid_amount ?? 0, // collection
            0,
            0,
            null,
            $this->rst_purchase->challan_id,
            $this->rst_purchase->date,
            'Restaurant Material Purchases',
        );
    }






    /*
     |--------------------------------------------------------------------------
     | UPDATE TRANSACTION
     |--------------------------------------------------------------------------
    */
    public function updateTransactionOLD()
    {
        (new TransactionService)->storeTransaction(

            $this->purchase,
            $this->purchase->challan_id,
            $this->purchase->account_id ?? defaultAccount()->id,
            request()->grand_total,
            $this->purchase->date,
            'Pharmacy Purchase',
            $this->purchase->id
        );
    }













    /*
     |--------------------------------------------------------------------------
     | UPDATE LEDGER
     |--------------------------------------------------------------------------
    */
    public function updateSuplierBalance($request)
    {
        // supplier balance update
        $supplier = Supplier::find($request->supplier_id);


        $supplier->update(['previous_due' => $supplier->current_balance]);
        $supplier->increment('current_balance', $request->due_amount ?? 0);
    }
}
