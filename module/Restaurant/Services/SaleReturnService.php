<?php


namespace Module\Restaurant\Services;

use Module\Hotel\Models\Guest;
use Module\Bar\Models\Stock;
use Module\Hotel\Models\AccountType;
use Module\Bar\Models\SaleReturn;
use Module\HotelService\Services\HotelTransactionService;
use Module\Bar\Services\BarTransectionService;


class SaleReturnService
{
    private $request;
    public $sale;
    public $invoice_no;
    public $transection_service;

    public function __construct()
    {
        $this->request = \request();
        $this->transection_service = new BarTransectionService();
        $this->invoice_no   = $this->transection_service->getInvoiceNo('Restaurant Sale Return');
    }


    public function store()
    {
        $input = $this->request->only(
            'invoice_no',
            'customer_id',
            'date',
            'subtotal',
            // 'discount',
            'return_amount',
            'payable_amount',
            'due_amount',
            'change_amount',
        );

        $input['date']  = $this->request->date ?: date('Y-m-d');

        $this->sale = SaleReturn::create($input);


        $guest = Guest::find($this->request->guest_id);

        $this->sale->update([
            'invoice_no' => $this->invoice_no,
        ]);

        $this->transection_service->setNextInvoiceNo('Restaurant Sale Return', date('Y-m'));

        $return_amount = -$this->request->return_amount ?? 0;

        $this->makePayment(
            $this->sale->id,
            $this->request->grand_total ?? request('payable_amount'),
            $this->request->discount,
            $this->request->paid_amount ?? 0,
            $guest->booking_id ?? null,
            request('payment_way') ?? null,

        );



        return $this->sale;
    }

    public function storeItem()
    {

        foreach ($this->request->product_ids as $key => $product_id) {

            $this->sale->items()->create([
                'sale_id'       => $this->request->sale_ids[$key],
                'product_id'    => $product_id,
                'quantity'      => $this->request->return_quantity[$key],
                'item_price'    => $this->request->product_cost[$key],
                'total_amount'  => $this->request->total_amount[$key],
                'item_discount'  => $this->request->item_discount[$key],
            ]);

            $this->stockUpdate($product_id, $this->request->return_quantity[$key]);
        }
    }






    public function stockUpdate($product_id, $qty)
    {
        $product = Stock::where('product_id', $product_id)->first();

        if ($product) {
            $product->increment('return_quantity', $qty);
        } else {
            Stock::create([
                'product_id'            => $product_id,
                'sold_quantity'         => 0,
                'return_quantity'       => $qty,
                'purchased_quantity'    => 0,
                'opening_quantity'      => 0,
            ]);
        }
    }




    public function makePayment($source_id, $payable_amount, $discount, $paid_amount, $booking_id, $account_type_id)
    {

        // dd('p');

        (new HotelTransactionService())->storeTransaction(
            $this->sale,
            $source_id,
            'Bar Sale',
            $account_type_id, // AccountType::first()->id,
            $payable_amount,
            $discount,
            $paid_amount,
            request('vat') ?? 0,
            request('service_charge') ?? 0,
            $booking_id,
            $this->invoice_no,
            $this->sale->date ?? today_from_system()
        );

    }


    /*
     |--------------------------------------------------------------------------
     | UPDATE LEDGER
     |--------------------------------------------------------------------------
    */
     public function updateLedger($balance_type)
    {
        dd('p');
        $account_id = defaultAccount()->id;
        $invoice_no = $this->sale->invoice_no;

        // Pharmcy Sale transaction
        // (new TransactionService)->storeTransaction($this->sale, $invoice_no, $account_id, $this->request->paid_amount, $this->request->date, 'Pharmacy Sale', $this->sale->id);
    }
}
