<?php

namespace Module\Hotel\Controllers\Report;

use DB;
use App\Models\Company;
use Illuminate\Http\Request;
use Module\Hotel\Models\Guest;
use Module\Hotel\Models\Rooms;
use App\Services\ExportService;
use Module\Hotel\Models\Booking;
use Module\Hotel\Models\RoomLog;
use App\Http\Controllers\Controller;
use Module\Hotel\Models\AccountType;
use Module\Hotel\Models\RoomCategory;
use Module\Hotel\Models\HotelTransection;
use Module\Hotel\Models\NightAuditSummary;
use Module\Hotel\Services\RoomStatusService;
use Module\Hotel\Models\HotelTransactionLedger;

class ReportController extends Controller
{

    private $service;
    private $booking;
    private $booking_count;
    private $night_closing_date;



    /*
     |--------------------------------------------------------------------------
     | CONSTRUCTOR
     |--------------------------------------------------------------------------
    */
    public function __construct()
    {
        $this->service = new ExportService();
    }





    /*
     |--------------------------------------------------------------------------
     | MONTHLY BOOKING REPORT
     |--------------------------------------------------------------------------
    */
    public function monthlyBookingReport(Request $request)
    {

        $date                       = $request->month ?? date('Y-m-d');
        $rooms                      = Rooms::query();
        $data['room_categories']    = RoomCategory::query()->get();
        $data['guests']             = Guest::query()->where('is_bar', 0)->get();
        $data['room_datas']         = $rooms->get();
        $data['rooms']              = $rooms->searchByField('room_category')->searchByField('id')->with('roomCategory:id,name,price','booking_dates.booking.customer:id,name,phone_no,gender,nid_no')->get();


        return view('hotel/reports/monthly/index', $data);
    }




    /*
     |--------------------------------------------------------------------------
     | MONTHLY BOOKING SUMMARY REPORT
     |--------------------------------------------------------------------------
    */
    public function monthlyBookingSummaryReport(Request $request)
    {

        $date                       = $request->month ?? date('Y-m-d');
        $rooms                      = Rooms::query();
        $data['room_categories']    = RoomCategory::query()->get();
        $data['guests']             = Guest::query()->where('is_bar', 0)->get();
        $data['room_datas']         = $rooms->get();
        $data['rooms']              = $rooms->searchByField('room_category')->searchByField('id')->with('roomCategory:id,name,price','booking_dates.booking.customer:id,name,phone_no,gender,nid_no')->get();


        return view('hotel/reports/monthly/booking-ui', $data);
    }





    /*
     |--------------------------------------------------------------------------
     | NIGHT AUDIT REPORT
     |--------------------------------------------------------------------------
    */
    public function nightAudit(Request $request)
    {

        $nightaudits = NightAuditSummary::latest('date')
                                            ->with(['details' => function($query){
                                                $query->whereHas('transaction', function($query){
                                                    $query->where('source_type', 'Booking');
                                                })
                                                ->with('transaction.account', 'transaction.source')
                                                ->with('transaction.transaction_ledgers')
                                                ->withSum('transaction as total_due', 'due_amount')
                                                ->withSum('transaction as total_collection', 'collection')
                                                ->with('transaction','transaction.source','transaction.account');
                                            }])
                                            ->dateFilter();

        $data['nightaudits'] =  $nightaudits->paginate(25);
        $data['account_types'] = AccountType::pluck('name', 'id');
        $data['paginate']     = 1;
        if(request('export_type')){

            $data['paginate']     = 0;
            return $this->service->exportData($data, 'hotel/reports/night-closing/export/', 'Night Closing Report');



        }
        // return view('hotel/reports/night-closing/index', $data);
        return view('hotel/reports/night-closing/indexV2', $data);
    }



     /*
     |--------------------------------------------------------------------------
     | DETAILS SHOW METHOD
     |--------------------------------------------------------------------------
    */
    public function detailsShow($id)
    {

        $data['audits']     = $night_audit =  NightAuditSummary::with('room_details.room:id,room_number')
                                                                    ->withCount(['details as restourantCount' => function($q){
                                                                        $q->whereHas('transaction' , function($q){
                                                                            $q->where('source_type', 'Booking');
                                                                        });
                                                                    }])
                                                                    ->with('details',function($query){
                                                                        $query->whereHas('transaction',function($que){
                                                                            $que->with('account')->with('source')->where('source_type', 'Booking');
                                                                        })->with('transaction','transaction.source','transaction.account');
                                                                    })
                                                                    ->where('date', $id)
                                                                    ->get();


        $check_in           = $night_audit->first()->date;
        $check_out          = fdate($night_audit->last()->date, 'Y-m-d');

        $data['categories'] = (new RoomStatusService())->availableRoom($check_in, $check_out);
        $data['company']    = Company::first();
        $data['account_types']  = AccountType::whereHas('hotelTransactions')->pluck('name', 'id');

        return view('hotel/reports/night-closing/invoice', $data);

    }


    /*
     |--------------------------------------------------------------------------
     | ROOM LOG REPORT
     |--------------------------------------------------------------------------
    */
    public function roomLog(Request $request)
    {

        $data['room_logs']  = RoomLog::query()->with('room', 'user')->orderBy('id', 'desc');
        $data['rooms']      = Rooms::query()->get();
        $data['paginate']   = 1;

        if(request('export_type')){

            $data['room_logs']  = $data['room_logs']->get();
            $data['paginate']   = 0;
            return $this->service->exportData($data, 'hotel/reports/room-logs/export/', 'Room Log');
        }

        $data['room_logs']  = $data['room_logs']->paginate(50);
        return view('hotel/reports/room-logs/index', $data);
    }





    /*
     |--------------------------------------------------------------------------
     | SERVICE REPORT
     |--------------------------------------------------------------------------
    */
    public function service(Request $request)
    {

        $data['services']   = HotelTransection::query()->with('created_user')->where('service_charge', '>', 0)->latest()->dateFilter();
        $data['paginate']   = 1;

        if(request('export_type')){

            $data['services']  = $data['services']->get();
            $data['paginate']   = 0;
            return $this->service->exportData($data, 'hotel/reports/services/export/', 'Service Report');
        }

        $data['services']  = $data['services']->paginate(50);
        return view('hotel/reports/services/index', $data);
    }





    /*
     |--------------------------------------------------------------------------
     | TODAY ACTIVITY METHOD
     |--------------------------------------------------------------------------
    */
    public function todayActivity(Request $request)
    {
        // ->when(setting('report_with_night_audit') == 1, function ($query) {
        //     $query->whereHas('night_audits',function($q){
        //         $q->with('NightAudit', function($que){
        //             // $que->SearchDateTo('date',request('from_date'), request('to_date') );
        //             // $que->whereBetween('date', [request('from_date'), request('to_date')]);
        //             $que->DateFilter();
        //         });
        //     });
        // })

        $booking                        = $this->booking = Booking::query()
                                                                    ->when(setting('report_with_night_audit') == 1, function ($query) {
                                                                        return $query->doesntHave('nightClosing');
                                                                    })
                                                                    ->where('booking_date', $request->date)->first();

        $this->night_closing_date       = optional($booking)->booking_date;

        $this->booking_count            = count(collect($booking)->toArray());

        $data['date']                   = $date = $request->date;
        $data['booking_count']          = $this->booking_count;
        $data['transactions']           = HotelTransactionLedger::where('date', 'LIKE', $request->date .'%')->whereIn('source_type', ['Booking', 'Booking Adjust'])->with('transaction.source', 'account')->get();

        $data['total_reservation']      = Booking::with('bookingDates')->whereHas('bookingDates', fn($q) => $q->where('date', $request->date))->where('status', 0)->count();
        $data['total_check_in']         = Booking::where('check_in_date', $date)->count();
        $data['total_check_out']        = Booking::whereDate('check_out_date', $date)->count();
        $data['total_cancel']           = Booking::cancel()->whereDate('updated_at', $date)->count();
        $data['total_room']             = Rooms::query()->count();
        $data['total_booked_room']      = Rooms::query()->whereHas('booking_details', fn($q) => $q->whereHas('bookingInfo', fn($q) => $q->where('booking_date', $date)))->count();
        $data['total_dirty_room']       = Rooms::query()->where('status', 0)->count();
        $data['total_maintenance_room'] = Rooms::query()->where('status', 2)->count();

        $data['paginate']   = 1;

        // if(request('export_type')){

        //     $data['services']  = $data['services']->get();
        //     $data['paginate']   = 0;
        //     return $this->service->exportData($data, 'hotel/reports/today-activities/export/', 'Service Report');
        // }

        // $data['services']  = $data['services']->paginate(50);

        return view('hotel/reports/today-activities/index', $data);
    }




    /*
     |--------------------------------------------------------------------------
     | DailyCheckIn REPORTS METHOD
     |--------------------------------------------------------------------------
    */
    public function DailyCheckIn(Request $request) {
         $date   = date('y-m-d');
         $today  = date('Y-m-d');

         $data['booking']             = $booking =  $this->booking = Booking::with('customer','bookingDetails')
                                        ->doesntHave('nightClosing')
                                        ->where('check_in_date', $date)
                                        ->first();

        $data['today_booking']             =  Booking::with('customer','bookingDetails','transection')
                                        ->doesntHave('nightClosing')
                                        ->where('check_in_date', $date)
                                        // ->where('check_out_time',  null)
                                        ->get();

        $this->night_closing_date       = optional($booking)->booking_date;

        $this->booking_count            = count(collect($booking)->toArray());

        $data['date']                   =  $date;


        // if(request('export_type')){

        //     return $this->service->exportData($data, 'hotel/reports/today-check-in/export/', 'Today Check In Report');
        // }


        return view('hotel/reports/today-check-in/index', $data);
    }



    /*
     |--------------------------------------------------------------------------
     | DailyCheckOut REPORTS METHOD
     |--------------------------------------------------------------------------
    */
    public function DailyCheckOut() {

            $date   = date('y-m-d');
            $today  = date('Y-m-d');

            $data['today_booking']          =  $booking =  $this->booking = Booking::with('customer','bookingDetails','transection')
                                            ->doesntHave('nightClosing')
                                            // ->where('check_out_date', $date)
                                            // ->where('check_out_time', today())
                                            ->whereDate('check_out_time',$today)
                                            ->get();

            $this->booking_count            = count(collect($booking)->toArray());

            $data['date']                   =  $date;

            if(request('export_type')){

                return $this->service->exportData($data, 'hotel/reports/today-check-out/export/', 'Today Check Out Report');
            }

            return view('hotel/reports/today-check-out/index', $data);

    }


    /*
     |--------------------------------------------------------------------------
     | OVER ALL REPORTS METHOD
     |--------------------------------------------------------------------------
    */
    public function ReportOverAll(Request $request)
    {

        $data['cashFlows'] = [];

        if (collect(request()->all())->count() > 0) {
         $data['transactions'] = HotelTransection::with('booking:id,payment_id','booking.paymentType:id,name','created_user','transaction_ledgers','night_audits')
                                                     ->when(setting('report_with_night_audit') == 1, function ($query) {
                                                        $query->whereHas('night_audits',function($q){
                                                            $q->with('NightAudit', function($que){
                                                                // $que->SearchDateTo('date',request('from_date'), request('to_date') );
                                                                // $que->whereBetween('date', [request('from_date'), request('to_date')]);
                                                                $que->DateFilter();
                                                            });
                                                        });
                                                    })
                                                    ->when(setting('report_with_night_audit') == 0, function ($query) {
                                                        $query->dateFilter();
                                                    })
                                                    ->latest()
                                                    ->paginate(25);

        $data['account_types']  = AccountType::whereHas('hotelTransactions')->pluck('name', 'id');

        }
        if (request('export_type')) {
            return (new ExportService())->exportData($data, 'hotel/reports/all-reports/export/', 'Over All Reports');
        }


        return view('hotel/reports/all-reports/index', $data);
    }




    //--------------------------------------------------------------------------//
    //                            ARRIVAL LIST METHOD                           //
    //--------------------------------------------------------------------------//
    public function expectedArrival(Request $request)
    {
        $booking                   = $this->booking = Booking::with('guestInfo')
                                                                ->where('booking_date', $request->date)
                                                                ->where('status', 0)
                                                                ->orWhere('status', 2)
                                                                ->paginate(25);

        $this->booking_count       = count(collect($booking)->toArray());

        $data['date']              = $date = $request->date;
        $data['booking_count']     = $this->booking_count;
        $data['paginate']          = 1;
        $data['bookings']          = $booking;


        if (request('export_type')) {
            return (new ExportService())->exportData($data, 'hotel/reports/expected-arrival/export/', 'Expected Arrival List');
        }

        return view('hotel/reports/expected-arrival/index', $data);

    }





    //--------------------------------------------------------------------------//
    //                           DEPARTURE LIST METHOD                          //
    //--------------------------------------------------------------------------//
    public function expectedDeparture(Request $request)
    {
        $booking                   = $this->booking = Booking::with('guestInfo')->where('check_out_date', $request->date)->where('status', 1)->paginate(25);

        $this->booking_count       = count(collect($booking)->toArray());

        $data['date']              = $date = $request->date;
        $data['booking_count']     = $this->booking_count;
        $data['paginate']          = 1;
        $data['bookings']          = $booking;

        if (request('export_type')) {
            return (new ExportService())->exportData($data, 'hotel/reports/expected-departure/export/', 'Expected Departure List');
        }

        return view('hotel/reports/expected-departure/index', $data);
    }




    //--------------------------------------------------------------------------//
    //                        IN HOUSE GUEST LIST METHOD                        //
    //--------------------------------------------------------------------------//
    public function inHouseGuest(Request $request)
    {

        $booking                   = Booking::when( request()->filled('to'), function($qr) {
                                                    // $qr->where('check_in_date', '<=', (request('to')));
                                                    $qr->where('booking_date', '=', (request('to')));
                                                })
                                                // ->when(request()->filled('to'), function($qr) {
                                                //     $qr->where('check_out_date', '>=', (request('to')));
                                                // })
                                                ->with('guestInfo')
                                                ->with('bookingDetails')
                                                ->where('status', 1)
                                                ->Paginate(25);


        $this->booking_count       = count(collect($booking)->toArray());

        $data['date']              = $date = $request->date;
        $data['booking_count']     = $this->booking_count;
        $data['paginate']          = 1;
        $data['bookings']          = $booking;
        $data['roomCategories']    = RoomCategory::where('status', 1)->get();
        $data['company']           = Company::first();

        if (request('export_type')) {
            return (new ExportService())->exportData($data, 'hotel/reports/in-house-guest/export/', 'In House Guest List');
        }

        return view('hotel/reports/in-house-guest/index', $data);
    }



    //--------------------------------------------------------------------------//
    //                      TODAY IN HOUSE REPORT METHOD                        //
    //--------------------------------------------------------------------------//
    public function TodayHouse(Request $request)
    {
        $today_date = date('y-m-d');

        $booking                   =    Booking::with('guestInfo','bookingDetails')
                                                    // ->where('check_out_date','<=', $today_date)
                                                    ->where('check_out_date', '>=', $today_date)
                                                    ->where('status', 1)
                                                    ->Paginate(25);


        $this->booking_count       = count(collect($booking)->toArray());

        $data['date']              = $today_date;
        $data['booking_count']     = $this->booking_count;
        $data['paginate']          = 1;
        $data['bookings']          = $booking;
        $data['roomCategories']    = RoomCategory::where('status', 1)->get();
        $data['company']           = Company::first();

        if (request('export_type')) {
            return (new ExportService())->exportData($data, 'hotel/reports/today-in-house/export/', 'Today In House Guest List');
        }

        return view('hotel/reports/today-in-house/index', $data);

    }



    /*
     |--------------------------------------------------------------------------
     | Daily Vat Report REPORT
     |--------------------------------------------------------------------------
    */
    public function vatDaily(Request $request)
    {

        $data['daily_vats']   = HotelTransection::query()->where('vat_amount', '>', 0)->latest()->dateFilter();
        $data['paginate']   = 1;

        if(request('export_type')){

            $data['daily_vats']  = $data['daily_vats']->get();
            $data['paginate']   = 0;
            return $this->service->exportData($data, 'hotel/reports/vat-report-day/export/', 'Daily Vat Report');
        }

        $data['daily_vats']  = $data['daily_vats']->paginate(25);
        return view('hotel/reports/vat-report-day/index', $data);
    }

    /*
     |--------------------------------------------------------------------------
     | Daily Vat Report REPORT
     |--------------------------------------------------------------------------
    */
    public function vatMonthly(Request $request)
    {

        $data['monthly_vats'] = HotelTransection::query()
                        ->where('vat_amount', '>', 0)
                        ->latest()
                        ->dateFilter()
                        ->selectRaw('MONTHNAME(created_at) as month, source_type, COUNT(*) as count, SUM(vat_amount) as vat_amount, SUM(total_amount) as total_amount, date')
                        ->groupBy('month', 'source_type');

        $data['paginate']   = 1;

        if(request('export_type')){

            $data['monthly_vats']  = $data['monthly_vats']->get();
            $data['paginate']   = 0;
            return $this->service->exportData($data, 'hotel/reports/vat-report-monthly/export/', 'Monthly Vat Report');
        }

        $data['monthly_vats']  = $data['monthly_vats']->paginate(50);

        return view('hotel/reports/vat-report-monthly/index', $data);
    }



}
