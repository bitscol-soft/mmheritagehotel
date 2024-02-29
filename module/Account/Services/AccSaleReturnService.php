<?php


namespace Module\Account\Services;

use Module\Account\Models\Damage;
use Module\Account\Models\DamageDetail;
use Module\Account\Models\SaleDetail;
use Module\Account\Models\SaleExchangeDetail;
use Module\Account\Models\SaleReturn;
use Module\Account\Models\SaleReturnDetail;

class AccSaleReturnService
{
    private $transactionService;

    public $invoiceNumberService;
    public $stockService;
    public $productStockService;



    public $saleReturn;









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

            'customer_id'               => 'required',
            'company_id'                => 'required',
            'returnable_product_ids.*'  => 'required',
        ]);
    }












    /*
     |--------------------------------------------------------------------------
     | STORE SALE
     |--------------------------------------------------------------------------
    */
    public function storeSaleReturn($request)
    {

        $this->saleReturn = SaleReturn::create([

            'date'                      => $request->date,
            'invoice_no'                => $this->invoiceNumberService->getSaleReturnInvoiceNo($request->company_id),
            'sale_id'                   => $request->sale_id,
            'customer_id'               => $request->customer_id,
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
    public function storeSaleReturnDetails($request)
    {

        foreach ($request->returnable_product_ids as $key => $product_id) {


            if($request->return_quantities[$key] != '') {

                $detail = SaleReturnDetail::create([

                    'sale_return_id'        => $this->saleReturn->id,
                    'product_id'            => $product_id,
                    'product_type'          => $request->conditions[$key],
                    'quantity'              => $request->return_quantities[$key],
                    'price'                 => $request->returnable_prices[$key],
                    'subtotal'              => $request->return_subtotals[$key]
                ]);



                // update stock
                $this->productStockService->storeRequisitionStock($detail->id, $this->saleReturn->invoice_no, "Account Sale Return", $this->saleReturn->date, 0, $detail->quantity, $product_id, 0, $detail->price, $request->company_id, $this->saleReturn->branch_id);


                $this->productStockService->updateStockInHand($product_id, $this->saleReturn->company_id, $this->saleReturn->branch_id, $this->saleReturn->date, $detail->quantity);

                // $branch_id = null;
                // $warehouse_id = null;
                // $date = date('Y-m-d', strtotime($request->date));

                // $this->stockService->createStock($detail, $request->company_id, $branch_id, $warehouse_id, $product_id, 'Out', $date, $request->sale_price[$key], $request->quantity[$key], -$request->quantity[$key]);

                // $this->stockService->stockSummary($product_id, $request->company_id, $branch_id, $warehouse_id);
            }
        }
    }








    /*
     |--------------------------------------------------------------------------
     | STORE SALE EXCHANGE DETAILS
     |--------------------------------------------------------------------------
    */
    public function storeSaleExchangeDetails($request)
    {

        foreach ($request->exchange_product_qtys ?? [] as $product_id => $quantity) {


            if($quantity != '') {

                $detail = SaleExchangeDetail::create([

                    'sale_return_id'        => $this->saleReturn->id,
                    'product_id'            => $product_id,
                    'quantity'              => $quantity,
                    'price'                 => $request->exchange_product_prices[$product_id],
                    'subtotal'              => $request->exchange_product_subtotals[$product_id]
                ]);



                // update stock
                $this->productStockService->storeRequisitionStock($detail->id, $this->saleReturn->invoice_no, "Account Sale Exchange", $this->saleReturn->date,  $detail->quantity, 0, $product_id, 0, $detail->price, $request->company_id, $this->saleReturn->branch_id);


                $this->productStockService->updateStockInHand($product_id, $this->saleReturn->company_id, $this->saleReturn->branch_id, $this->saleReturn->date, '');


                // $branch_id = null;
                // $warehouse_id = null;
                // $date = date('Y-m-d', strtotime($request->date));

                // $this->stockService->createStock($detail, $request->company_id, $branch_id, $warehouse_id, $product_id, 'Out', $date, $request->sale_price[$key], $request->quantity[$key], -$request->quantity[$key]);

                // $this->stockService->stockSummary($product_id, $request->company_id, $branch_id, $warehouse_id);
            }
        }
    }












    /*
     |--------------------------------------------------------------------------
     | STORE DAMAGE DATA
     |--------------------------------------------------------------------------
    */
    public function storeDamageData($request)
    {
        $damages = $this->saleReturn->return_details->where('product_type', 'Damage');

        if (count($damages) > 0) {
            
            $damage = Damage::create([

                'date'                      => $request->date,
                'invoice_no'                => $this->invoiceNumberService->getDamageInvoiceNo($request->company_id),
                'company_id'                => $request->company_id,
                'branch_id'                 => $request->branch_id,
                'total_amount'              => $damages->sum('subtotal') ?? 0,
                'sale_return_id'            => $this->saleReturn->id,
            ]);

            foreach ($damages as $key => $item) {

                $detail = DamageDetail::create([

                    'damage_id'             => $damage->id,
                    'product_id'            => $item->product_id,
                    'quantity'              => $item->quantity,
                    'price'                 => $item->price,
                    'subtotal'              => $item->subtotal
                ]);


                // update stock
                $this->productStockService->storeRequisitionStock($detail->id, $damage->invoice_no, "Account Product Damage", $damage->date, 0, $detail->quantity, $item->product_id, 0, $detail->price, $request->company_id, $damage->branch_id);


                $this->productStockService->updateStockInHand($item->product_id, $damage->company_id, $damage->branch_id, $damage->date, '');

            }


            $damage_account             = $this->transactionService->getDamageAccount();      // credit
            $purchase_account           = $this->transactionService->getPurchaseAccount();      // credit

            $damage                     = $damage->refresh();
            $invoice_no                 = $damage->invoice_no;
            $date                       = $damage->date;


            $description                = 'Product Damage';

            $this->transactionService->storeTransaction($damage->company_id, $damage,    $invoice_no,    $damage_account,   $damage->total_amount, 0,  $date, 'debit',     'Product Damage', $description);   //  Payable Amount
            $this->transactionService->storeTransaction($damage->company_id, $damage,    $invoice_no,    $purchase_account, 0,  $damage->total_amount,  $date, 'credit',     'Product Damage', $description);   //  Payable Amount

        }
    }









    /*
     |--------------------------------------------------------------------------
     | MAKE TRANSACTION
     |--------------------------------------------------------------------------
    */
    public function makeTransaction()
    {
        $cash_account           = $this->transactionService->getCashAccount();          // debit
        $sale_return_account    = $this->transactionService->getSaleReturnAccount();      // credit
        $customer_account       = optional($this->saleReturn->customer)->account;         // credit

        $saleReturn             = $this->saleReturn->refresh();
        $invoice_no             = $saleReturn->invoice_no;
        $date                   = $saleReturn->date;


        $description        = 'Sales Return from ' . (optional($saleReturn->customer)->name ?? 'Mr. Customer');

        $this->transactionService->storeTransaction($saleReturn->company_id, $saleReturn,    $invoice_no,    $sale_return_account,      $saleReturn->total_payable, 0,  $date, 'debit',     'Sale Return', $description);   //  Payable Amount

        $this->transactionService->storeTransaction($saleReturn->company_id, $saleReturn,    $invoice_no,    $cash_account,             0, $saleReturn->total_paid_amount,   $date, 'credit',    'Acc. Sale Return Pay', $description);    //  Paid Amount

        $this->transactionService->storeTransaction($saleReturn->company_id, $saleReturn,    $invoice_no,    $customer_account,         $saleReturn->total_paid_amount, $saleReturn->total_payable,   $date, 'credit',    'Customer Sale Return', $description);    //  Due Amount
    }

}

