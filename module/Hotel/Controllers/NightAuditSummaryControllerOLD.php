<?php

namespace Module\Hotel\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Module\Hotel\Models\Rooms;
use App\Services\ExportService;
use Module\Hotel\Models\Booking;
use Module\Account\Models\Account;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Module\Hotel\Models\AccountType;
use Module\Account\Models\Transaction;
use Module\Account\Models\AccountGroup;
use Module\Hotel\Models\HotelTransection;
use Module\Hotel\Models\NightAuditSummary;
use Module\Restaurant\Models\ProductLedger;
use Module\Hotel\Models\BookingDateDetails;
use Module\Hotel\Services\RoomStatusService;
use Module\Hotel\Models\HotelTransactionLedger;
use Module\Account\Services\AccountTransactionService;
use Module\Hotel\Models\NightAuditDetail;
use Module\Hotel\Models\NightAuditTransaction;

class NightAuditSummaryController extends Controller
{

    private $night_closing_date;
    private $booking_count;
    private $booking;
    private $transactionService;



    /*
     |--------------------------------------------------------------------------
     | CONSTRUCTOR
     |--------------------------------------------------------------------------
    */
    public function __construct()
    {
        $this->transactionService   = new AccountTransactionService();

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
                                                $que->where('source_type', '!=','Restaurant Sale')->with('account')->with('source');
                                            })
                                            ->withSum('transaction as total_due', 'due_amount')
                                            ->withSum('transaction as total_collection', 'collection')
                                            ->with('transaction','transaction.source','transaction.account');
                                        })
                                        ->where('is_rest', '!=', 1)
                                        ->dateFilter();

        $data['nightaudits']    = request()->filled('export_type') ? $nightaudits->get() : $nightaudits->paginate(25);

        $data['paginate']       = 1;

        if(request('export_type')){

            $data['paginate']     = 0;
            return (new ExportService())->exportData($data, 'night-audits/export/', 'Night Closing Report');
        }

        if (request('update') == 1) {
            $this->updateSummary();
        }


        if (request()->filled('datetime')) {
            $this->updateHotelTransaction();
        }
        return view('night-audits/index', $data);
    }













    /*
     |--------------------------------------------------------------------------
     | CREATE METHOD
     |--------------------------------------------------------------------------
    */
    public function create(Request $request)
    {
        $data['from_date']              = $from_date = $request->from_date;
        $data['to_date']                = $to_date = $request->to_date ?? $request->from_date;

        // dd(fdate($from_date, 'Y-m-d'),fdate($to_date, 'Y-m-d'));
        $data['accountTypes']           = AccountType::pluck('name', 'id');
        $data['transactions']           = HotelTransection::query()
                                        ->when($request->filled('from_date') && $request->filled('to_date'), fn($q) => $q->whereBetween('datetime', [fdate($from_date, 'Y-m-d H:i:s'),fdate($to_date, 'Y-m-d H:i:s')]))
                                        ->doesntHave('nightClosingLedger')
                                        ->with(['transaction_ledgers' => function($q) use($request, $from_date, $to_date){
                                            $q->doesntHave('nightClosingLedger')
                                            ->when($request->filled('from_date') && $request->filled('to_date'), fn($q) => $q->whereBetween('datetime', [fdate($from_date, 'Y-m-d H:i:s'),fdate($to_date, 'Y-m-d H:i:s')])
                                            ->where('date', '>=', fdate($from_date, 'Y-m-d'))->where('date', '<=', fdate($to_date, 'Y-m-d')));
                                        }])

                                        ->whereHas('transaction_ledgers', function($q) use($request, $from_date, $to_date){
                                            $q->DoesntHave('nightClosingLedger')
                                            ->when($request->filled('from_date') && $request->filled('to_date'), fn($q) => $q->whereBetween('datetime', [fdate($from_date, 'Y-m-d H:i:s'),fdate($to_date, 'Y-m-d H:i:s')])
                                            ->where('date', '>=', fdate($from_date, 'Y-m-d'))->where('date', '<=', fdate($to_date, 'Y-m-d')));
                                        })
                                        ->with('source', 'account')->orderBy('source_type')->get()->groupBy('source_type');

    // $data['transaction_ledgers']            = HotelTransactionLedger::query()
    //                                         ->when($request->filled('from_date') && $request->filled('to_date'), fn($q) => $q->whereBetween('datetime', [fdate($from_date, 'Y-m-d H:i:s'),fdate($to_date, 'Y-m-d H:i:s')]))
    //                                         ->doesntHave('nightClosingLedger')
    //                                         ->with(['transaction' => function($q) use($request, $from_date, $to_date){
    //                                             $q->doesntHave('nightClosingLedger')
    //                                             ->when($request->filled('from_date') && $request->filled('to_date'), fn($q) => $q->whereBetween('datetime', [fdate($from_date, 'Y-m-d H:i:s'),fdate($to_date, 'Y-m-d H:i:s')])
    //                                             ->where('date', '>=', fdate($from_date, 'Y-m-d'))->where('date', '<=', fdate($to_date, 'Y-m-d')));
    //                                         }])

    //                                         ->whereHas('transaction', function($q) use($request, $from_date, $to_date){
    //                                             $q->DoesntHave('nightClosingLedger')
    //                                             ->when($request->filled('from_date') && $request->filled('to_date'), fn($q) => $q->whereBetween('datetime', [fdate($from_date, 'Y-m-d H:i:s'),fdate($to_date, 'Y-m-d H:i:s')])
    //                                             ->where('date', '>=', fdate($from_date, 'Y-m-d'))->where('date', '<=', fdate($to_date, 'Y-m-d')));
    //                                         })
    //                                         ->with('source', 'account')->orderBy('source_type')->get()->groupBy('source_type');



        $data['total_reservation']      = BookingDateDetails::whereBetween('date', [$from_date, $to_date])->where('status', 0)->count();
        $data['total_check_in']         = BookingDateDetails::whereBetween('date', [$from_date, $to_date])->where('status', 1)->count();
        $data['total_check_out']        = BookingDateDetails::whereBetween('date', [$from_date, $to_date])->where('status', 3)->count();
        $data['total_cancel']           = Booking::cancel()->where('updated_at', $from_date)->count();
        $data['total_room']             = Rooms::query()->count();
        $data['total_booked_room']      = BookingDateDetails::whereBetween('date', [$from_date, $to_date])->where('status', 2)->count();
        $data['total_dirty_room']       = Rooms::query()->where('status', 0)->count();
        $data['total_maintenance_room'] = Rooms::query()->where('status', 2)->count();

        // return view('night-audits/create-v3', $data);
        return view('night-audits/create-v2', $data);
    }













    /*
     |--------------------------------------------------------------------------
     | STORE METHOD
     |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $data = $request->validate([

            'collection'            => 'required|numeric',
            'due_amount'            => 'required|numeric',
            'total_check_in'        => 'required|numeric',
            'total_check_out'       => 'required|numeric',
            'total_reservation'     => 'required|numeric',
            'total_cancelled'       => 'required|numeric',
            'total_room'            => 'required|numeric',
            'total_dirty_room'      => 'required|numeric',
            'total_booked_room'     => 'required|numeric',
        ]);


        // if (count($request->transaction_ids ?? []) == 0) {
        //     return redirect()->back()->with('error', 'No data found under Booking or Services');
        // }


        try {

            $rooms = Rooms::query()->select('id', 'status')->get();

            $data['date']   = $request->date;

            DB::transaction(function () use ($request, $data, $rooms) {

                // $audit = NightAuditSummary::firstOrCreate([
                //     'date' => $request->date
                // ], $data);

                $audit = NightAuditSummary::create($data);

                foreach ($request->transaction_ids ?? [] as $key => $transaction_id) {

                    $auditDetail = $audit->details()->firstOrCreate([

                        'transaction_id' => $transaction_id,

                    ],[
                        'total_amount'   => $request->total_amounts[$transaction_id],
                        'collection'     => $request->collections[$transaction_id],
                        'due'            => $request->due_amounts[$transaction_id],
                    ]);

                    $this->nightAuditTransactionStore($audit->id);


                    $this->accountTransactionIfEnableWhenNightAudit($transaction_id, $audit);


                    $this->setNightAuditIdProductLedger($transaction_id, $audit);
                }

                foreach ($rooms as $key => $room) {
                    try {
                        $get_status = BookingDateDetails::where('room_id', $room->id)->where('date', $request->date)->first()->status;

                        if ($get_status == 0) {
                            $status = 'Reserved';
                        }
                        elseif($get_status == 1){
                            $status  = 'Check In';
                        }
                        elseif($get_status == 2){
                            $status  = 'Booked';
                        }
                        elseif($get_status == 3){
                            $status  = 'Check Out';
                        }
                        elseif($get_status == 3){
                            $status  = 'Cancelled';
                        }

                    } catch (\Throwable $th) {
                        $get_status = $room->status;

                        if ($get_status == 2) {

                            $status = 'Maintainance';

                        }else if($get_status == 1){

                            $status = 'Ready';
                        }
                        else{

                            $status = 'Dirty';
                        }
                    }


                    $audit->room_details()->firstOrCreate([
                        'room_id'   => $room->id,
                        'status'    => $status
                    ]);
                }

            });

        } catch (\Throwable $th) {
            throw $th;
            return redirect()->back()->with('error', $th->getMessage());
        }

        return redirect()->route('night-audits.index')->with('message', 'Night Audit have been Successfully Generate for ' . $request->date);
    }













    /*
     |--------------------------------------------------------------------------
     | SHOW METHOD
     |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        // details.transaction.transaction_ledgers
        $data['audits']     = $night_audit =  NightAuditSummary::with('auditTransactions.transaction_ledger', 'details.transaction.account', 'details.transaction.source', 'room_details.room:id,room_number')
                                                                    ->withCount(['details as bookingCount' => function($q){
                                                                        $q->whereHas('transaction' , function($q){
                                                                            $q->where('source_type', 'Booking');
                                                                        });
                                                                    }])
                                                                    ->with(['details.transaction' => function ($qr)  {
                                                                        $qr->withSum('transaction_ledgers', 'in');
                                                                    }])
                                                                    ->withCount(['details as restourantCount' => function($q){
                                                                        $q->whereHas('transaction' , function($q){
                                                                            $q->where('source_type', 'Restaurant Sale');
                                                                        });
                                                                    }])
                                                                    ->withCount(['details as barSaleCount' => function($q){
                                                                        $q->whereHas('transaction' , function($q){
                                                                            $q->where('source_type', 'Bar Sale');
                                                                        });
                                                                    }])
                                                                    ->withCount(['details as hotelServiceCount' => function($q){
                                                                        $q->whereHas('transaction' , function($q){
                                                                            $q->where('source_type', 'Hotel Service Sale');
                                                                        });
                                                                    }])
                                                                    ->where('date', $id)
                                                                    ->get();


        $check_in               = $night_audit->first()->date;
        $check_out              = fdate($night_audit->last()->date, 'Y-m-d');

        $data['categories']     = (new RoomStatusService())->availableRoom($check_in, $check_out);
        $data['company']        = Company::first();

        $data['account_types']  = AccountType::whereHas('hotelTransactions')->pluck('name', 'id');


        return view('night-audits/invoice', $data);
        // return view('night-audits/index-details', $data);


        $audit = NightAuditSummary::with('details.transaction.transaction', 'details.transaction.account', 'details.transaction.source.bookingDetails.roomNumber')->find($id);

        return view('night-audits/show', compact('audit'));
    }











    /*
     |--------------------------------------------------------------------------
     | DELETE/DESTORY METHOD
     |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        try {

            DB::transaction(function() use($id){

                $night_audit = NightAuditSummary::where('date', $id)->first();


                ProductLedger::whereNotNull('audit_date')->where('audit_date', $night_audit->date)->update([
                    'audit_date'    => null
                ]);

                Transaction::where('description', 'Night Audit-'. $night_audit->id)->delete();

                foreach(NightAuditSummary::where('date', $id)->get() as $audit){
                    $night_audit->auditTransactions()->delete();
                    $night_audit->delete();
                }
            });

        } catch (\Throwable $e) {
            throw $e;

            return redirect()->back()->with('error', 'You cannot delete this Information!');
        }
        return redirect()->back()->with('message', 'Night Audit deleted Successfull!');
    }




    public function getStatus($code)
    {
        if ($code == 0) {
            return 'reserved';
        } else if($code == 1) {
            return 'check in';
        } else if($code == 3) {
            return 'check out';
        } else if($code == 4) {
            return 'cancelled';
        }

    }






    public function updateHotelTransaction()
    {
        HotelTransection::query()->get()->map(function($item){
            $item->update([
                'datetime'  => fdate(fdate($item->date, 'Y-m-d') . fdate($item->created_at, 'H:i:s'), 'Y-m-d H:i:s'),
            ]);
        });
    }



    /**
     * -----------------------------------------------------------------------
     * ACCOUNT TRANSACTION IF ENABLE FOR NIGHT AUDIT
     * -----------------------------------------------------------------------
     */
    private function accountTransactionIfEnableWhenNightAudit($transaction_id, $audit)
    {
        if (!setting('account_transaction_when_night_audit')) {
            return;
        }

        try {
            $transaction        = HotelTransection::where('id', $transaction_id)->with('transaction_ledgers', 'source')->first();

            $total_amount       = $transaction->total_amount;
            $collection         = $transaction->collection;

            $model              = optional($transaction->source);
            // dd($transaction);

            $date               = $audit->date;

            $description        = 'Night Audit-'. $audit->id;
            $sale_account       = $this->transactionService->getSaleAccount();
            $balance_type       = optional(AccountGroup::find(1))->balance_type;

            $module_account     = Account::where('name', $transaction->source_type)
                                        ->where('account_group_id', 1)
                                        ->where('account_control_id', 1)
                                        ->where('account_subsidiary_id', 8)
                                        ->where('balance_type', $balance_type)
                                        ->first();


            // ACC ACCOUNT SALE TRANSACTION
            $this->transactionService->storeTransaction(auth()->user()->company_id, $model, $model->invoice_no ?? $model->booking_number, $sale_account, 0, convertToBDTCurrency($total_amount), $date ?? date('Y-m-d'), 'credit', 'Sale', $description);


            // MODULE TRANSACTION / CUSTOMER DUE TRANSACTION IF HAVE
            $this->transactionService->storeTransaction(auth()->user()->company_id, $model, $model->invoice_no ?? $model->booking_number, $module_account, convertToBDTCurrency($total_amount), $collection, $date ?? date('Y-m-d'), 'debit', 'Customer Due', $description);


            // MULTIPLE PAYMENT ACCOUNT IF HAVE MULTIPLE TRANSACTION LEDGER
            foreach ($transaction->transaction_ledgers as $ledger) {
                $cashAccount        = getPaymentTypeAccount($ledger->payment_type);

                // MULTIPLE PAYMENT METHOD TRANSACTION IF SELECT MULTIPLE TYPE
                $this->transactionService->storeTransaction(auth()->user()->company_id, $model, $transaction->invoice_no, $cashAccount, $ledger->in, 0, $date ?? date('Y-m-d'), 'debit', 'Payment', $description);
            }
        } catch (\Throwable $th) {
            //throw $th;
        }

    }




    private function setNightAuditIdProductLedger($transaction_id, $audit)
    {
        $transaction = HotelTransection::where('id', $transaction_id)->with('source.details')->first();

        if ($transaction->source_type != 'Booking') {

            ProductLedger::where('sourceable_type', $transaction->source_type)
                            ->where('sourceable_id', $transaction->source_id)
                            ->update([
                                'audit_date'        => $audit->date
                            ]);
        }

    }




    // UPDATE NIGHT AUDIT SUMMARY CALCULATION
    public function updateSummary()
    {
        $audits = NightAuditSummary::where('date', request('date'))->with('details.transactions')->get();

        foreach ($audits as $key => $audit) {

            $total_amount = 0;
            $total_due = 0;
            foreach($audit->details as $detail){
                $total_amount   += $detail->transactions->sum('total_amount');
                $total_due      += $detail->transactions->sum('due_amount');
            }
            $audit->update([
                'collection'    => $total_amount,
                'due_amount'    => $total_due
            ]);
        }
        return;

    }


    /**
     * ----------------------------------------------------------------
     * NIGHT AUDIT TRANSACTION STORE WHEN CREATE
     * ----------------------------------------------------------------
     */
    public function nightAuditTransactionStore($detailId)
    {

        foreach(request('transaction_ledger_ids') as $key => $ledger_id){

            NightAuditTransaction::firstOrCreate([
                'transaction_ledger_id'     => $ledger_id,
            ],[
                'transaction_id'            => request('transaction_ids')[$key],
                'audit_id'                  => $detailId,
            ]);
        };
    }



    //SET NIGHT AUDIT TRANSACTION DATA IF NOT EXITS INTO TABLE
    public function nightAuditTransaction($detailId = null)
    {
        NightAuditDetail::query()->with('transaction.transaction_ledgers')
                                 ->when($detailId, fn($q) => $q->where('id', $detailId))
                                 ->when(request()->filled('audit_id'), fn($q) => $q->where('audit_id', request('audit_id')))
                                 ->get()
                                 ->map(function($detail){

                                    foreach ($detail->transaction->transaction_ledgers->pluck('id') as $key => $ledger_id) {
                                        NightAuditTransaction::firstOrCreate([
                                            'transaction_ledger_id'     => $ledger_id,
                                        ],[
                                            'transaction_id'            => $detail->transaction_id,
                                            'audit_id'                  => $detail->audit_id,
                                        ]);
                                    };

                                 });

    }




    public function nightAuditTransactionsDelete()
    {
        $ids = explode(',', request('ids'));
        NightAuditTransaction::whereIn('transaction_ledger_id', $ids)->delete();

        return redirect()->back();
    }

}
