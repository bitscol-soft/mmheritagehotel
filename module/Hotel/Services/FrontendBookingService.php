<?php

namespace Module\Hotel\Services;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Module\Hotel\Models\Rooms;
use Module\Hotel\Models\Booking;
use Module\Hotel\Models\RoomLog;
use Illuminate\Support\Facades\DB;
use Module\Hotel\Models\RoomCategory;
use Module\Hotel\Models\BookingDetails;
use Module\Hotel\Models\InvoiceGenerate;
use Module\Hotel\Models\HotelTransection;
use Module\Hotel\Models\BookingDateDetails;
use Module\Hotel\Models\BookingMemberDetail;
use Module\Hotel\Services\AccountTransactionService;
use Module\HotelService\Services\HotelTransactionService;

class FrontendBookingService
{

    public $booking;
    public $check_in_date;
    public $check_out_date;


    function dateDiffInDays($date1, $date2)
    {
        $diff = strtotime($date2) - strtotime($date1);
        return abs(round($diff / 86400));
    }


    /*
     |--------------------------------------------------------------------------
     | STORE METHOD
     |--------------------------------------------------------------------------
    */
    public function store($request, $guest, $vat_amount, $service_amount, $roomPrice)
    {

        $check_in_date      = $request->check_in;
        $check_out_date     = $request->check_out;
        $status             = 2;

        $this->booking =  Booking::create([
            'customer_id'      => $guest->id,
            'purpose'          => 1,
            'booking_date'     => date('Y-m-d'),
            'check_in_date'    => $check_in_date,
            'check_out_date'   => $check_out_date,
            'status'           => $status,
            'vat_id'           => 1,
            'vat_amount'       => convertToBDTCurrency($vat_amount),
            'check_in_time'    => null,
            'service_amount'   => convertToBDTCurrency($service_amount),
            'booking_type'     => '',
            'booking_from'     => 3,
            'pickup'           => $request->pickup,
            'drop'             => $request->drop,
            'pickup_flight'    => $request->pickup_flight,
            'drop_flight'      => $request->drop_flight,
            'reference'        => $request->reference,
            'book_type'        => $request->book_type,
            'purpose'          => $request->purpose
        ]);

        $invoice_no = $this->getBookingInvoiceNo();

        // Update Invoice
        $this->booking->update([
            'booking_number' => $invoice_no,
        ]);

        // Make Payment for Booking
        $this->makePayment(
            $this->booking->id,
            $service_amount + $vat_amount + $roomPrice,
            0,
            0,
            $vat_amount,
            $service_amount,
            $invoice_no,
            $request->payment_type ?? null,
            'Booking',
            setting('root_currency'),
        );

        return $this->booking;
    }







    /*
     |--------------------------------------------------------------------------
     | saveBookingDetails METHOD FOR Save New Bookings
     |--------------------------------------------------------------------------
    */
    public function saveBookingDetails($request, $booking_id, $nighCount, $vat_amount, $service_amount, $roomPrice)
    {

        // dd($request, $booking_id, $nighCount, $vat_amount, $service_amount, $roomPrice);
        // foreach ($request->room_category as $key => $value) {
            $details = BookingDetails::create([
                'booking_id'        => $this->booking->id,
                'category_id'       => $request->room_category,
                'room_id'           => $request->room_id,
                'guest_count'       => 1,
                'night_count'       => $nighCount ?? 1,
                'infant_count'      => 0,
                'discount_amount'   => 0,
                // 'service_charge'    => convertToBDTCurrency($service_amount ?? 0),
                // 'total_amount'      => convertToBDTCurrency($roomPrice ?? 0),
                'service_charge'    => $service_amount ?? 0,
                'total_amount'      => $roomPrice ?? 0,
                'current_room_rate' => ($roomPrice / $nighCount) ?? 0,
                'status'            => 1,
                'allow_breakfast'   => 0,

            ]);

            $this->bookingDates($request, $booking_id, $details);


            // Save Booking Guest Information
            // $this->bookingGuestInformation($request, $details, $key);
        // }

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
        $period = CarbonPeriod::create($request->check_in, $request->check_out);

        foreach ($period as $date) {

            BookingDateDetails::updateOrCreate([
                'booking_id'            => $booking_id,
                'booking_detail_id'     => $details->id,
                'room_id'               => $details->room_id,
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
                        'room_id'         => $room,
                        'guest_count'     => RoomCategory::find($request->room_category[$key])->guest_capacity ?? 1,
                        'night_count'     => $request->night[$key] ?? 1,
                        'infant_count'    => $request->infant[$key] ?? 0,
                        'discount_amount' => convertToBDTCurrency($request->discount[$key]),
                        'service_charge'  => convertToBDTCurrency($request->room_services[$key] / count($room_numbers)) ?? 0,
                        'total_amount'    => convertToBDTCurrency($request->room_amount[$key]),
                        'status'          => 1,
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

        $this->check_in_date = Carbon::parse($request->check_in)->format('Y-m-d');
        $this->check_out_date = Carbon::parse($request->check_out)->format('Y-m-d');

        $this->booking = $booking;

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
        ]);


        // Make Payment for Booking
        $this->makePayment(
            $this->booking->id,
            $request->sub_total,
            array_sum($request->discount),
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
     | CREATE/UPDATE BOOKING DETAILS INFORMATION
     |--------------------------------------------------------------------------
    */
    public function updateBookingDetails($request, $booking)
    {
        // dd($request->all());
        foreach ($request->room_category ?? [] as $key => $room_id) {

            $booking_detail = $this->booking->bookingDetails()->updateOrCreate([

                'category_id'     => $request->room_category[$key],
                'room_id'         => $request->room_number[$key],
            ], [
                'guest_count'     => $request->guest[$key],
                'infant_count'    => $request->infant[$key],
                'night_count'     => $request->night[$key],
                'discount_amount' => $request->discount[$key],
                'total_amount'    => $request->amount[$key],
                'status'          => 1,
                'allow_breakfast' => $request->allow_breakfast[$key] ?? 0,
            ]);
            // dd($booking_detail);

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
            // today_from_system(),
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
     |--------------------------------------------------------------------------
     | releaseBooking METHOD FOR Checkout Booking
     |--------------------------------------------------------------------------
    */

    public function releaseBooking()
    {
        $request = \request();

        $total_amount = $request->paid_amount;

        foreach ($request->item_ids as $key => $item_id) {

            $transaction = HotelTransection::where('source_id', $item_id)->where('source_type', $request->item_types[$key])->first();

            $transaction->update([
                'total_amount'      => $request->total_amount[$key],
                'vat_amount'        => $request->vat_amount[$key],
                'service_charge'    => $request->service_charge[$key],
            ]);

            $transaction->refresh();
            // $total_amount = $transaction->collection + $total_amount;

            // if ($total_amount > $transaction->total_amount) {
            //     $total_amount  = $transaction->due_amount;
            // }

            // if ($transaction->due_amount <= 0) {
            //     $total_amount = 0;
            // }


            $transaction->update([
                'collection' => $transaction->collection + $request->item_amount[$key],
            ]);

            // $total_amount -= $request->item_amount[$key];
            // if ($total_amount < 0) {
            //     $total_amount  = 0;
            // }

        }
    }


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

    public function transaction($source, $source_type, $amount)
    {
        $transaction    = HotelTransection::where('source_id', $source->id)->where('source_type', $source_type)->first();
        $this->booking  = $source;
        $amount = convertToBDTCurrency($amount, 1);

        $this->makePayment(
            $source->id,
            $transaction->total_amount,
            0,
            $amount + $transaction->collection,
            $transaction->vat_amount,
            $transaction->service_charge,
            $source->booking_number,
            optional($source->paymentType)->id,
            'Due Collection',
            setting('root_currency') //need to check
        );
    }







    /*
     |--------------------------------------------------------------------------
     | releaseBooking METHOD FOR Checkout Booking
     |--------------------------------------------------------------------------
    */


    public function deleteBooking($id)
    {
        DB::transaction(function () use ($id) {

            $booking = Booking::find($id);

            $guest = $booking->guestInfo;

            if ($guest) {

                $guest->update([
                    'booking_id' => null,
                ]);
            }

            $booking->transection->delete();
            $booking->hotel_transaction()->delete();
            $booking->transection_ledgers()->delete();
            $booking->bookingDates()->delete();
            $booking->bookingAdjusts()->delete();
            $booking->bookingDetails()->delete();
            $booking->transactions()->delete();
            $booking->bookingExtraCharge()->delete();
            $booking->members()->delete();
            $booking->delete();
        });
    }


    public function roomStatus($room_id, $status)
    {
        Rooms::where('id', $room_id)->update([
            'status'    => $status,
        ]);
    }


    public function roomLog($room_id, $status, $remark)
    {
        RoomLog::create([

            'created_by'    => auth()->id(),
            'room_id'       => $room_id,
            'status'        => $status,
            'remarks'       => $remark,
            'date'          => date('Y-m-d'),

        ]);
    }







    /*
     |--------------------------------------------------------------------------
     | UPDATE RooM Current Price
     |--------------------------------------------------------------------------
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
}
