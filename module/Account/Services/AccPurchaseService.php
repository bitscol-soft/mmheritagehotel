<?php


namespace Module\Account\Services;


use Module\Account\Models\Purchase;
use Module\Account\Models\PurchaseDetail;
use Module\Production\Models\RequsitionPurchaseDetails;

class AccPurchaseService
{
    public $invoiceNumberService;
    public $transactionService;
    public $stockService;



    public $purchase;

    public $productStockService;









    /*
     |--------------------------------------------------------------------------
     | CONSTRUCTOR
     |--------------------------------------------------------------------------
    */
    public function __construct()
    {
        $this->invoiceNumberService = new InvoiceNumberService();
        $this->transactionService   = new AccountTransactionService();
        $this->stockService         = new StockService();
        $this->productStockService  = new ProductStockService();
    }











    /*
     |--------------------------------------------------------------------------
     | VALIDATE DATA
     |--------------------------------------------------------------------------
    */
    public function validateData($request)
    {

        $request->validate([

            'supplier_id'       => 'required',
            'company_id'        => 'required',
            'product_id.*'      => 'required',
            'quantity.*'        => 'required',
        ]);
    }












    /*
     |--------------------------------------------------------------------------
     | STORE PURCHASE
     |--------------------------------------------------------------------------
    */
    public function storePurchase($request)
    {
        $this->purchase = Purchase::create([

            'supplier_id'       => $request->supplier_id,
            'date'              => $request->date,
            'invoice_no'        => $this->invoiceNumberService->getPurchaseInvoiceNo($request->company_id),
            'qty_total'         => $request->qty_total ?? 0,
            'qty_amount'        => $request->qty_amount ?? 0,
            'discount_amount'   => $request->discount_amount ?? 0,
            'total_amount'      => $request->total_amount ?? 0,
            'paid_amount'       => $request->paid_amount ?? 0,
            'due_amount'        => $request->due_amount ?? 0,
            'company_id'        => $request->company_id
        ]);
    }












    /*
     |--------------------------------------------------------------------------
     | UPDATE PURCHASE
     |--------------------------------------------------------------------------
    */
    public function updatePurchase($request, $id)
    {

        $this->purchase = Purchase::with('details')->find($id);

        $this->purchase->update([

            'supplier_id'       => $request->supplier_id,
            'date'              => $request->date,
            'qty_total'         => $request->qty_total ?? 0,
            'qty_amount'        => $request->qty_amount ?? 0,
            'discount_amount'   => $request->discount_amount ?? 0,
            'total_amount'      => $request->total_amount ?? 0,
            'paid_amount'       => $request->paid_amount ?? 0,
            'due_amount'        => $request->due_amount ?? 0,
            'company_id'        => $request->company_id
        ]);
    }












    /*
     |--------------------------------------------------------------------------
     | STORE PURCHASE DETAILS
     |--------------------------------------------------------------------------
    */
    public function storePurchaseDetails($request)
    {
        foreach ($request->product_id as $key => $product_id) {

            $detail = PurchaseDetail::create([

                'purchase_id'   => $this->purchase->id,
                'product_id'    => $product_id,
                'quantity'      => $request->quantity[$key],
                'price'         => $request->purchase_price[$key],
                'description'   => $request->description[$key] ?? null,
            ]);


            if($request->quantity[$key] != null) {
                $branch_id = null;
                $warehouse_id = null;
                $date = date('Y-m-d', strtotime($request->date));

                // update stock
                $this->productStockService->storeRequisitionStock($detail->id, $this->purchase->invoice_no, "Account Purchase", $this->purchase->date, 0, $detail->quantity, $product_id, 0, $detail->price, $request->company_id, $this->purchase->branch_id);


                $this->productStockService->updateStockInHand($product_id, $this->purchase->company_id, $this->purchase->branch_id, $this->purchase->date, $detail->quantity);


                // $this->stockService->createStock($detail, $request->company_id, $branch_id, $warehouse_id, $product_id, 'In', $date, $request->purchase_price[$key], $request->quantity[$key], $request->quantity[$key]);

                // $this->stockService->stockSummary($product_id, $request->company_id, $branch_id, $warehouse_id);
            }
        }
    }












    /*
     |--------------------------------------------------------------------------
     | UPDATE PURCHASE DETAILS
     |--------------------------------------------------------------------------
    */
    public function updatePurchaseDetails($request)
    {

        PurchaseDetail::where('purchase_id', $this->purchase->id)->whereNotIn('id', array_filter($request->detail_ids))->delete();

        foreach ($request->detail_ids as $key => $detail_id) {


            PurchaseDetail::updateOrCreate([

                'id' => $detail_id
            ], [

                'purchase_id'       => $this->purchase->id,
                'product_id'        => $request->product_id[$key],
                'quantity'          => $request->quantity[$key],
                'price'             => $request->purchase_price[$key],
                'description'       => $request->description[$key] ?? null,
            ]);

        }
    }












    /*
     |--------------------------------------------------------------------------
     | MAKE TRANSACTION
     |--------------------------------------------------------------------------
    */
    public function makeTransaction()
    {
        $cash_account       = $this->transactionService->getCashAccount();          // debit
        $purchase_account   = $this->transactionService->getPurchaseAccount();      // credit
        $customer_account   = optional($this->purchase->supplier)->account;         // credit

        $purchase           = $this->purchase->refresh();
        $invoice_no         = $purchase->invoice_no;
        $date               = $purchase->date;


        $description        = 'Purchase from ' . (optional($purchase->supplier)->name ?? 'Mr. Supplier');

        $this->transactionService->storeTransaction($purchase->company_id, $purchase,    $invoice_no,    $purchase_account,    $purchase->total_amount, 0,  $date, 'debit',     'Purchase', $description);   //  Payable Amount

        $this->transactionService->storeTransaction($purchase->company_id, $purchase,    $invoice_no,    $cash_account,        0, $purchase->paid_amount,   $date, 'credit',    'P.Payment', $description);    //  Paid Amount

        $this->transactionService->storeTransaction($purchase->company_id, $purchase,    $invoice_no,    $customer_account,    $purchase->paid_amount, $purchase->total_amount,   $date, 'credit',    'Supplier Due', $description);    //  Due Amount
    }












    /*
     |--------------------------------------------------------------------------
     | STORE ACCOUNT PURCHASE FROM ANOTHER SOURCE
     |--------------------------------------------------------------------------
    */
    public function storeAccountPurchaseFromAnotherSource($sourceable, $source, $details)
    {
        
        $purchase = $sourceable->accountPurchase()->create([
            'supplier_id'       => $sourceable->supplier_id,
            'source'            => $source,
            'date'              => $sourceable->purchase_date,
            'invoice_no'        => $this->invoiceNumberService->getPurchaseInvoiceNo($sourceable->company_id),
            'qty_total'         => $sourceable->total ?? 0,
            'qty_amount'        => $sourceable->total_amount ?? 0,
            'total_amount'      => $sourceable->total_amount ?? 0,
            'due_amount'        => $sourceable->total_amount ?? 0,
            'company_id'        => $sourceable->company_id
        ]);

        
        $this->invoiceNumberService->setNextInvoiceNo($sourceable->company_id, 'Purchase', date('Y'));

        $this->storeAccountPurchaseDetailsFromAnotherSource($purchase, $details);
    }












    /*
     |--------------------------------------------------------------------------
     | UPDATE ACCOUNT PURCHASE FROM ANOTHER SOURCE
     |--------------------------------------------------------------------------
    */
    public function updateAccountPurchaseFromAnotherSource($sourceable, $source, $details)
    {
        $sourceable->accountPurchase()->update([
            'supplier_id'       => $sourceable->supplier_id,
            'source'            => $source,
            'date'              => $sourceable->purchase_date,
            'qty_total'         => $sourceable->total ?? 0,
            'qty_amount'        => $sourceable->total_amount ?? 0,
            'total_amount'      => $sourceable->total_amount ?? 0,
            'due_amount'        => $sourceable->total_amount ?? 0,
            'company_id'        => $sourceable->company_id
        ]);

        $purchase = $sourceable->accountPurchase;

        $purchase->details()->delete();

        $this->storeAccountPurchaseDetailsFromAnotherSource($purchase, $details);
    }












    /*
     |--------------------------------------------------------------------------
     | STORE ACCOUNT PURCHASE DETAILS FROM ANOTHER SOURCE
     |--------------------------------------------------------------------------
    */
    public function storeAccountPurchaseDetailsFromAnotherSource($purchase, $purchaseDetails)
    {
        if ($purchase->source == 'Production') {

            foreach($purchaseDetails as $purchaseDetail) {

                PurchaseDetail::create([
                    'purchase_id'   => $purchase->id,
                    'product_id'    => $purchaseDetail->product_id,
                    'quantity'      => $purchaseDetail->quantity ?? 0,
                    'price'         => $purchaseDetail->last_rate ?? 0,
                ]);
            }
        }
    }
}
