<?php


namespace Module\Restaurant\Services;

use Module\Hotel\Models\Guest;
use Illuminate\Support\Facades\DB;
use Module\Restaurant\Models\Sale;
use Module\Restaurant\Models\Stock;
use Module\Hotel\Models\AccountType;
use Module\Restaurant\Models\Product;
use Module\Hotel\Models\HotelTransection;
use Module\Restaurant\Models\KitchenOrder;
use Module\Hotel\Models\CardAuthorizedInfo;
use Module\HotelService\Services\HotelTransactionService;

class SaleService
{
    private $request;
    private $transection_service;
    public $sale;

    public $kitchen;
    public $invoice_no;

    public function __construct()
    {
        $this->request = \request();
        $this->transection_service = new ResturentTransectionService();
        $this->invoice_no   = $this->transection_service->getInvoiceNo('Restaurant Sale');
    }




    /**
     *--------------------------------------------------------------------------
     * STORE METHOD
     *--------------------------------------------------------------------------
     */
    public function store()
    {

        $input = $this->request->only(
            'date',
            'hotel_guest_id',
            'waiter_no',
            'guest_name',
            'subtotal',
            'discount',
            'previous_due',
            'paid_amount',
            'due_amount',
            'vat_amount',
            'payment_status',
            'service_amount',
            'invoice_no'
        );


        $input['change_amount']     = request('paid_amount') > request('payable_amount') ? request('paid_amount') - request('payable_amount') : 0;
        $input['date']              = date('Y-m-d');
        $input['table_id']          = request('hotel_table_id');
        $input['discount']          = request('total_discount');  // discount percentage wise
        $input['vat_amount']        = request('vat') ?? request('vat_amount');
        $input['service_amount']    = request('service_charge') ?? request('service_amount');
        $input['hotel_booking_id']  = Guest::where('id', request('hotel_guest_id'))->first()->booking_id ?? null;
        $input['subtotal']          = array_sum(request('item_price')) - array_sum(request('item_discount'));

        // $input['payment_status']    = request('paid_amount') > 0 ? request('payment_status') : 'Due';
        $input['payment_status']    = request('paid_amount') >= request('payable_amount') ? request('payment_status') : 'Due';

        if (isset($this->request->payment_way)) {
            $input['payment_way']   = AccountType::find(request('payment_way'))->name;
        }



        if ($this->request->is_soft_save == 1) {
            $input['deleted_at']    = now();
        }


        $guest                  = Guest::find($this->request->hotel_guest_id);

        if (request('guest_name') == '') {
            $input['guest_name']  = $guest->name;
        }



        $this->sale             = Sale::create($input);

        if( setting('rst_use_kitchen_module') == 1 ){

            $this->kitchen = KitchenOrder::create([
                    'sale_id'       => $this->sale->id,
                    'invoice_no'    => $this->sale->invoice_no,
                    'ticket_no'     => $this->sale->invoice_no,
                    'customer_id'   => $guest->id ?? null,
                    'customer_name' => request('guest_name') ?? $guest->name,
                    'table_no'      => request('hotel_table_id') ?? null,
                    'waiter_no'     => request('waiter_no') ?? null,
                    'total_qty'     => null,
                    'total_amount'  => array_sum(request('item_price')) - array_sum(request('item_discount')),
                    'note'          => null,
                    'order_status'  => 'Pending',
                    'approve_time'  => null,
                    'cancel_time'   => null,
                    'date'          => date('Y-m-d'),
                    'status'        => 1,
                    'approved_by'   => null,
                    'is_bar'        => 0,
                    'canceled_by'   => null,
                    'created_by'    => auth()->id(),
                    'updated_by'    => auth()->id(),
            ]);
        }

        $this->invoice_no       = $this->sale->invoice_no;

        $this->transection_service->setNextInvoiceNo('Restaurant Sale', date('Y-m'));



        // HOTEL CUSTOMER LEDGER UPDATE
        // if ($this->request->paid_amount > 0) {
            //     (new HotelTransactionLedgerService())->storeCustomerLedger(
            //         $this->request->hotel_guest_id,
            //         fdate(date('Y-m-d'),'Y-m-d'),
            //         $this->request->paid_amount,
            //         $this->request->grand_total ?? request('payable_amount'),
            //     );
        // }




        // TRANSACTION UPDATE/CREATE
        $this->makePayment(
            $this->sale->id,
            $this->request->grand_total ?? request('payable_amount'),
            $this->request->discount,
            $this->request->paid_amount ?? 0,
            $guest->booking_id ?? null,
            $this->request->invoice_no,
            request('payment_way') ?? null,
        );


        return $this->sale;

    }



    /**
     *--------------------------------------------------------------------------
     * UPDATE METHOD
     *--------------------------------------------------------------------------
     */
    public function update($id)
    {

        $input = $this->request->only(
            'date',
            'hotel_guest_id',
            'waiter_no',
            'guest_name',
            'subtotal',
            'discount',
            'previous_due',
            'paid_amount',
            'due_amount',
            'vat_amount',
            'payment_status',
            'service_amount',
        );


        $input['change_amount'] = request('paid_amount') > request('payable_amount') ? request('paid_amount') - request('payable_amount') : 0;
        $input['table_id']      = request('hotel_table_id');
        $input['hotel_booking_id'] = Guest::where('id', request('hotel_guest_id'))->first()->booking_id ?? null;
        $input['payment_status']    = request('paid_amount') >= request('payable_amount') ? request('payment_status') : 'Due';

        if ($this->request->is_soft_save == 1) {
            $input['deleted_at']      = now();
        }

        $this->sale             = Sale::where('id', $id)->first();

        if (isset($this->request->payment_way)) {
            $input['payment_way']   = AccountType::find(request('payment_way'))->name;
        }

        $this->sale->update($input);

        $guest                  = Guest::find($this->request->hotel_guest_id);


        // HOTEL CUSTOMER LEDGER UPDATE
        // if ($this->request->paid_amount > 0) {
        //     (new HotelTransactionLedgerService())->storeCustomerLedger(
        //         $this->request->hotel_guest_id,
        //         fdate(date('Y-m-d'),'Y-m-d'),
        //         $this->request->paid_amount,
        //         $this->request->grand_total ?? request('payable_amount'),
        //     );
        // }

        $this->makePayment(
            $this->sale->id,
            $this->request->grand_total ?? request('payable_amount'),
            $this->request->discount,
            $this->request->paid_amount ?? 0,
            $guest->booking_id ?? null,
            $this->request->invoice_no,
            request('payment_way') ?? null,

        );

        return $this->sale;
    }





    //--------------------------------------------------------------------------//
    //                            COLLECT DUE METHOD                            //
    //--------------------------------------------------------------------------//
    public function collectDue($request)
    {

        DB::transaction(function () use($request) {

            $totalPaidAmount     = $this->request->total_paid_amount;


            foreach ($request->item_ids as $key => $item_id) {

                $sale            = Sale::where('id', $item_id)->first();
                $payment_way     = AccountType::find(request('payment_type'))->name ?? null;


                if ($totalPaidAmount > 0) {


                    // UPDATING TOTAL PAID AMOUNT
                    $itemTotalAmount        = $this->request->total_amount[$key];
                    $itemTotalDueAmount     = $this->request->item_amount[$key];
                    $itemPreviousCollection = $this->request->previous_collection[$key];

                    if ($totalPaidAmount >= $itemTotalDueAmount) {
                        $totalPaidAmount    = $totalPaidAmount - $itemTotalDueAmount;
                        $isDue              = 0;
                    }
                    else{
                        $totalPaidAmount    = $totalPaidAmount;
                        $isDue              = 1;
                    }


                    // SALE UPDATE
                    $sale->update([
                        'paid_amount'    => $isDue == 1 ? $totalPaidAmount + $itemPreviousCollection : $itemTotalDueAmount + $itemPreviousCollection,
                        'due_amount'     => $isDue == 1 ? $itemTotalDueAmount - $totalPaidAmount : 0,
                        'change_amount'  => 0,
                        'payment_status' => $isDue == 1 ? 'Due' : 'Paid',
                        'payment_way'    => $payment_way,
                    ]);


                    $guest        = Guest::find($this->request->hotel_guest_id);


                    // TRANSACTION UPDATE
                    (new HotelTransactionService())->storeTransaction(
                        $sale,
                        $sale->id,
                        'Restaurant Sale',
                        $this->request->payment_type ?? null,
                        $itemTotalAmount,
                        $this->request->discount[$key],
                        $isDue == 1 ? $totalPaidAmount + $itemPreviousCollection : $itemTotalDueAmount + $itemPreviousCollection,
                        $this->request->vat_amount[$key] ?? 0,
                        $this->request->service_charge[$key] ?? 0,
                        $guest->booking_id ?? null,
                        $this->request->invoice_no[$key],
                        fdate($this->sale->date ?? date('Y-m-d'),'Y-m-d'),
                        null,
                        0,
                        141,
                        $isDue == 1 ? $totalPaidAmount : $itemTotalDueAmount,
                    );


                    if ($isDue == 1) {
                        $totalPaidAmount = 0;
                    }

                }


            }

        });

    }

    // public function makeTransactionPayment($sale, $source_id, $payable_amount, $discount, $paid_amount, $booking_id, $invoice_no, $account_type_id, $service_charge, $vat_amount)
    // {
        //     (new HotelTransactionService())->storeTransaction(
        //         $sale,
        //         $source_id,
        //         'Restaurant Sale',
        //         $account_type_id,
        //         $payable_amount,
        //         $discount,
        //         $paid_amount,
        //         $vat_amount ?? 0,
        //         $service_charge ?? 0,
        //         $booking_id,
        //         $this->invoice_no,
        //         fdate($this->sale->date ?? date('Y-m-d'),'Y-m-d'),
        //     );
    // }






    //--------------------------------------------------------------------------//
    //                            STORE ITEM METHOD                             //
    //--------------------------------------------------------------------------//
    public function storeItem()
    {

        foreach ($this->request->product_ids as $key => $product_id) {

            $this->sale->items()->create([
                'product_id'    => $product_id,
                'quantity'      => $this->request->sales_qty[$key],
                'sales_price'   => $this->request->sales_price[$key],
                'item_price'    => $this->request->item_price[$key],
                'unit_id'       => $this->request->unit_id[$key] ?? null,
                // 'vat_amount'    => $this->request->item_vat_amounts[$key],
                'item_discount'    => $this->request->item_discount[$key] ?? 0,
                'vat_amount'    => $this->request->item_vat_amounts[$key] ?? $this->request->vat_amount[$key],
                'is_bar'        => Product::where('id', $product_id)->first()->is_bar,
            ]);

            // kitchen order details
            if(setting('rst_use_kitchen_module') == 1){

                $this->kitchen->order_items()->create([
                    'order_id'      => $this->kitchen->id,
                    'item_id'       => $product_id,
                    'item_name'     => $product_id,
                    'price'         => $this->request->sales_price[$key],
                    'qty'           => $this->request->sales_qty[$key],
                    'status'        => 1,
                ]);
            }






            $this->stockUpdate($product_id, $this->request->sales_qty[$key]);
        }
    }






    //--------------------------------------------------------------------------//
    //                         UPDATE SALE ITEM METHOD                          //
    //--------------------------------------------------------------------------//
    public function updateSaleItem()
    {

        foreach ($this->request->product_ids as $key => $product_id) {

            // dd($this->request->old_quantity[$key] ?? 0);
            $this->sale->items()->updateOrCreate([
                'product_id'    => $product_id,
            ],[
                'quantity'      => $this->request->sales_qty[$key],
                'sales_price'   => $this->request->sales_price[$key],
                'item_price'    => $this->request->item_price[$key],
                'unit_id'       => $this->request->unit_id[$key],
                'vat_amount'    => $this->request->item_vat_amounts[$key],
                'is_bar'        => Product::where('id', $product_id)->first()->is_bar,
            ]);

            $this->stockUpdateIfSaleUpdate($product_id, ($this->request->sales_qty[$key] - ($this->request->old_quantity[$key] ?? 0)));
        }
    }





    //--------------------------------------------------------------------------//
    //                           STOCK UPDATE METHOD                            //
    //--------------------------------------------------------------------------//
    public function stockUpdate($product_id, $qty)
    {
        $stock = Stock::query()->where('product_id', $product_id)->first();

        if ($stock) {
            $stock->increment('sold_quantity', $qty);
        } else {
            $stock = Stock::create([
                'product_id'            => $product_id,
                'sold_quantity'         => $qty,
                'return_quantity'       => 0,
                'purchased_quantity'    => 0,
                'opening_quantity'      => 0,
            ]);
        }

        (new StockLedgerService())->stockLedger($this->sale->id,'Restaurant Sale', $product_id, 0, $qty, 0, $this->sale->company_id);
    }





    //--------------------------------------------------------------------------//
    //                     STOCK UPDATE IF SALE UPDATE METHOD                   //
    //--------------------------------------------------------------------------//
    public function stockUpdateIfSaleUpdate($product_id, $qty)
    {
        $product = Stock::query()->where('product_id', $product_id)->first();
        if ($product) {

            $product->decrement('sold_quantity', $qty);
            // (new StockLedgerService())->stockLedger($this->sale->id,'Restaurant Sale', $product_id, $qty, 0, 0, $this->sale->company_id);

        } else {

            Stock::create([
                'product_id'            => $product_id,
                'sold_quantity'         => 0,
                'return_quantity'       => 0,
                'purchased_quantity'    => 0,
                'opening_quantity'      => 0,
            ]);

            (new StockLedgerService())->stockLedger($this->sale->id,'Restaurant Sale', $product_id, 0, $qty, 0, $this->sale->company_id);
        }
    }





    //--------------------------------------------------------------------------//
    //                     STOCK UPDATE IF SALE DELETE METHOD                   //
    //--------------------------------------------------------------------------//
    public function stockUpdateIfSaleDelete($product_id, $qty)
    {
        $product = Stock::query()->where('product_id', $product_id)->first();

        if ($product) {
            $product->decrement('sold_quantity', $qty);

            (new StockLedgerService())->stockLedger( $this->sale->id, 'Restaurant Sale', $product_id, $qty, 0, 0, $this->sale->company_id);

        } else {
            Stock::create([
                'product_id'            => $product_id,
                'sold_quantity'         => 0,
                'return_quantity'       => 0,
                'purchased_quantity'    => 0,
                'opening_quantity'      => 0,
            ]);

            (new StockLedgerService())->stockLedger($this->sale->id,'Restaurant Sale', $product_id, 0, $qty, 0, $this->sale->company_id);
        }
    }





    //--------------------------------------------------------------------------//
    //                            REST STOCK METHOD                             //
    //--------------------------------------------------------------------------//
    public function restStock($product_id, $rest)
    {
        $new_product = Stock::where('product_id', $product_id)->first();

        if ($new_product->available_quantity < $rest) {
            $rest = $rest - $new_product->available_quantity;
            // $new_product->update(['available_quantity' => 0]);

            $this->restStock($product_id, $rest);
        } else {
            // $new_product->decrement('available_quantity', $rest);
        }
    }





    //--------------------------------------------------------------------------//
    //                           MAKE PAYMENT METHOD                            //
    //--------------------------------------------------------------------------//
    public function makePayment($source_id, $payable_amount, $discount, $paid_amount, $booking_id, $invoice_no, $account_type_id)
    {

        (new HotelTransactionService())->storeTransaction(
            $this->sale,
            $source_id,
            request('is_bar') == 1 ? 'Bar Sale' : 'Restaurant Sale',
            $account_type_id, // AccountType::first()->id,
            $payable_amount,
            $discount,
            $paid_amount,
            request('vat') ?? request('vat_amount') ?? 0,
            request('service_charge') ?? request('service_amount') ?? 0,
            $booking_id,
            $this->invoice_no,
            fdate($this->sale->date ?? date('Y-m-d'),'Y-m-d'),
        );
    }





    //--------------------------------------------------------------------------//
    //                          UPDATE LEDGER METHOD                            //
    //--------------------------------------------------------------------------//
    public function updateLedger($balance_type)
    {
        $account_id = defaultAccount()->id;
        $invoice_no = $this->sale->invoice_no;

        // Pharmcy Sale transaction
        // (new TransactionService)->storeTransaction($this->sale, $invoice_no, $account_id, $this->request->paid_amount, $this->request->date, 'Pharmacy Sale', $this->sale->id);
    }



    public function StoreCardInfo($request, $sale){
            if($request->is_multiple_way == 1 && $request->card_info != null){
                CardAuthorizedInfo::create([
                        'sale_id'           => $sale->id,
                        'authorized_info'   => $request->card_info,
                        'collect_amount'    => array_sum($request->modal_account_paid_amounts),
                ]);

        }
    }



     //CALCULATE SALE PAID AMOUNT, PAYABLE AMOUNT, DUE AMOUNT
     public function calculateSaleAmount($sale_id)
     {
         $sale = Sale::query()->with('details')->where('id', $sale_id)->first();

         //$subtotal = ($sale->details->sum('item_price') + $sale->vat_amount + $sale->service_amount) - $sale->discount;

         $sale->update([
             'subtotal'  => $sale->details->sum('item_price'),
         ]);

         HotelTransection::where('source_type', 'Bar Sale')->where('source_id', $sale_id)->update([
             'total_amount'  => $sale->details->sum('item_price'),
         ]);

     }
}
