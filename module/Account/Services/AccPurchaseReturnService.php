<?php


namespace Module\Account\Services;

use Module\Account\Models\PurchaseDetail;
use Module\Account\Models\PurchaseExchangeDetail;
use Module\Account\Models\PurchaseReturn;
use Module\Account\Models\PurchaseReturnDetail;

class AccPurchaseReturnService
{
    private $transactionService;

    public $invoiceNumberService;
    public $stockService;
    public $productStockService;



    public $purchaseReturn;









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

            'supplier_id'               => 'required',
            'company_id'                => 'required',
            'returnable_product_ids.*'  => 'required',
        ]);
    }












    /*
     |--------------------------------------------------------------------------
     | STORE SALE
     |--------------------------------------------------------------------------
    */
    public function storePurchaseReturn($request)
    {

        $this->purchaseReturn = PurchaseReturn::create([

            'date'                      => $request->date,
            'invoice_no'                => $this->invoiceNumberService->getPurchaseReturnInvoiceNo($request->company_id),
            'purchase_id'               => $request->purchase_id,
            'supplier_id'               => $request->supplier_id,
            'company_id'                => $request->company_id,
            'branch_id'                 => $request->branch_id,

            'total_return_amount'       => $request->return_total_amount ?? 0,
            'total_exchange_amount'     => $request->exchange_total_amount ?? 0,
            'total_amount'              => $request->grand_total_amount ?? 0,
            'total_discount'            => $request->total_discount ?? 0,
            'total_payable'             => $request->grand_total_amount ?? 0,
            'total_paid_amount'         => $request->paid_amount ?? 0,
            'total_due_amount'          => $request->due_amount ?? 0,
        ]);
    }












    /*
     |--------------------------------------------------------------------------
     | STORE SALE RETURN DETAILS
     |--------------------------------------------------------------------------
    */
    public function storePurchaseReturnDetails($request)
    {

        foreach ($request->returnable_product_ids as $key => $product_id) {


            if($request->return_quantities[$key] != '') {

                $detail = PurchaseReturnDetail::create([

                    'purchase_return_id'    => $this->purchaseReturn->id,
                    'product_id'            => $product_id,
                    'quantity'              => $request->return_quantities[$key],
                    'price'                 => $request->returnable_prices[$key],
                    'subtotal'              => $request->return_subtotals[$key]
                ]);



                // update stock
                $this->productStockService->storeRequisitionStock($detail->id, $this->purchaseReturn->invoice_no, "Account Purchase Return", $this->purchaseReturn->date, $detail->quantity, 0, $product_id, $detail->price, 0, $request->company_id, $this->purchaseReturn->branch_id);


                $this->productStockService->updateStockInHand($product_id, $this->purchaseReturn->company_id, $this->purchaseReturn->branch_id, $this->purchaseReturn->date, $detail->quantity);

                // $branch_id = null;
                // $warehouse_id = null;
                // $date = date('Y-m-d', strtotime($request->date));

                // $this->stockService->createStock($detail, $request->company_id, $branch_id, $warehouse_id, $product_id, 'Out', $date, $request->purchase_price[$key], $request->quantity[$key], -$request->quantity[$key]);

                // $this->stockService->stockSummary($product_id, $request->company_id, $branch_id, $warehouse_id);
            }
        }
    }








    /*
     |--------------------------------------------------------------------------
     | STORE SALE EXCHANGE DETAILS
     |--------------------------------------------------------------------------
    */
    public function storePurchaseExchangeDetails($request)
    {

        foreach ($request->exchange_product_qtys ?? [] as $product_id => $quantity) {


            if($quantity != '') {

                $detail = PurchaseExchangeDetail::create([

                    'purchase_return_id'        => $this->purchaseReturn->id,
                    'product_id'            => $product_id,
                    'quantity'              => $quantity,
                    'price'                 => $request->exchange_product_prices[$product_id],
                    'subtotal'              => $request->exchange_product_subtotals[$product_id]
                ]);



                // update stock
                $this->productStockService->storeRequisitionStock($detail->id, $this->purchaseReturn->invoice_no, "Account Purchase Exchange", $this->purchaseReturn->date, 0, $detail->quantity, $product_id, 0, $detail->price, $request->company_id, $this->purchaseReturn->branch_id);


                $this->productStockService->updateStockInHand($product_id, $this->purchaseReturn->company_id, $this->purchaseReturn->branch_id, $this->purchaseReturn->date, '');


                // $branch_id = null;
                // $warehouse_id = null;
                // $date = date('Y-m-d', strtotime($request->date));

                // $this->stockService->createStock($detail, $request->company_id, $branch_id, $warehouse_id, $product_id, 'Out', $date, $request->purchase_price[$key], $request->quantity[$key], -$request->quantity[$key]);

                // $this->stockService->stockSummary($product_id, $request->company_id, $branch_id, $warehouse_id);
            }
        }
    }













    /*
     |--------------------------------------------------------------------------
     | MAKE TRANSACTION
     |--------------------------------------------------------------------------
    */
    public function makeTransaction()
    {
        $cash_account               = $this->transactionService->getCashAccount();          // debit
        $purchase_return_account    = $this->transactionService->getPurchaseReturnAccount();      // credit
        $supplier_account           = optional($this->purchaseReturn->supplier)->account;         // credit

        $purchaseReturn             = $this->purchaseReturn->refresh();
        $invoice_no                 = $purchaseReturn->invoice_no;
        $date                       = $purchaseReturn->date;


        $description                = 'Purchases Return from ' . (optional($purchaseReturn->supplier)->name ?? 'Mr. Supplier');

        $this->transactionService->storeTransaction($purchaseReturn->company_id, $purchaseReturn,    $invoice_no,    $purchase_return_account,   $purchaseReturn->total_payable, 0,  $date, 'credit',     'Purchase Return', $description);   //  Payable Amount

        $this->transactionService->storeTransaction($purchaseReturn->company_id, $purchaseReturn,    $invoice_no,    $cash_account,               0, $purchaseReturn->total_paid_amount,   $date, 'debit',    'Acc. Purchase Return Pay', $description);    //  Paid Amount

        $this->transactionService->storeTransaction($purchaseReturn->company_id, $purchaseReturn,    $invoice_no,    $supplier_account,           $purchaseReturn->total_paid_amount, $purchaseReturn->total_payable,   $date, 'debit',    'Supplier Purchase Return', $description);    //  Due Amount
    }

}

