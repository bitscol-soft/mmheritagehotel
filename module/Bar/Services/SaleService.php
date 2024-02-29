<?php


namespace Module\Bar\Services;

use Module\Bar\Models\Sale;
use Module\Bar\Models\Stock;
use Module\Bar\Models\Product;
use Module\Bar\Models\ProductPackageDetails;
use Module\Hotel\Models\Guest;
use Module\Bar\Models\ProductUnit;
use Module\Hotel\Models\AccountType;
use Module\Hotel\Models\HotelTransection;
use Module\Bar\Services\StockLedgerService;
use Module\HotelService\Services\HotelTransactionService;

class SaleService
{
    private $request;
    private $transection_service;
    public $sale;
    public $invoice_no;




    public function __construct()
    {
        $this->request = \request();
        $this->transection_service = new BarTransectionService();
        $this->invoice_no   = $this->transection_service->getInvoiceNo('Bar Sale');
    }





    public function store()
    {

        $input = $this->request->only(
            'date',
            'hotel_guest_id',
            'pay_booking_id',
            'customer_id',
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

        $input['change_amount']     = request('paid_amount') > request('payable_amount') ? request('paid_amount') - request('payable_amount') : request('payable_amount') - request('paid_amount');
        $input['date']              = today_from_system();
        $input['table_id']          = request('hotel_table_id');
        $input['vat_amount']        = request('vat') ?? 0;
        $input['service_amount']    = request('service_charge') ?? 0;
        // $input['hotel_booking_id']  = request('hotel_booking_id');
        $input['hotel_booking_id']  = optional(Guest::where('id', request('hotel_guest_id'))->first())->booking_id ?? null;
        $input['pay_booking_id']    = request('pay_booking_id');
        $input['customer_id']       = request('customer_id');
        $input['subtotal']          = array_sum(request('item_price')) - array_sum(request('item_discount'));
        $item_dis = array_sum(request('item_discount'));

        $input['payment_status']    = request('paid_amount') >= request('payable_amount') ? request('payment_status') : 'Due';



        if (isset($this->request->payment_way)) {
            $input['payment_way']   = AccountType::find(request('payment_way'))->name;
        }

        if ($this->request->is_soft_save == 1) {
            $input['deleted_at']    = now();
        }

        if (!request()->filled('guest_name')) {
            $guest                  = Guest::find($this->request->hotel_guest_id);
            $input['guest_name']    = $guest->name;
        }

        $this->sale                 = Sale::query()->create($input);

        $this->invoice_no           = $this->sale->invoice_no;

        $this->transection_service->setNextInvoiceNo('Bar Sale', date('Y-m'));

        $this->makePayment(
            $this->sale->id,
            $this->request->grand_total ?? request('payable_amount'), // $this->request->payable_amount,
            $this->request->discount + array_sum(request('item_discount')),
            $this->request->paid_amount ?? 0,
            $guest->booking_id ?? null,
            request('payment_way') ?? null,
        );

        return $this->sale;
    }




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


        $input['change_amount']     = request('paid_amount') > request('payable_amount') ? request('paid_amount') - request('payable_amount') : request('payable_amount') - request('paid_amount');

        $input['table_id']          = request('hotel_table_id');
        $input['hotel_booking_id']  = optional(Guest::where('id', request('hotel_guest_id'))->first())->booking_id ?? null;
        // $input['subtotal']          = array_sum(request('item_price')) - array_sum(request('item_discount'));
        $input['subtotal']          = array_sum(request('item_price'));

        if ($this->request->is_soft_save == 1) {
            $input['deleted_at']    = now();
        }

        $this->sale                 = Sale::where('id', $id)->first();

        if (isset($this->request->payment_way)) {
            $input['payment_way']   = AccountType::find(request('payment_way'))->name;
        }

        $this->sale->update($input);

        $guest                      = Guest::where('id',$this->request->hotel_guest_id)->first();


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

            $product = Product::where('id', $product_id)->first();


            $this->sale->items()->create([
                'product_id'    => $product_id,
                'sales_price'   => $this->request->sales_price[$key],
                'item_price'    => $this->request->item_price[$key],
                'quantity'      => $this->request->sales_qty[$key],
                'item_discount'      => $this->request->item_discount[$key],
                'small_quantity'=> $this->request->small_qty[$key] ?? null,
                'unit_id'       => $this->request->unit_id[$key] ?? null,
                'small_unit_id' => $this->request->small_unit_id[$key] != 'null' ? $this->request->small_unit_id[$key] : null, // new sale
                'vat_amount'    => $this->request->item_vat_amounts[$key] ?? 0,
                'is_bar'        => $product->is_bar,
            ]);

            // $unit_type = ProductUnit::query()->where('id', $this->request->unit_id[$key])->first()->type;

            $sale_qty = $this->request->sales_qty[$key] * ($product->pack_size ?? 1);

            // if ($unit_type == 'pack') {
            //     $sale_qty = (1 / $product->pack_size) * $sale_qty;
            // }

            if($product->package_id != null || $product->package_id != '' || $product->package_id != NULL){
                $product_package_details = ProductPackageDetails::where('package_id', $product->package_id)->get();
                foreach ($product_package_details as $key => $package_detail) {
                    $saleqty = $this->request->sales_qty[$key] * $package_detail->quantity;
                    $this->stockUpdate($package_detail->product_id, $saleqty);
                }
            }
            $this->stockUpdate($product_id, $sale_qty);
        }

    }

    public function updateSaleItem()
    {
        foreach ($this->request->product_ids as $key => $product_id) {

            $product = Product::where('id', $product_id)->first();

            // dd($this->request->old_quantity[$key] ?? 0);
            $this->sale->items()->updateOrCreate([
                'product_id'    => $product_id,
            ],[
                'sales_price'   => $this->request->sales_price[$key],
                'item_price'    => $this->request->item_price[$key],

                'quantity'      => $this->request->sales_qty[$key],
                'item_discount'      => $this->request->item_discount[$key],
                'small_quantity'=> $this->request->small_qty[$key] ?? null,
                'unit_id'       => $this->request->unit_id[$key],
                'small_unit_id' => $this->request->small_unit_id[$key] ?? null,

                'vat_amount'    => $this->request->item_vat_amounts[$key],
                'is_bar'        => $product->is_bar,
            ]);

            // $unit_type = optional(ProductUnit::query()->where('id', $this->request->unit_id[$key])->first())->type;

            $sale_qty = $this->request->sales_qty[$key];
            $sale_qty = $sale_qty - ($this->request->old_quantity[$key] ?? 0);

            $sale_qty = $sale_qty * ($product->pack_size ?? 1);

            // if ($unit_type == 'pack') {
            //     $sale_qty = (1 / $product->pack_size) * $sale_qty;
            // }

            $this->stockUpdateIfSaleUpdate($product_id, $sale_qty);
        }
    }




    public function stockUpdate($product_id, $qty)
    {
        $product = Stock::where('product_id', $product_id)->where('is_bar', 1)->first();

        if ($product) {
            $product->increment('sold_quantity', $qty);
        } else {
            Stock::create([
                'product_id'            => $product_id,
                'sold_quantity'         => $qty,
                'return_quantity'       => 0,
                'purchased_quantity'    => 0,
                'opening_quantity'      => 0,
                'is_bar'                => 1,
            ]);
        }
        (new StockLedgerService())->stockLedger($this->sale->id,'Bar Sale', $product_id, 0, $qty, 1, $this->sale->company_id);
    }



    public function stockUpdateIfSaleUpdate($product_id, $qty)
    {
        $product = Stock::query()->where('product_id', $product_id)->where('is_bar', 1)->first();

        if ($product) {
            $product->increment('sold_quantity', $qty);


        } else {
            Stock::create([
                'product_id'            => $product_id,
                'sold_quantity'         => 0,
                'return_quantity'       => 0,
                'purchased_quantity'    => 0,
                'opening_quantity'      => 0,
                'is_bar'                => 1,
            ]);
        }

        (new StockLedgerService())->stockLedger($this->sale->id,'Bar Sale', $product_id, 0, $qty, 1, $this->sale->company_id);
    }





    public function stockUpdateIfSaleDelete($product_id, $qty, $unit)
    {
        $stock = Stock::query()->where('product_id', $product_id)->where('is_bar', 1)->first();
        $product = Product::query()->where('id', $product_id)->first();
        $qty = $this->pack_qty($product,$unit, $qty);

        if ($stock) {
            $stock->decrement('sold_quantity', $qty);

            (new StockLedgerService())->stockLedger($this->sale->id,'Bar Sale', $product_id, $qty, 0, 1, $this->sale->company_id);

        } else {
            Stock::create([
                'product_id'            => $product_id,
                'sold_quantity'         => 0,
                'return_quantity'       => 0,
                'purchased_quantity'    => 0,
                'opening_quantity'      => 0,
            ]);

            (new StockLedgerService())->stockLedger($this->sale->id,'Bar Sale', $product_id, 0, $qty, 1, $this->sale->company_id);
        }
    }


    public function restStock($product_id, $rest)
    {
        $new_product = Stock::where('product_id', $product_id)->first();

        if ($new_product->available_quantity < $rest) {
            $rest = $rest - $new_product->available_quantity;

            $this->restStock($product_id, $rest);
        }
    }


    public function makePayment($source_id, $payable_amount, $discount, $paid_amount = 0, $booking_id = null, $account_type_id = null)
    {

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
        $account_id = defaultAccount()->id;
        $invoice_no = $this->sale->invoice_no;

        // Pharmcy Sale transaction
        // (new TransactionService)->storeTransaction($this->sale, $invoice_no, $account_id, $this->request->paid_amount, $this->request->date, 'Pharmacy Sale', $this->sale->id);
    }




    public function pack_qty($product,$unit_id, $qty)
    {
        $unit_type = optional(ProductUnit::query()->where('id', $unit_id)->first())->type;

        $sale_qty = $qty;

        if ($unit_type == 'pack') {
            $sale_qty = (1 / $product->pack_size) * $sale_qty;
        }
        return $sale_qty;
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
