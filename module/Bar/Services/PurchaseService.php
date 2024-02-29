<?php

namespace Module\Bar\Services;

use Carbon\Carbon;
use Module\Bar\Models\Stock;
use Module\Bar\Models\Purchase;
use Module\Bar\Models\Supplier;
use Illuminate\Support\Facades\DB;
use Module\Bar\Services\BarTransectionService;

class PurchaseService
{

    public $purchase;


    public $transection_service;


    public function __construct()
    {
        $this->transection_service = new BarTransectionService();
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
            'paid_amount'       => $request->paid_amount ?? 0,
            'due_amount'        => $request->due_amount ?? 0,
            'change_amount'     => $request->change_amount ?? 0,
            'total_vat'         => $request->total_vat
        ]);

        $this->transection_service->setNextInvoiceNo('Bar Purchases', date('Y-m'));

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
     | MANAGE STOCK
     |--------------------------------------------------------------------------
    */
    public function manageStock($request, $qty, $product_id)
    {
        $productStock   = Stock::query()->where('is_bar', 1)->where('product_id', $product_id)->first();
        if ($productStock) {

            $this->stockUpdate($productStock, $qty);

        } else {

            $this->stockCreate($product_id, $qty);

        }
    }










    /*
     |--------------------------------------------------------------------------
     | UPDATE STOCK
     |--------------------------------------------------------------------------
    */
    private function stockUpdate($productStock, $qty)
    {
        $productStock->increment('purchased_quantity', $qty);
    }







    /*
     |--------------------------------------------------------------------------
     | CREATE STOCK
     |--------------------------------------------------------------------------
    */
    private function stockCreate($product_id, $qty)
    {
        $stock = Stock::create([
            'product_id'            => $product_id,
            'opening_quantity'      => 0,
            'purchased_quantity'    => $qty,
            'sold_quantity'         => 0,
            'is_bar'                => 1,
        ]);
    }










    /*
     |--------------------------------------------------------------------------
     | UPDATE TRANSACTION
     |--------------------------------------------------------------------------
    */
    public function updateTransaction()
    {
        (new BarTransectionService)->store(
            $this->purchase,
            $this->purchase->challan_id,
            $this->purchase->account_id ?? defaultAccount()->id,
            request()->grand_total,
            $this->purchase->date,
            'Bar Purchase',
            $this->purchase->id
        );
    }




    /*
     |--------------------------------------------------------------------------
     | PURCHASE DELETE
     |--------------------------------------------------------------------------
    */
    public function delete($id)
    {
        DB::transaction(function() use($id){
            $purchase = Purchase::where('id', $id)->with('purchase_details')->first();

            foreach ($purchase->purchase_details as $key => $item) {
                $stock =  Stock::query()->where('is_bar', 1)->where('product_id', $item->product_id)->first();
                $stock->decrement('purchased_quantity', $item->quantity);

                $item->delete();
            }
            $purchase->delete();
        });
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
