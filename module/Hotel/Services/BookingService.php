<?php

namespace Module\Hotel\Services;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Module\Hotel\Models\Guest;
use Module\Hotel\Models\Rooms;
use Module\Hotel\Models\Booking;
use Module\Hotel\Models\RoomLog;
use Illuminate\Support\Facades\DB;
use Module\Account\Models\Account;
use Module\Restaurant\Models\Sale;
use Module\Hotel\Models\AccountType;
use Module\Hotel\Models\RoomCategory;
use Module\Account\Models\Transaction;
use Illuminate\Support\Facades\Session;
use Module\Account\Models\AccountGroup;
use Module\Hotel\Models\BookingDetails;
use Module\Hotel\Models\InvoiceGenerate;
use Module\Hotel\Models\HotelTransection;
use Module\Hotel\Models\BookingDateDetails;
use Module\Hotel\Models\BookingExtraCharge;
use Module\Hotel\Models\BookingMemberDetail;
use Module\Hotel\Models\HotelTransactionLedger;
use Module\Hotel\Services\AccountTransactionService;
use Module\HotelService\Services\HotelTransactionService;
use Module\HotelService\Services\HotelTransactionLedgerService;
use Module\Account\Services\AccountTransactionService as ServicesAccountTransactionService;

class BookingService
{

    private $request;
    public $booking;
    public $check_in_date;
    public $check_out_date;

    private $transactionService;


    // CONSTRUCT METHOD
    public function __construct()
    {
        $this->request = \request();

        $this->transactionService   = new ServicesAccountTransactionService();
    }



    /*
     |--------------------------------------------------------------------------
     | STORE METHOD
     |--------------------------------------------------------------------------
    */
    public function store($request)
    {

        $this->check_in_date    = Carbon::parse($request->check_in_date)->format('Y-m-d');
        $this->check_out_date   = Carbon::parse($request->check_out_date)->format('Y-m-d');

        $status                = $request->status ?? 0;

        if ($request->type == 'reserve') {
            $status             = 0;
        } else {
            $status             = 2;
        }
        if ($request->status == 1  && ($this->check_in_date == date('Y-m-d')) ) {
            $status             = 1;
        }

        $this->booking =  Booking::create([
            'customer_id'           => $request->customer_id,
            'purpose'               => $request->purpose,
            'booking_date'          => $request->booking_date,
            'check_in_date'         => $this->check_in_date,
            'check_out_date'        => $this->check_out_date,
            'reference'             => $request->reference,
            'pickup'                => $request->pickup,
            'drop'                  => $request->drop,
            'pickup_flight'         => $request->pickup_flight,
            'drop_flight'           => $request->drop_flight,
            'status'                => $status,
            'vat_id'                => 1,
            'vat_amount'            => convertToBDTCurrency($request->vat_amount),
            'payment_id'            => $request->payment_type,
            'check_in_time'         => $request->status ? ($this->check_in_date == date('Y-m-d') ? now() : null) : null,
            'service_amount'        => convertToBDTCurrency($request->service_amount),
            'check_in_note'         => $request->check_in_note,
            'booking_type'          => $request->bulk_booking == 1 ? 'Bulk' : '',
            'book_type'             => $request->book_type,
            'booking_pax'           => $request->booking_pax,
            'child_pax'             => $request->child_pax,
            'adult_pax'             => $request->adult_pax,
            'emergency_cont_name'   => $request->emergency_cont_name,
            'emergency_cont_phone'  => $request->emergency_cont_phone,
            'purpose_id'            => $request->purpose_id,
            'platform_id'           => $request->platform_id,
            'is_room_in_invoice'    => $request->is_room_in_invoice ?? 0,
            'card_info'             => $request->card_info ?? null,
            'company_id'            => $request->company_id ?? null,
        ]);

        $invoice_no = $this->getBookingInvoiceNo();

        // Update Invoice
        $this->booking->update([
            'booking_number' => $invoice_no,
        ]);

        // Make Payment for Booking
        $this->makePayment(
            $this->booking->id,
            $request->sub_total,
            // array_sum($request->discount),
            0,
            $request->advanced_amount ?? 0,
            $request->vat_amount,
            $request->service_amount,
            $invoice_no,
            $request->payment_type,
            'Booking',
            $request->currency_type,
        );

        return $this->booking;
    }







    /*
     |--------------------------------------------------------------------------
     | saveBookingDetails METHOD FOR Save New Bookings
     |--------------------------------------------------------------------------
    */
    public function saveBookingDetails($request, $booking_id)
    {
        if ($request->bulk_booking == 1) {
            $this->bulkRoomBookingStore($request);
        } else {

            foreach ($request->room_category as $key => $value) {
                $details = BookingDetails::create([
                    'booking_id'        => $this->booking->id,
                    'category_id'       => $request->room_category[$key],
                    'room_id'           => $request->room_number[$key] ?? null,
                    'guest_count'       => $request->guest[$key],
                    'night_count'       => $request->night[$key] ?? 1,
                    'infant_count'      => $request->infant[$key],
                    'child_count'       => $request->child[$key],
                    'discount_amount'   => convertToBDTCurrency($request->discount[$key] ?? 0),
                    'discount_type'     => $request->discount_type[$key],
                    'room_discount'     => convertToBDTCurrency($request->discount[$key] ?? 0),
                    'service_charge'    => convertToBDTCurrency($request->room_services[$key] ?? 0),
                    'total_amount'      => convertToBDTCurrency($request->amount[$key] ?? 0),
                    'current_room_rate' => $request->room_price[$key] ?? 0,
                    'status'            => $this->booking->status,
                    'allow_breakfast'   => $request->allow_breakfast[$key] ?? 0,
                    'breakfast_qty'     => $request->breakfast_qty[$key] ?? 0,

                ]);

                $this->bookingDates($request, $booking_id, $details);


                // Save Booking Guest Information
                $this->bookingGuestInformation($request, $details, $key);
            }
        }


    }







    /*
     |--------------------------------------------------------------------------
     | SAVE MEMBER DETAILS
     |--------------------------------------------------------------------------
    */
    public function saveMemberDetails($request)
    {
        foreach ($request->member_names ?? [] as $key => $name) {

            if ($name != '') {
                BookingMemberDetail::create([

                    'booking_id'        => $this->booking->id,
                    'guest_id'          => $request->customer_id,
                    'name'              => $name,
                    'phone'             => $request->member_phone_nos[$key],
                    'email'             => $request->member_emails[$key],
                    'gender'            => $request->member_genders[$key],
                    'age'               => $request->member_age[$key],
                    'relation'          => $request->relation[$key],
                    'registration_no'   => $request->registration_nos[$key]
                ]);
            }
        }
    }





    /*
     |--------------------------------------------------------------------------
     | Store Booking ALL DATES
     |--------------------------------------------------------------------------
    */
    public function bookingDates($request, $booking_id, $details, $status = 0)
    {
        $period = CarbonPeriod::create($this->check_in_date, $this->check_out_date);

        foreach ($period as $date) {

            BookingDateDetails::updateOrCreate([
                'booking_id'            => $booking_id,
                'booking_detail_id'     => $details->id,
                'room_id'               => $details->room_id ?? null,
                'date'                  => fdate($date, 'Y-m-d'),
            ],[
                'status'                => $this->booking->status ?? $status
            ]);

        }
    }






    /*
     |--------------------------------------------------------------------------
     | BULK ROOM BOOKING
     |--------------------------------------------------------------------------
    */
    public function bulkRoomBookingStore($request)
    {
        foreach (array_filter($request->room_number) as $key => $room_numbers) {
            foreach ($room_numbers as $room) {

                    $details = BookingDetails::create([
                        'booking_id'      => $this->booking->id,
                        'category_id'     => $request->room_category[$key],
                        'room_id'         => $room ?? null,
                        'guest_count'     => RoomCategory::find($request->room_category[$key])->guest_capacity ?? 1,
                        'night_count'     => $request->night[$key] ?? 1,
                        'infant_count'    => $request->infant[$key] ?? 0,
                        'discount_amount' => convertToBDTCurrency($request->discount[$key]),
                         'discount_type'      => $request->discount_type[$key],
                        'service_charge'  => convertToBDTCurrency($request->room_services[$key] / count($room_numbers)) ?? 0,
                        'total_amount'    => convertToBDTCurrency($request->room_amount[$key] * ($request->night[$key] ?? 1)),
                        'current_room_rate' => convertToBDTCurrency($request->room_amount[$key]),
                        'status'          => $this->booking->status,
                        'allow_breakfast' => $request->allow_breakfast[$key] ?? 0,
                    ]);

                    $this->bookingDates($request, $this->booking->id, $details);
            }
        }
    }






    /*
     |--------------------------------------------------------------------------
     | STORE BOOKING GUEST INFORMATION LIKE NAME, PHONE, ADDRESS, ATTACHMENT
     |--------------------------------------------------------------------------
    */
    public function bookingGuestInformation($request, $details, $id)
    {

        foreach (array_filter($request->guest_names[$id] ?? []) as $key => $name) {
            $this->booking->booking_guests()->create([
                'name'              => $name,
                'booking_detail_id' => $details->id,
                'address'           => $request->guest_address[$id][$key],
                'phone'             => $request->guest_phones[$id][$key],
            ]);
        }
    }



    /*
     |--------------------------------------------------------------------------
     | UPDATE METHOD
     |--------------------------------------------------------------------------
    */
    public function update($request, $booking)
    {

        $this->check_in_date    = Carbon::parse($request->check_in)->format('Y-m-d');
        $this->check_out_date   = Carbon::parse($request->check_out)->format('Y-m-d');

        $this->booking          = $booking;

        $this->booking->update([
            'customer_id'    => $request->customer_id,
            'purpose'        => $request->purpose,
            'booking_date'   => $request->booking_date,
            'check_in_date'  => $request->check_in,
            'check_out_date' => $request->check_out,
            'reference'      => $request->reference,
            'pickup'         => $request->pickup,
            'drop'           => $request->drop,
            'pickup_flight'  => $request->pickup_flight,
            'drop_flight'    => $request->drop_flight,
            'sub_total'      => $request->sub_total,
            'payment_way'    => $request->payment_way,
            'book_type'      => $request->book_type,
            'booking_pax'    => $request->booking_pax,
            'emergency_cont_name'      => $request->emergency_cont_name,
            'emergency_cont_phone'     => $request->emergency_cont_phone,
            'purpose_id'     => $request->purpose_id,
            'platform_id'    => $request->platform_id,
        ]);


        // Make Payment for Booking
        $this->makePayment(
            $this->booking->id,
            $request->sub_total,
            // array_sum($request->discount),
            0,
            $request->advanced_amount ?? 0,
            $request->vat_amount,
            $request->service_amount,
            $this->booking->booking_number,
            $request->payment_type,
            'Booking',
            1,
        );

    }




    /*
     |--------------------------------------------------------------------------
     | ASSIGN METHOD
     |--------------------------------------------------------------------------
    */
    public function assign($request, $booking)
    {

        $this->check_in_date    = Carbon::parse($request->check_in)->format('Y-m-d');
        $this->check_out_date   = Carbon::parse($request->check_out)->format('Y-m-d');

        $this->booking          = $booking;

        $this->booking->update([
            'customer_id'    => $request->customer_id,
            'purpose'        => $request->purpose,
            'booking_date'   => $request->booking_date,
            'check_in_date'  => $request->check_in,
            'check_out_date' => $request->check_out,
            'reference'      => $request->reference,
            'pickup'         => $request->pickup,
            'drop'           => $request->drop,
            'pickup_flight'  => $request->pickup_flight,
            'drop_flight'    => $request->drop_flight,
            'sub_total'      => $request->sub_total,
            'payment_way'    => $request->payment_way,
            'book_type'      => $request->book_type,
            'booking_pax'    => $request->booking_pax,
            'emergency_cont_name'      => $request->emergency_cont_name,
            'emergency_cont_phone'     => $request->emergency_cont_phone,
            'purpose_id'     => $request->purpose_id,
            'platform_id'    => $request->platform_id,
        ]);


        // Make Payment for Booking
        $this->makePayment(
            $this->booking->id,
            $request->sub_total,
            // array_sum($request->discount),
            0,
            $request->advanced_amount ?? 0,
            $request->vat_amount,
            $request->service_amount,
            $this->booking->booking_number,
            $request->payment_type,
            'Booking',
            1,
        );

    }


        /*
     |--------------------------------------------------------------------------
     | ASSIGN BOOKING DETAILS INFORMATION
     |--------------------------------------------------------------------------
    */
    public function assignBookingDetails($request, $booking)
    {
        // $this->booking          = $booking;

        foreach ($request->room_category ?? [] as $key => $room_id) {

            // $room_discount = (double)$this->booking->bookingDetails()->where('room_id', $request->room_number[$key])->first()->room_discount;
            // $discount_amount = $this->booking->bookingDetails()->where('room_id', $request->room_number[$key])->first()->discount_amount;

            $booking_detail = $this->booking->bookingDetails()->update([

                'category_id'     => $request->room_category[$key],
                'room_id'         => $request->room_number[$key],
            ], [
                'guest_count'     => $request->guest[$key],
                'infant_count'    => $request->infant[$key],
                'night_count'     => $request->night[$key],
                'discount_amount' => $request->discount[$key],
                // 'room_discount'   => $room_discount == 0 ? $discount_amount : $room_discount,
                'total_amount'    => $request->amount[$key],
                'service_charge'  => convertToBDTCurrency($request->room_services[$key] ?? 0),
                // 'current_room_rate' => $request->room_price[$key] ?? 0,
                'status'          => 1,
                'allow_breakfast' => $request->allow_breakfast[$key] ?? 0,

            ]);


            $this->booking->bookingDetails()->whereNotIn('category_id', $request->room_category)->whereNotIn('room_id', $request->room_number)->delete();

            $this->booking->bookingDates($request, $booking, $booking_detail, 0);

        }
    }








    /*
     |--------------------------------------------------------------------------
     | CREATE/UPDATE BOOKING DETAILS INFORMATION
     |--------------------------------------------------------------------------
    */
    public function updateBookingDetails($request, $booking){


        foreach ($request->room_category ?? [] as $key => $room_id) {

            $room_discount = (double)$this->booking->bookingDetails()->where('room_id', $request->room_number[$key])->first()->room_discount;
            $discount_amount = $this->booking->bookingDetails()->where('room_id', $request->room_number[$key])->first()->discount_amount;


            $booking_detail = $this->booking->bookingDetails()->updateOrCreate([

                'category_id'     => $request->room_category[$key],
                'room_id'         => $request->room_number[$key],
            ], [
                'guest_count'     => $request->guest[$key],
                'infant_count'    => $request->infant[$key],
                'night_count'     => $request->night[$key],
                'discount_amount' => $request->discount[$key],
                'room_discount'   => $discount_amount ?? $room_discount,
                'total_amount'    => $request->amount[$key],
                'service_charge'  => convertToBDTCurrency($request->room_services[$key] ?? 0),
                // 'current_room_rate' => $request->room_price[$key] ?? 0,
                'status'          => 1,
                'allow_breakfast' => $request->allow_breakfast[$key] ?? 0,
                'discount_type'   => $request->discount_type[$key] ?? 0,

            ]);

            $this->booking->bookingDetails()->whereNotIn('category_id', $request->room_category)->whereNotIn('room_id', $request->room_number)->delete();
            $this->bookingDates($request, $this->booking->id, $booking_detail, 0);

        }
    }








    /*
     |--------------------------------------------------------------------------
     | getBookingInvoiceNo METHOD FOR Generate INVOICE NO
     |--------------------------------------------------------------------------
    */
    public function getBookingInvoiceNo(): string
    {
        $year = date('Y');
        $month = date('m');
        $date  = $year . '-' . $month;

        $nextId = optional(InvoiceGenerate::query()
            ->where('type', 'Booking')
            ->where('year', $date)
            ->first())->next_id;

        if ($nextId == null)

            $nextId = InvoiceGenerate::query()
                ->create([
                    'type' => 'Booking',
                    'year' => $date,
                    'next_id' => 1,
                ])->next_id;

        return $date
            . '-'
            . str_pad($nextId, 4, "0", STR_PAD_LEFT);
    }






    /*
     |--------------------------------------------------------------------------
     | setNextInvoiceNo METHOD FOR SET NEXT INVOICE NO
     |--------------------------------------------------------------------------
    */

    public function setNextInvoiceNo($type, $time)
    {
        $invoice_no = InvoiceGenerate::query()
            ->firstOrCreate([
                'type' => $type,
                'year' => $time,
            ]);

        $invoice_no->increment('next_id');
        $invoice_no->save();
    }








    /*
     |--------------------------------------------------------------------------
     | makePayment METHOD FOR  Payment Booking
     |--------------------------------------------------------------------------
    */

    public function makePayment($booking_id, $payable_amount, $discount, $paid_amount = 0, $vat, $service_charge, $invoice_no, $payment_type, $remark, $currency_type, $collectedDueAmount = null)
    {
        (new HotelTransactionService())->storeTransaction(
            $this->booking,
            $booking_id,
            'Booking',
            $payment_type,
            $payable_amount,
            $discount,
            $paid_amount,
            $vat,
            $service_charge,
            $booking_id,
            $invoice_no,
            $this->booking->booking_date,
            $remark,
            request('extra_charge'),
            $currency_type,
            $collectedDueAmount
        );


        // if (setting('enable_account_transaction_for_hotel')) {
        //     (new AccountTransactionService($this->booking->guestInfo->name, $this->booking, 0))->makeAccountTransaction();
        // }

    }



    /*
     |-------------------------------------------------------------------------------------------
     | RELEASE BOOKING V2 METHOD FOR BOOKING CHECKOUT - THIS METHOD WILL UPDATE HOTEL TRANSACTION
     |-------------------------------------------------------------------------------------------
    */
    public function releaseBooking($booking)
    {
        $request = \request();

        $total_amount = $request->paid_amount;

        foreach ($request->item_ids ?? [] as $key => $item_id) {

            $transactions = HotelTransection::where('source_id', $item_id)
                            ->where('source_type', $request->item_types[$key])
                            ->get();

         foreach($transactions as $transaction){

            $transaction->vat_amount = $request->vat_amount[$key];
            $transaction->service_charge = $request->service_charge[$key];

            (new HotelTransactionLedgerService())->storeTransaction(
                $item_id,
                $request->item_types[$key],
                $request->payment_type,
                $transaction->id,
                $request->paid_amount,
                $transaction->date,
                "Check Out"
            );


            $item_amount     = $request->item_amount;
            $total           = $transaction->total_amount;
            $due_amount      = $transaction->due_amount;
            $tra_dis         = $transaction->discount;
            $discount        = $request->discount;
            $old_discount    = $request->old_discount;
            $collect         = $transaction->collection;

            // for main
            // collection1 = total_amount + vat_amount + service_charge - discount

            // if($transaction->total_amount){
            //     $collection = $total;
            // }



            // oldSelect
            //  $collection = $collect;
            //  if($due_amount != 0 &&  $total_amount <= $due_amount){
            //      $collection = $collect += $total_amount;
            //  }
            // // for extra charge
            // // collection2 = total_amount + vat_amount + service_charge + extra_charge

            // $extra = $transaction->extra_charge;

            // // dd([$collection, $extra, $discount]);
            // if ($transaction->extra_charge == null || $transaction->extra_charge == 0) {
            //     $transaction->update([
            //         'collection'   => $transaction->source_type == "Booking" ? $request->total_amount[$key] -  $discount : $request->total_amount[$key],
            //         'discount'     => $transaction->source_type == "Booking" ? $discount + $tra_dis : 0,
            //     ]);
            // } elseif($transaction->extra_charge) {
            //     $transaction->update([
            //         'collection' => $extra,
            //     ]);
            // }





            if ($due_amount != 0 && $total_amount <= $due_amount) {
                $collection = $collect + $total_amount;
            }

            // FOR EXTRA CHARGE
            $extra = $transaction->extra_charge;

            if ($transaction->extra_charge == null || $transaction->extra_charge == 0) {
                if ($total_amount >= $request->total_amount[$key]) {
                    $transaction->update([
                        'collection' => $transaction->source_type == "Booking" ? $request->total_amount[$key] - $discount : $request->total_amount[$key],
                        'discount'   => $transaction->source_type == "Booking" ? $discount : 0,
                    ]);
                } else {
                    $transaction->update([
                        'collection' => $transaction->source_type == "Booking" ? $collect + $total_amount : 0,
                        'discount'   => $transaction->source_type == "Booking" ? $discount : 0,
                    ]);
                }
            } elseif ($transaction->extra_charge) {
                $transaction->update([
                    'collection' => $extra,

                ]);
            }


            // UPDATE PAY BY IN INVOICE
            $booking->update([
                'pay_by'    => $request->pay_by,
            ]);


            // UPDATE REST SALE TABLE DATA
            if($transaction->source_type == "Restaurant Sale" && $transaction->collection == $transaction->total_amount){
                Sale::where('hotel_booking_id', $booking->id)->update([
                    'paid_amount'=> $request->item_amount[$key],
                    'payment_status'=> "Paid",
                ]);

                Session::put('fromCheckoutStore', "Yes");
                Session::put('bookingId', $booking->id);
            }


            //  Migration Varchual DataBase
            // `total_amount` - `collection` - `discount`
            $this->accountTransactionForCheckout($request, $key, $transaction);

          }
        }
    }








    //------------------------------------------------------------------------------------------------------//
    //    ACC ACCOUNT TRANSACTION FOR BOOKING CHECKOUT METHOD - THIS METHOD WILL CREATE ACC TRANSACTION     //
    //------------------------------------------------------------------------------------------------------//
    public function accountTransactionForCheckout($request, $key, $transaction){

        $description            = null;

        if ($request->item_types[$key] == 'Booking') {
            $model              = Booking::find($request->item_ids[$key]);
        }
        else{
            $model              = Sale::find($request->item_ids[$key]);
        }

        if ($model->transactions != null) {
            $model->transactions()->delete();
        }

        $sale_account       = $this->transactionService->getSaleAccount();
        $balance_type       = optional(AccountGroup::find(1))->balance_type;

        $module_account     = Account::where('name', $request->item_types[$key])
                                    ->where('account_group_id', 1)
                                    ->where('account_control_id', 1)
                                    ->where('account_subsidiary_id', 8)
                                    ->where('balance_type', $balance_type)
                                    ->first();

        $cashAccount        = $request->payment_type != null ? getPaymentTypeAccount($request->payment_type) : Account::find(55);


        // ACC ACCOUNT SALE TRANSACTION
        $this->transactionService->storeTransaction($model->company_id ?? auth()->user()->company_id,  $model,  $model->invoice_no ?? $model->booking_number,  $sale_account,    0,                             $transaction->total_amount,   $transaction->date ?? date('Y-m-d'),   'credit',  'Sale',         $description);
        //  Payable Amount

        // MODULE TRANSACTION / CUSTOMER DUE TRANSACTION IF HAVE
        $this->transactionService->storeTransaction($model->company_id ?? auth()->user()->company_id,  $model,  $model->invoice_no ?? $model->booking_number,  $module_account,  $transaction->total_amount,    $transaction->collection,     $transaction->date ?? date('Y-m-d'),   'debit',   'Customer Due', $description);
        //  Due Amount

        // SINGLE PAYMENT METHOD TRANSACTION IF SELECT ONLY ONE PAYMENT TYPE
        $this->transactionService->storeTransaction($model->company_id ?? auth()->user()->company_id,  $model,  $model->invoice_no ?? $model->booking_number,  $cashAccount,     $transaction->collection,      0,                            $transaction->date ?? date('Y-m-d'),   'debit',   'Payment',      $description);
        //  Paid Amount


    }










    //-----------------------------------------------------------------------------------------------------//
    //     RELEASE BOOKING V2 METHOD FOR BOOKING CHECKOUT - THIS METHOD WILL CREATE HOTEL TRANSACTION      //
    //-----------------------------------------------------------------------------------------------------//
    public function releaseBookingV2($booking)
    {
        $request = \request();

        $total_amount = $request->paid_amount;

        foreach ($request->item_ids as $key => $item_id) {

            // HOTEL ACCOUNT TRANSACTION
            $hotelTransaction = HotelTransection::create([
                'source_id'         => $booking->id,
                'source_type'       => 'Booking',
                'invoice_no'        => $booking->booking_number,
                'date'              => date('Y-m-d'),
                'account_type_id'   => $request->payment_type,
                'total_amount'      => 0,
                'collection'        => $request->item_amount[$key],
                'discount'          => 0,
                'vat_amount'        => $request->vat_amount[$key],
                'service_charge'    => $request->service_charge[$key],
                'change_amount'     => 0,
                'extra_charge'      => 0,
                'datetime'          => now(),
                'booking_id'        => $booking->id,
            ]);


            // HOTEL TRANSACTION LEDGER
            if ($request->item_amount[$key] > 0) {

                HotelTransactionLedger::create([
                    'source_id'             => $hotelTransaction->source_id,
                    'source_type'           => 'Booking',
                    'payment_type'          => $request->payment_type,
                    'date'                  => date('Y-m-d'),
                    'hotel_transaction_id'  => $hotelTransaction->id,
                    'in'                    => $request->item_amount[$key],
                    'out'                   => 0,
                    'remarks'               => 'Booking Checkout',
                    'datetime'              => now(),
                ]);

            }

            // ACC TRANSACTION
            $this->accountTransactionForDueCollection($booking, $request->item_amount[$key]);

        }
    }









    /*
     |--------------------------------------------------------------------------
     | QUICK RELEASING BOOKING METHOD
     |--------------------------------------------------------------------------
    */
    public function quickReleaseBooking($booking_id)
    {
        $transactions = HotelTransection::where('booking_id', $booking_id)->get();

        foreach ($transactions as $key => $transaction) {

            $transaction->update([
                'collection' => $transaction->total_amount,
            ]);
        }
    }






    /*
     |--------------------------------------------------------------------------
     | releaseBooking METHOD FOR Checkout Booking
     |--------------------------------------------------------------------------
    */
    public function transaction($request, $source, $source_type, $amount, $payment_type = null)
    {

        $transaction    = HotelTransection::where('source_id', $source->id)->where('source_type', $source_type)->first();
        $this->booking  = $source;
        $amount         = convertToBDTCurrency($amount, 1);

        $this->makePayment(
            $source->id,
            $transaction->total_amount,
            0,
            $amount += $transaction->collection,
            $transaction->vat_amount,
            $transaction->service_charge,
            // $transaction->extra_charge,
            $source->booking_number,
            $payment_type != null ? $payment_type : optional($source->paymentType)->id,
            'Due Collection',
            setting('root_currency'), //need to check
            $amount
        );




    }







    /*
     |--------------------------------------------------------------------------
     | DueTransaction METHOD DUE COLLECTED
     |--------------------------------------------------------------------------
    */
    public function DueTransaction($request, $source, $source_type, $amount, $payment_type = null)
    {

        $transaction    = HotelTransection::where('source_id', $source->id)->where('source_type', $source_type)->first();
        $this->booking  = $source;
        $amount         = convertToBDTCurrency($amount, 1);

        $this->makePayment(
            $source->id,
            $transaction->total_amount,
            0,
            $transaction->collection,
            $transaction->vat_amount,
            $transaction->service_charge,
            // $transaction->extra_charge,
            $source->booking_number,
            $payment_type != null ? $payment_type : optional($source->paymentType)->id,
            'Due Collection',
            setting('root_currency'), //need to check
            $amount
        );
    }







    /*
     |--------------------------------------------------------------------------
     | DELETE Booking METHOD
     |--------------------------------------------------------------------------
    */
    public function deleteBooking($id)
    {
        DB::transaction(function () use ($id) {

            $booking = Booking::find($id);

            $guest = $booking->guestInfo;
            $guestImage = $booking->guestImage;


            if ($guest) {

                $guest->update([
                    'booking_id' => null,
                ]);
            }

            $booking->transection->delete();

            // ACC ACCOUNT TRANSACTION DELETE
            $booking->transactions()->delete();

            $booking->hotel_transaction()->delete();
            $booking->transection_ledgers()->delete();
            $booking->bookingDates()->delete();
            $booking->bookingAdjusts()->delete();
            $booking->bookingDetails()->delete();
            $booking->transactions()->delete();
            $booking->bookingExtraCharge()->delete();
            $booking->members()->delete();
            $booking->guestImage()->delete();
            $booking->delete();
        });
    }


 /*
     |--------------------------------------------------------------------------
     | CANCEl Booking METHOD
     |--------------------------------------------------------------------------
    */
    public function CancelBooking($id)
    {
        DB::transaction(function () use ($id) {

            $booking = Booking::find($id);

            $booking->update([
                'status'    => 4,
            ]);

            $booking->bookingDates()->delete();

            $status = 'Ready';

            $remark = 'Room status changed to '. $status . 'by @ '. auth()->user()->name;

            (new BookingService())->roomLog($id, $status, $remark, null);
        });
    }







    /**
     * ---------------------------------------------------
     * ROOM STATUS
     * ---------------------------------------------------
     */
    public function roomStatus($room_id, $status)
    {
        Rooms::where('id', $room_id)->update([
            'status'    => $status,
        ]);
    }






    /**
     * ---------------------------------------------------
     * ROOM LOG
     * ---------------------------------------------------
     */
  public function roomLog($room_id, $status, $remark, $reason)
{
    RoomLog::create([
                'created_by'    => auth()->id(),
                'room_id'       => $room_id,
                'status'        => $status,
                'remarks'       => $remark,
                'date'          => date('Y-m-d'),
                'updated_by'    => auth()->id(),
                'note'          => $reason ?? "NO Extra Charge Added",
                'booked_by'     => auth()->id(),
                'received_by'   => auth()->id(),
    ]);
}










    /**
     * ---------------------------------------------------
     * UPDATE ROOM PRICE BOOKING
     * ---------------------------------------------------
     */
    public function updateRoomPriceInBooking()
    {
        Booking::with('details')->get()->map(function($item){
            $night = Carbon::parse($item->check_out_date)->diffInDays(Carbon::parse($item->check_in_date));

            foreach($item->details as $detail){

                $night = $detail->night == null ? $night : $detail->night;
                if ($night <= 0) {
                    $night = 1;
                }
                $current_room_rate = ($detail->total_amount + $detail->discount_amount) / $night;

                $detail->update([
                    'night_count'       => $night,
                    'current_room_rate' => $current_room_rate,
                ]);
            }
        });
    }






    /**
     * ---------------------------------------------------
     * ROOM WISE CHECKOUT
     * ---------------------------------------------------
     */
    public function roomWiseCheckout($booking, $collection)
    {
        $total_checkout_amount = $booking->hotel_transaction()->sum('total_amount');

        if($total_checkout_amount !== $collection){

            return response()->json([
                'status'    => 0,
                'message'   => 'Error',
                'data'      => 'You can\'t checkout this room. Please collect due amount first.',
            ]);

        }

        DB::transaction(function() use($booking){


            //$this->quickReleaseBooking($booking->id);

            BookingDetails::where('booking_id', $booking->id)->where('room_id', request('room_id'))->update([
                'status'    => 3,
            ]);

            BookingDateDetails::where('booking_id', $booking->id)->where('room_id', request('room_id'))->update([
                'status'    => 3,
            ]);


            $this->roomStatus(request('room_id'), 0);

            $this->roomLog(request('room_id'), 'Dirty', 'Checkout and Room status goes to dirty by @ '.auth()->user()->name, null);


            Guest::find($booking->customer_id)->update([
                'booking_id'    => null
            ]);


            if ($booking->bookingDetails->where('status', 1)->count() <= 1) {

                $booking->update([

                    'check_out_time'  => now(),
                    'status'          => 3,
                    'payment_id'      => request('payment_type'),
                    'payment_way'     => request('payment_way'),
                ]);
            }

        });

        return response()->json([
            'status'    => 1,
            'data'      => 'Room successfully released.',
            'message'   => 'success',
        ]);
    }






    /**
     * ---------------------------------------------------
     * EXTRA CHARGE
     * ---------------------------------------------------
     */
    public function extraCharge($request)
    {

        try {

            DB::transaction(function () use($request) {

                $hotelTransaction   = HotelTransection::where('booking_id' ,$request->booking_id)->first();

                BookingExtraCharge::create([
                    'booking_id'    => $request->booking_id,
                    'extra_amount'  => convertToBDTCurrency($request->extra_amount),
                    'reason'        => $request->reason
                ]);


                if ($request->extra_amount > 0) {
                    HotelTransactionLedger::create([
                        'source_id'             => $hotelTransaction->source_id,
                        'source_type'           => 'Booking',
                        'payment_type'          => $request->payment_type,
                        'date'                  => date('Y-m-d'),
                        'hotel_transaction_id'  => $hotelTransaction->id,
                        'in'                    => convertToBDTCurrency($request->extra_amount),
                        'out'                   => 0,
                        'remarks'               => 'Extra Charge',
                    ]);
                }

                $extra_charge = $this->getExtraChargeValue($request);

                $total_amount = $hotelTransaction->total_amount - $hotelTransaction->extra_charge;  // decrease previous total amount via calculate with previous extra charge
                $total_amount = $total_amount + $extra_charge;

                $hotelTransaction->update([
                    'extra_charge'      => $extra_charge,
                    'total_amount'      => $total_amount,
                ]);


                // ACC ACCOUNT TRANSACTION FOR EXTRA CHARGE
                $this->accountTransactionForExtraCharge($request, $total_amount);


            });

        } catch (\Throwable $th) {
            throw $th;
        }
    }





    /**
     * ---------------------------------------------------
     * EXTRA CHARGE
     * ---------------------------------------------------
     */
    public function extraChargeV2($request)
    {

        try {
            $booking = Booking::find($request->booking_id);

            DB::transaction(function () use($request, $booking) {

                $hotelTransaction = HotelTransection::create([
                    'source_id'         => $request->booking_id,
                    'source_type'       => 'Booking',
                    'invoice_no'        => $booking->booking_number,
                    'date'              => $date ?? date('Y-m-d'),
                    'account_type_id'   => $request->payment_type,
                    'total_amount'      => convertToBDTCurrency($request->extra_amount) ?? $request->extra_amount,
                    'collection'        => 0,
                    'discount'          => 0,
                    'vat_amount'        => 0,
                    'service_charge'    => 0,
                    'change_amount'     => convertToBDTCurrency(request('change_amount')),
                    'extra_charge'      => $request->extra_amount,
                    'datetime'          => now(),
                    'booking_id'        => $booking->id,
                ]);


                BookingExtraCharge::create([
                    'booking_id'    => $request->booking_id,
                    'extra_amount'  => convertToBDTCurrency($request->extra_amount),
                    'reason'        => $request->reason
                ]);


                if ($request->extra_amount > 0) {

                    HotelTransactionLedger::create([
                        'source_id'             => $hotelTransaction->source_id,
                        'source_type'           => 'Booking',
                        'payment_type'          => $request->payment_type,
                        'date'                  => date('Y-m-d'),
                        'hotel_transaction_id'  => $hotelTransaction->id,
                        'in'                    => convertToBDTCurrency($request->extra_amount),
                        'out'                   => 0,
                        'remarks'               => 'Extra Charge',
                        'datetime'              => now(),
                    ]);

                }

                $extra_charge = $this->getExtraChargeValue($request);

                $total_amount = $hotelTransaction->total_amount - $hotelTransaction->extra_charge;  // decrease previous total amount via calculate with previous extra charge
                $total_amount = $total_amount + $extra_charge;

                // $hotelTransaction->update([
                //     'extra_charge'      => $extra_charge,
                //     'total_amount'      => $total_amount,
                // ]);


                // ACC ACCOUNT TRANSACTION FOR EXTRA CHARGE
                $this->accountTransactionForExtraCharge($request, $total_amount);


            });

        } catch (\Throwable $th) {
            throw $th;
        }
    }







    /**
     * ---------------------------------------------------
     * ACC ACCOUNT TRANSACTION FOR EXTRA CHARGE
     * ---------------------------------------------------
     */
    public function accountTransactionForExtraCharge($request, $total_amount)
    {
        $description        = null;
        $model              = Booking::find($request->booking_id);

        $sale_account       = $this->transactionService->getSaleAccount();
        $balance_type       = optional(AccountGroup::find(1))->balance_type;

        $module_account     = Account::where('name', 'Booking')
                                    ->where('account_group_id', 1)
                                    ->where('account_control_id', 1)
                                    ->where('account_subsidiary_id', 8)
                                    ->where('balance_type', $balance_type)
                                    ->first();




        // CHECK SALE ACCOUNT TRANSACTION FOR THIS BOOKING HAS TRANSACTION OR NOT
        $saleTransection = Transaction::where([
                                        'invoice_no'            => $model->booking_number,
                                        'transaction_item_type' => 'Sale',
                                        'balance_type'          => 'credit',
                                        'account_id'            => $sale_account->id,
                                    ])->first();


        // UPDATE ACCOUNT SALE TRANSACTION
        if ($saleTransection == null) {
            $this->transactionService->storeTransaction($model->company_id ?? auth()->user()->company_id,  $model,  $model->invoice_no ?? $model->booking_number,  $sale_account,    0,               $total_amount,   optional($model->transaction)->date ?? date('Y-m-d'),   'credit',  'Sale',         $description);    //  Payable Amount
        }
        else{
            $saleTransection->update([
                'credit_amount' => $total_amount,
            ]);
        }




        // CHECK MODULE ACCOUNT TRANSACTION FOR THIS BOOKING HAS TRANSACTION OR NOT
        $moduleTransection = Transaction::where([
                                        'invoice_no'            => $model->booking_number,
                                        'transaction_item_type' => 'Customer Due',
                                        'balance_type'          => 'debit',
                                        'account_id'            => $module_account->id,
                                    ])->first();

        // UPDATE MODULE TRANSACTION / CUSTOMER DUE TRANSACTION
        if ($moduleTransection == null) {
            $this->transactionService->storeTransaction($model->company_id ?? auth()->user()->company_id,  $model,  $model->invoice_no ?? $model->booking_number,  $module_account,  $total_amount,  0,                optional($model->transaction)->date ?? date('Y-m-d'),   'debit',   'Customer Due', $description);    //  Due Amount
        }
        else{
            $moduleTransection->update([
                'debit_amount' => $total_amount,
            ]);
        }


    }






    /**
     * ---------------------------------------------------
     * GET EXTRA CHARGE VALUE
     * ---------------------------------------------------
     */
    public function getExtraChargeValue($request)
    {
       return BookingExtraCharge::where('booking_id', $request->booking_id)->sum('extra_amount');
    }







    /**
     * ---------------------------------------------------
     * TOTAL TRANSACTION LEDGER
     * ---------------------------------------------------
     */
    public function totalTransactionLedger($request)
    {
       return HotelTransactionLedger::where('booking_id', $request->booking_id)->sum('extra_amount');
    }







    /**
     * ---------------------------------------------------
     * DUE COLLECTION
     * ---------------------------------------------------
     */
    public function dueCollection($request, $booking)
    {
        try {

            DB::transaction(function () use($request, $booking) {


                // HOTEL ACCOUNT TRANSACTION
                $hotelTransaction = HotelTransection::create([
                    'source_id'         => $booking->id,
                    'source_type'       => 'Booking',
                    'invoice_no'        => $booking->booking_number,
                    'date'              => date('Y-m-d'),
                    'account_type_id'   => $request->payment_type,
                    'total_amount'      => 0,
                    'collection'        => convertToBDTCurrency($request->amount) ?? $request->amount,
                    'discount'          => 0,
                    'vat_amount'        => 0,
                    'service_charge'    => 0,
                    'change_amount'     => 0,
                    'extra_charge'      => 0,
                    'datetime'          => now(),
                    'booking_id'        => $booking->id,
                ]);


                // HOTEL TRANSACTION LEDGER
                if ($request->amount > 0) {

                    HotelTransactionLedger::create([
                        'source_id'             => $hotelTransaction->source_id,
                        'source_type'           => 'Booking',
                        'payment_type'          => $request->payment_type,
                        'date'                  => date('Y-m-d'),
                        'hotel_transaction_id'  => $hotelTransaction->id,
                        'in'                    => convertToBDTCurrency($request->amount) ?? $request->amount,
                        'out'                   => 0,
                        'remarks'               => 'Due Collection',
                        'datetime'              => now(),
                    ]);

                }


                // ACC ACCOUNT TRANSACTION FOR EXTRA CHARGE
                $this->accountTransactionForDueCollection($booking, $request->amount);


            });

        } catch (\Throwable $th) {
            throw $th;
        }
    }







    /**
     * ---------------------------------------------------
     * ACC ACCOUNT TRANSACTION FOR EXTRA CHARGE
     * ---------------------------------------------------
     */
    public function accountTransactionForDueCollection($model, $request_amount)
    {

        //------------------------- MODULE ACCOUNT TRANSACTION --------------------------//
        $balance_type       = optional(AccountGroup::find(1))->balance_type;

        $module_account     = Account::where('name', 'Booking')
                                    ->where('account_group_id', 1)
                                    ->where('account_control_id', 1)
                                    ->where('account_subsidiary_id', 8)
                                    ->where('balance_type', $balance_type)
                                    ->first();

        $moduleTransection = Transaction::where([
                                        'invoice_no'            => $model->booking_number,
                                        'transaction_item_type' => 'Customer Due',
                                        'balance_type'          => 'debit',
                                        'account_id'            => $module_account->id,
                                    ])->first();

        if ($moduleTransection == null) {
            $this->transactionService->storeTransaction($model->company_id ?? auth()->user()->company_id,  $model,  $model->invoice_no ?? $model->booking_number,  $module_account,  optional($model->transaction)->total_amount,  0,  optional($model->transaction)->date ?? date('Y-m-d'),   'debit',   'Customer Due',   null);    //  Due Amount
        }
        else{
            $moduleTransection->update([
                'credit_amount' => $moduleTransection->credit_amount + $request_amount,
            ]);
        }




        //------------------------- PAYMENT ACCOUNT TRANSACTION --------------------------//
        $paidDueAmount      = 0;
        $account_type_id    = request('payment_type');
        $cashAccount        = $account_type_id != null ? getPaymentTypeAccount($account_type_id) : Account::find(55);


        $transection = $model->transactions()->where([
            'invoice_no'            => $model->invoice_no ?? $model->booking_number,
            'transaction_item_type' => 'Payment',
            'balance_type'          => 'debit',
            'account_id'            => $cashAccount->id,
        ])->first();

        if ($transection != null) {
            $paidDueAmount = $transection->debit_amount + $request_amount; // IF EXIST THEN UPDATE DEBIT AMOUNT
        }

        $this->transactionService->storeTransaction($model->company_id ?? auth()->user()->company_id,  $model,  $model->invoice_no ?? $model->booking_number,  $cashAccount,   $paidDueAmount > 0 ? $paidDueAmount : $request_amount,   0,   $date ?? date('Y-m-d'),   'debit',   'Payment',    null);    //  Paid Amount


    }



    public function collectDue($request)
    {
        // dd($request->all());

        DB::transaction(function () use($request) {

            $totalPaidAmount     = $this->request->total_paid_amount;


            foreach ($request->item_ids as $key => $item_id) {

                $sale            = Booking::where('id', $item_id)->first();
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
                    // $sale->update([
                    //     'paid_amount'    => $isDue == 1 ? $totalPaidAmount + $itemPreviousCollection : $itemTotalDueAmount + $itemPreviousCollection,
                    //     'due_amount'     => $isDue == 1 ? $itemTotalDueAmount - $totalPaidAmount : 0,
                    //     'change_amount'  => 0,
                    //     'payment_status' => $isDue == 1 ? 'Due' : 'Paid',
                    //     'payment_way'    => $payment_way,
                    // ]);


                    $guest        = Guest::find($this->request->hotel_guest_id);


                    // TRANSACTION UPDATE
                    (new HotelTransactionService())->storeTransaction(
                        $sale,
                        $sale->id,
                        'Booking',
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




}
