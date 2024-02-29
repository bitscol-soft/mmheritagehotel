<?php

namespace Module\Hotel\Controllers\Report;

use Illuminate\Http\Request;
use App\Services\ExportService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Module\Hotel\Models\HotelTransection;
use Module\Hotel\Models\HotelTransactionLedger;

class CashFlowController extends Controller
{





    /*
     |--------------------------------------------------------------------------
     | INDEX METHOD
     |--------------------------------------------------------------------------
    */
    public function index(Request $request){


        $data['cashFlows'] = [];

        if (collect(request()->all())->count() > 0) {

            $from_time  = request('from_time') ? \Carbon\Carbon::parse(request('from_time'))->format('H:i A') : null;
            $to_time    = request('to_time') ? \Carbon\Carbon::parse(request('to_time'))->format('H:i A') : null;

            $data['cashFlows'] = HotelTransection::whereHas('booking')->where('source_type', 'Booking')->whereHas('transaction_ledgers') // Add whereHas for transaction_ledgers
                                                    ->with('booking:id,payment_id', 'booking.paymentType:id,name', 'created_user')
                                                    ->when($from_time, function ($query) use ($from_time, $to_time) {
                                                        $query->whereHas('transaction_ledgers', function ($qr) use ($from_time, $to_time) {
                                                            $qr->whereBetween(DB::raw("DATE_FORMAT(datetime, '%H:%i %A')"), [$from_time, $to_time]);
                                                        });
                                                    })
                                                    ->dateFilter()
                                                    ->latest()
                                                    ->paginate(25);



            // $data['cashFlows'] = HotelTransactionLedger::where('source_type', 'Booking')
            //                                             ->with('transaction', 'account')
            //                                             ->when(setting('report_with_night_audit') == 1, function ($query) {
            //                                                 return $query->whereHas('nightClosingLedger');
            //                                             })
            //                                             ->when($from_time, function ($query) use ($from_time, $to_time) {
            //                                                 $query->when(request()->filled('from_time') || request()->filled('to_time'), function ($qr) use ($from_time, $to_time) {
            //                                                     $qr->whereBetween(DB::raw("DATE_FORMAT(datetime, '%H:%i %A')"), [$from_time, $to_time]);
            //                                                 });
            //                                             })
            //                                             ->latest()
            //                                             ->dateFilter()
            //                                             ->paginate(25);

        }
        if (request('export_type')) {
            return (new ExportService())->exportData($data, 'hotel/reports/cash-flow/export/', 'Cash Flow');
        }

        return view('hotel/reports/cash-flow/index', $data);
    }
}
