<?php

namespace Module\HotelService\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Module\Hotel\Models\Rooms;
use Module\Hotel\Models\Booking;
use Illuminate\Support\Facades\DB;
use App\Services\ExportService;
use App\Http\Controllers\Controller;
use Module\Hotel\Models\AccountType;
use Module\Hotel\Models\NightAuditSummary;
use Module\Hotel\Models\BookingDateDetails;
use Module\Hotel\Models\BookingDetails;
use Module\Hotel\Models\HotelTransection;
use Module\Hotel\Services\RoomStatusService;

class HotelServiceNightAuditController extends Controller
{



    private $night_closing_date;
    private $booking_count;
    private $booking;


    /*
     |--------------------------------------------------------------------------
     | CONSTRUCTOR
     |--------------------------------------------------------------------------
    */
    public function __construct()
    {

    }





    /*
     |--------------------------------------------------------------------------
     | INDEX METHOD
     |--------------------------------------------------------------------------
    */
    public function index()
    {

        $nightaudits = NightAuditSummary::latest('date')
                                        ->with('details',function($query){
                                            $query->whereHas('transaction',function($que){
                                                $que->with('account')->with('source')->where('source_type', 'Hotel Service Sale');
                                            })
                                            ->withSum('transaction as total_due', 'due_amount')
                                            ->withSum('transaction as total_collection', 'collection')
                                            ->with('transaction','transaction.source','transaction.account');
                                        })
                                        ->dateFilter();

        // $nightaudits = NightAuditSummary::latest('date')->dateFilter();

        $data['nightaudits'] = request()->filled('export_type') ? $nightaudits->get() : $nightaudits->paginate(25);
        $data['account_types'] = AccountType::pluck('name', 'id');
        $data['paginate']     = 1;

        if(request('export_type')){
            $data['paginate']     = 0;
            return (new ExportService())->exportData($data, 'night-audits/export/', 'Night Closing Report');
        }


        if (request()->filled('datetime')) {
            $this->updateHotelTransaction();
        }

        return view('hotel-service-night-audits/index', $data);
    }






    /*
     |--------------------------------------------------------------------------
     | SHOW METHOD
     |--------------------------------------------------------------------------
    */
    public function show($id)
    {

        $data['audits']     = $night_audit =  NightAuditSummary::with('room_details.room:id,room_number')
                                                                    ->withCount(['details as restourantCount' => function($q){
                                                                        $q->whereHas('transaction' , function($q){
                                                                            $q->where('source_type', 'Hotel Service Sale');
                                                                        });
                                                                    }])
                                                                    ->with('details',function($query){
                                                                        $query->whereHas('transaction',function($que){
                                                                            $que->with('account')
                                                                                ->with('source')
                                                                                ->where('source_type', 'Hotel Service Sale');
                                                                        })->with('transaction','transaction.source','transaction.account');
                                                                    })
                                                                    ->where('date', $id)
                                                                    ->get();


        $check_in           = $night_audit->first()->date;
        $check_out          = fdate($night_audit->last()->date, 'Y-m-d');

        $data['categories'] = (new RoomStatusService())->availableRoom($check_in, $check_out);
        $data['company']    = Company::first();

        $data['account_types']  = AccountType::whereHas('hotelTransactions')->pluck('name', 'id');

        return view('hotel-service-night-audits/invoice', $data);

    }





}
