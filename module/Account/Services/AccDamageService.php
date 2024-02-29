<?php


namespace Module\Account\Services;

use Module\Account\Models\Damage;
use Module\Account\Models\DamageDetail;

class AccDamageService
{
    private $transactionService;

    public $invoiceNumberService;
    public $stockService;
    public $productStockService;



    public $damage;









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

            'company_id'        => 'required',
            'prices.*'          => 'required',
            'quantities.*'      => 'required',
        ]);
    }












    /*
     |--------------------------------------------------------------------------
     | STORE SALE
     |--------------------------------------------------------------------------
    */
    public function storeDamage($request)
    {

        $this->damage = Damage::create([

            'date'                      => $request->date,
            'invoice_no'                => $this->invoiceNumberService->getDamageInvoiceNo($request->company_id),
            'company_id'                => $request->company_id,
            'branch_id'                 => $request->branch_id,
            'total_amount'              => $request->total_amount ?? 0,
        ]);
    }












    /*
     |--------------------------------------------------------------------------
     | STORE DAMAGE DETAILS
     |--------------------------------------------------------------------------
    */
    public function storeDamageDetails($request)
    {

        foreach ($request->quantities as $product_id => $quantity) {


            if($quantity != '') {

                $detail = DamageDetail::create([

                    'damage_id'             => $this->damage->id,
                    'product_id'            => $product_id,
                    'quantity'              => $quantity,
                    'price'                 => $request->prices[$product_id],
                    'subtotal'              => $request->subtotals[$product_id]
                ]);



                // update stock
                $this->productStockService->storeRequisitionStock($detail->id, $this->damage->invoice_no, "Account Product Damage", $this->damage->date, 0, $detail->quantity, $product_id, 0, $detail->price, $request->company_id, $this->damage->branch_id);


                $this->productStockService->updateStockInHand($product_id, $this->damage->company_id, $this->damage->branch_id, $this->damage->date, '');

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
        $damage_account             = $this->transactionService->getDamageAccount();      // credit
        $purchase_account           = $this->transactionService->getPurchaseAccount();      // credit

        $damage                     = $this->damage->refresh();
        $invoice_no                 = $damage->invoice_no;
        $date                       = $damage->date;


        $description                = 'Product Damage';

        $this->transactionService->storeTransaction($damage->company_id, $damage,    $invoice_no,    $damage_account,   $damage->total_amount, 0,  $date, 'debit',     'Product Damage', $description);   //  Payable Amount
        $this->transactionService->storeTransaction($damage->company_id, $damage,    $invoice_no,    $purchase_account, 0,  $damage->total_amount,  $date, 'credit',     'Product Damage', $description);   //  Payable Amount

    }

}

