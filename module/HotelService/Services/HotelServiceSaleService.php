<?php


namespace Module\HotelService\Services;

use Module\Hotel\Models\Guest;
use Module\Hotel\Models\AccountType;
use Module\Hotel\Models\HotelTransection;
use Module\HotelService\Models\Service\HotelServiceSale;
use Module\HotelService\Services\HotelTransactionService;

class HotelServiceSaleService
{
    private $request;
    public $sale;
    public $guest;
    private $transection_service;
    public $invoice_no;


    public function __construct()
    {
        $this->request = \request();
        $this->transection_service = new HotelTransactionService();
        $this->invoice_no = $this->transection_service->getInvoiceNo();
    }



    public function store()
    {
        $year  = date('Y');
        $month = date('m');
        $date  = $year . '-' . $month;

        $this->guest = $this->getGuest($this->request->hotel_guest_id);

        $data = [
            'hotel_guest_id'    => optional($this->guest)->id,
            'guest_name'        => $this->request->guest_name,
            'invoice_date'      => $this->request->date ?? date('Y-m-d'),
            'subtotal'          => $this->request->subtotal ?? 0,
            'payable_amount'    => $this->request->payable_amount ?? 0,
            'discount'          => $this->request->discount ?? 0,
            'paid_amount'       => $this->request->paid_amount ?? 0,
            'booking_id'        => optional($this->guest)->booking_id,
        ];
        $this->sale = HotelServiceSale::create($data);

        // Update Invoice
        $this->sale->update([
            'invoice_no' => $this->transection_service->getInvoiceNo(),
        ]);

        // Set Next Invoice ID
        $this->transection_service->setNextInvoiceNo('Service Sale', $date);

        // $this->updateLedger('In');
    }




    public function storeItem()
    {

        foreach ($this->request->service_id as $key => $service_id) {
            $this->sale->saleItems()->create([
                'hotel_service_id'         => $service_id,
                'price'                    => $this->request->price[$key],
                'quantity'                 => $this->request->quantity[$key],
            ]);
        }
    }






    public function makePayment()
    {
        // dd($this->request->all());

        (new HotelTransactionService())->storeTransaction(
            $this->sale,
            $this->sale->id,
            'Hotel Service Sale',
            AccountType::first()->id,
            $this->request->payable_amount,
            $this->request->discount,
            $this->request->paid_amount,
            0,
            0,
            optional($this->guest)->booking_id,
            $this->invoice_no
        );

        // if ($this->request->paid_amount > 0) {

        //     $this->updateLedger('Out');
        // }
    }








    // ############################     private  methods    ##########################

    private function getGuest($guest_id)
    {

        return Guest::find($guest_id);
    }










    public function duePayment($service_sale)
    {


        $this->sale = $service_sale;
        $this->request->paid_amount = $service_sale->paid_amount + $this->request->payable_amount;
        $this->request->date = date('Y-m-d');



        $service_sale->update([
            'paid_amount' => $service_sale->paid_amount + $this->request->payable_amount,
        ]);

        $this->updatePayment($service_sale->id);

        // $this->updateLedger('Out');
    }


    public function updatePayment($get_due)
    {
        // $transection = HotelTransection::where('source_type', 'Hotel Service Sale')->where('source_id', $get_due)->first();
        // $get_collection = $transection->collection;
        // $transection->update([
        //     'collection' => $get_collection + $this->request->payable_amount,
        // ]);


        (new HotelTransactionService())->storeTransaction(
            $this->sale,
            $this->sale->id,
            'Hotel Service Sale',
            $this->request->account_type_id,
            $this->request->payable_amount,
            $this->request->discount,
            $this->request->paid_amount,
            0,
            0,
            optional($this->guest)->booking_id,
            $this->invoice_no
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
    }
}
