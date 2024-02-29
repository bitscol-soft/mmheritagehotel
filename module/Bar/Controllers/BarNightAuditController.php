<?php

namespace Module\Bar\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Module\Hotel\Models\Rooms;
use App\Services\ExportService;
use Module\Hotel\Models\Booking;
use Illuminate\Support\Facades\DB;
use Module\Account\Models\Account;
use App\Http\Controllers\Controller;
use Module\Bar\Models\ProductLedger;
use Module\Hotel\Models\AccountType;
use Module\Account\Models\AccountGroup;
use Module\Hotel\Models\BookingDetails;
use Module\Hotel\Models\HotelTransection;
use Module\Hotel\Models\NightAuditSummary;
use Module\Hotel\Models\BookingDateDetails;
use Module\Hotel\Services\RoomStatusService;
use Module\Hotel\Models\NightAuditTransaction;

class BarNightAuditController extends Controller
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

    }





    /*
     |--------------------------------------------------------------------------
     | INDEX METHOD
     |--------------------------------------------------------------------------
    */
    public function index()
    {

        $nightaudits = NightAuditSummary::latest('date')
                                            ->with(['details' => function($query){
                                                $query->whereHas('transaction', function($query){
                                                    $query->where('source_type', 'Bar Sale');
                                                })
                                                ->with('transaction.account', 'transaction.source')
                                                ->with('transaction.transaction_ledgers')
                                                ->withSum('transaction as total_due', 'due_amount')
                                                ->withSum('transaction as total_collection', 'collection')
                                                ->with('transaction','transaction.source','transaction.account');
                                            }])
                                            ->dateFilter();

        // $nightaudits = NightAuditSummary::latest('date')->dateFilter();

        $data['nightaudits'] = request()->filled('export_type') ? $nightaudits->get() : $nightaudits->paginate(25);
        $data['account_types'] = AccountType::pluck('name', 'id');
        $data['paginate']     = 1;

        if(request('export_type')){
            $data['paginate']     = 0;
            return (new ExportService())->exportData($data, 'bar-night-audits/export/', 'Night Closing Report');
        }


        if (request()->filled('datetime')) {
            $this->updateHotelTransaction();
        }

        // return $data;
        return view('bar-night-audits/index', $data);
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
                                            $q
                                            ->doesntHave('nightClosingLedger')
                                            ->when($request->filled('from_date') && $request->filled('to_date'), fn($q) => $q->whereBetween('datetime', [fdate($from_date, 'Y-m-d H:i:s'),fdate($to_date, 'Y-m-d H:i:s')])
                                            ->where('date', '>=', fdate($from_date, 'Y-m-d'))->where('date', '<=', fdate($to_date, 'Y-m-d')));
                                        }])

                                        ->whereHas('transaction_ledgers', function($q) use($request, $from_date, $to_date){
                                            $q
                                            ->DoesntHave('nightClosingLedger')
                                            ->when($request->filled('from_date') && $request->filled('to_date'), fn($q) => $q->whereBetween('datetime', [fdate($from_date, 'Y-m-d H:i:s'),fdate($to_date, 'Y-m-d H:i:s')])
                                            ->where('date', '>=', fdate($from_date, 'Y-m-d'))->where('date', '<=', fdate($to_date, 'Y-m-d')));
                                        })
                                        ->with('source', 'account')
                                        ->where('source_type', 'Bar Sale')
                                        ->orderBy('source_type')->get()->groupBy('source_type');



        $data['total_reservation']      = BookingDateDetails::whereBetween('date', [$from_date, $to_date])->where('status', 0)->count();
        $data['total_check_in']         = BookingDateDetails::whereBetween('date', [$from_date, $to_date])->where('status', 1)->count();
        $data['total_check_out']        = BookingDateDetails::whereBetween('date', [$from_date, $to_date])->where('status', 3)->count();
        $data['total_cancel']           = Booking::cancel()->where('updated_at', $from_date)->count();
        $data['total_room']             = Rooms::query()->count();
        $data['total_booked_room']      = BookingDateDetails::whereBetween('date', [$from_date, $to_date])->where('status', 2)->count();
        $data['total_dirty_room']       = Rooms::query()->where('status', 0)->count();
        $data['total_maintenance_room'] = Rooms::query()->where('status', 2)->count();

        return view('bar-night-audits/create-v2', $data);
    }









    /*
     |--------------------------------------------------------------------------
     | STORE METHOD
     |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        // return $request->all();
        $request->validate([

            'collection'            => 'required|numeric',
            'due_amount'            => 'required|numeric',
        ]);


        // if (count($request->transaction_ids ?? []) == 0) {
        //     return redirect()->back()->with('error', 'No data found under Booking or Services');
        // }

        $data['date']               = $request->date;
        $data['collection']         = $request->collection;
        $data['due_amount']         = $request->due_amount;
        $data['total_check_in']     = $request->total_check_in;
        $data['total_room']         = $request->total_room;
        $data['total_check_out']    = $request->total_check_out;
        $data['total_reservation']  = $request->total_reservation;
        $data['total_cancelled']    = $request->total_cancelled;
        $data['total_dirty_room']   = $request->total_dirty_room;
        $data['total_booked_room']  = $request->total_booked_room;
        // $data['is_rest']            = 1;
        try {


            DB::transaction(function () use ($request, $data) {

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

            });

        } catch (\Throwable $th) {
            // throw $th;
            return redirect()->back()->with('error', $th->getMessage());
        }

        return redirect()->route('rst.night-audits.index')->with('message', 'Night Audit have been Successfully Generate for ' . $request->date);
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
                                                                            $q->where('source_type', 'Bar Sale');
                                                                        });
                                                                    }])
                                                                    ->with('details',function($query){
                                                                        $query->whereHas('transaction',function($que){
                                                                            $que->with('account')->with('source')->where('source_type', 'Bar Sale');
                                                                        })->with('transaction','transaction.source','transaction.account');
                                                                    })
                                                                    ->where('date', $id)
                                                                    ->get();


        $check_in           = $night_audit->first()->date;
        $check_out          = fdate($night_audit->last()->date, 'Y-m-d');

        $data['categories'] = (new RoomStatusService())->availableRoom($check_in, $check_out);
        $data['company']    = Company::first();
        $data['account_types']  = AccountType::whereHas('hotelTransactions')->pluck('name', 'id');

        return view('bar-night-audits/invoice', $data);

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

     /**
     * -----------------------------------------------------------------------
     * Product Ledger NIGHT AUDIT
     * -----------------------------------------------------------------------
     */
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



}
