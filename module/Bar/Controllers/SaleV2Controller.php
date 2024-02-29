<?php

namespace Module\Bar\Controllers;

use Carbon\Carbon;
use Module\Bar\Models\Sale;
use Illuminate\Http\Request;
use Module\Hotel\Models\Vat;
use Module\Hotel\Models\Guest;
use Module\Hotel\Models\Rooms;
use Module\Bar\Models\SaleItem;
use Illuminate\Support\Facades\DB;
use Module\Bar\Request\SaleRequest;
use App\Http\Controllers\Controller;
use Module\Bar\Services\SaleService;
use Module\Hotel\Models\AccountType;
use Module\Bar\Models\RstTableManage;
use Module\Hotel\Models\HotelTransection;
use Module\Hotel\Services\RoomStatusService;
use Module\Bar\Services\BarTransectionService;
use Module\HotelService\Services\HotelTransactionService;

class SaleV2Controller extends Controller
{
    private $service;


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
        $this->hasAccess("bar.sales.index");

        $data = [
            'sales' => Sale::query()->searchByField('invoice_no')
                ->latest()
                ->searchByField('invoice_no')
                ->searchByField('date')
                ->paginate(25)
        ];

        return view('bar.sales-v2.index', $data);
    }













    /*
     |--------------------------------------------------------------------------
     | CREATE METHOD
     |--------------------------------------------------------------------------
    */
    public function create()
    {

        $this->hasAccess("bar.sales.create");

        $invoice                = (new BarTransectionService())->getInvoiceNo('Bar Sale');
        $data['invoice_id']     = $invoice;
        $data['vat_percent']    = Vat::first()->bar_vat;
        $data['account_types']  = AccountType::pluck('name', 'id');
        $data['guests']         = Guest::paginate(2);
        $data['tables']         = RstTableManage::query()->get();


        // Booking Checked
        $data['mix_date']           = fdate(today_from_system(),'m/d/Y') . ' - ' . Carbon::parse(today_from_system())->addDay()->format('m/d/Y');
        $check_in                   = date('Y-m-d');
        $check_out                  = date('Y-m-d', strtotime($check_in . "+1 days"));
        $data['mix_date']           = $check_in . ' - ' . $check_out;
        $data['booking_date']       = date('m/d/Y') . ' - ' . Carbon::now()->addDay()->format('m/d/Y');

        $data['room_numbers']   = Rooms::pluck('room_number', 'id');



        return view('bar/sales-v2/create', $data);

    }













    /*
     |--------------------------------------------------------------------------
     | STORE METHOD
     |--------------------------------------------------------------------------
    */
    public function store(SaleRequest $request)
    {
        try {

            $sale =  $request->store();

            return response()->json([
                'status'    => 1,
                'message'   => 'Success',
                'data'      => $sale,
                'invoice_no'=>(new BarTransectionService())->getInvoiceNo('Bar Sale'),
            ]);

            // return redirect()->route('bar.sales-v2.show', $sale->id)->with('message', 'Sale created success !');
        }
        catch (\Throwable $th) {

            return response()->json([
                'status'    => 0,
                'message'   => 'Server Error',
                'data'      => $th->getMessage(),
            ]);

        }
    }













    /*
     |--------------------------------------------------------------------------
     | SHOW METHOD
     |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        $this->hasAccess("bar.sales.view");

        $view = 'bar.sales-v2.show';
        if (request('invoice_type') == 'pos') {
            $view = 'bar.sales-v2.pos-print';
        }
        return view($view, [
            'sale'              => Sale::with('items')->find($id),
            'vat_number'        => Vat::first()->vat_number,
            'account_types'     => AccountType::whereHas('hotelTransactions')->pluck('name', 'id'),
        ]);
    }













    /*
     |--------------------------------------------------------------------------
     | EDIT METHOD
     |--------------------------------------------------------------------------
    */
    public function edit($id)
    {
        $data['sale']           = Sale::query()
                                ->with(['items' => function($query){
                                    $query->with(['product' => function($query){
                                        $query->querySum('stocks', 'total_quantity', 'available_quantity');
                                    }]);
                                }])
                                ->find($id);
        $data['account_types']  = AccountType::pluck('name', 'id');
        $data['vat_percent']    = Vat::first()->bar_vat;
        $data['guests']         = Guest::query()->where('id', $data['sale']->guest_name)->get();
        $data['tables']         = RstTableManage::query()->get();

        // Booking Checked
        $data['mix_date']           = fdate(today_from_system(),'m/d/Y') . ' - ' . Carbon::parse(today_from_system())->addDay()->format('m/d/Y');
        $check_in                   = date('Y-m-d');
        $check_out                  = date('Y-m-d', strtotime($check_in . "+1 days"));
        $data['mix_date']           = $check_in . ' - ' . $check_out;
        $data['booking_date']       = date('m/d/Y') . ' - ' . Carbon::now()->addDay()->format('m/d/Y');
        $data['room_numbers'] = (new RoomStatusService())
                                ->BookedRoom($check_in, $check_out)
                                ->where('is_booked', '>=', 1)
                                ->where('is_checkin', '>=', 1)
                                // ->where('is_checkin', '>=', 1)
                                ->pluck('room_number', 'id');


        return view('bar.sales-v2._inc.edit-sale', $data)->render();
    }













    /*
     |--------------------------------------------------------------------------
     | UPDATE METHOD
     |--------------------------------------------------------------------------
    */
    public function update($id, Request $request)
    {
        try {

            $sale = null;

            DB::transaction(function () use ($id, &$sale) {

                $saleService    = (new SaleService());
                $sale           = $saleService->update($id);
                $saleService->updateSaleItem();
            });


                return response()->json([
                    'status'    => 1,
                    'message'   => 'Success',
                    'data'      => $sale,
                    'invoice_no'=> (new BarTransectionService())->getInvoiceNo('Bar Sale'),
                ]);


        } catch (\Throwable $th) {
            return response()->json([
                'status'    => 0,
                'message'   => 'Server Error',
                'data'      => $th->getMessage(),
            ]);

        }
    }












    /*
     |--------------------------------------------------------------------------
     | DELETE/DESTORY METHOD
     |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        $this->hasAccess("bar.sales.delete");

        try {
            $sale = Sale::find($id);
            $sale->delete();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
        return redirect()->back()->with('success', 'Sale deleted success !');
    }




    /*
     |--------------------------------------------------------------------------
     | DELETE SALE ITEM METHOD
     |--------------------------------------------------------------------------
    */
    public function destroySaleItem($id)
    {
        $this->hasAccess("bar.sales.delete");

        try {

            DB::transaction(function() use($id) {

                $saleItem = SaleItem::where('id',$id)->first();
                (new SaleService())->stockUpdateIfSaleUpdate($saleItem->product_id, $saleItem->quantity);
                $saleItem->delete();
                (new SaleService())->calculateSaleAmount($saleItem->sale_id);

            });

        } catch (\Throwable $th) {

            return response()->json([
                'status'    => 0,
                'data'      => $th->getMessage(),
                'message'   => 'Server Error',
            ]);
        }

            return response()->json([
                'status'    => 1,
                'data'      => [],
                'message'   => 'Success',
            ]);
    }






    /*
     |--------------------------------------------------------------------------
     | UPDATE CALCULATION METHOD
     |--------------------------------------------------------------------------
    */
    public function updateCalculation($id)
    {
        try {

           $sale = Sale::query()->where('id', $id)->first();

           if ($sale) {
            $sale->update([
                'subtotal'  => $sale->details->sum('item_price')
            ]);

            HotelTransection::where('source_type', 'Bar Sale')->where('source_id', $id)->update([
                'total_amount'  => $sale->details->sum('item_price'),
            ]);
           }
        } catch (\Throwable $th) {
            //throw $th;
        }

        return redirect()->back()->with('message', 'Successfully updated.');
    }







    /*
     |--------------------------------------------------------------------------
     | UPDATE PAID AMOUNT METHOD
     |--------------------------------------------------------------------------
    */
    public function updatePaidAmount($saleId)
    {
        try {

           $sale = Sale::query()->where('id', $saleId)->first();

           if ($sale) {

                DB::transaction(function () use($sale){

                    // SALE UPDATE
                    $sale->update([
                        'paid_amount'    => $sale->payable_amount,
                        'change_amount'  => 0,
                        'due_amount'     => 0,
                        'payment_way'    => 'Cash',
                        'payment_status' => 'Paid',
                    ]);


                    $accountType = AccountType::where('name', 'Cash')->first();

                    // TRANSACTION UPDATE
                    (new HotelTransactionService())->storeTransaction(
                        $sale,
                        $sale->id,
                        'Bar Sale',
                        $accountType->id ?? null,
                        $sale->payable_amount, // total_amount
                        $sale->discount,
                        $sale->payable_amount, // collection
                        $sale->vat_amount,
                        $sale->service_amount,
                        null, // booking_id
                        $sale->invoice_no,
                        fdate($sale->date ?? date('Y-m-d'),'Y-m-d')
                    );

                });

           }


        } catch (\Throwable $th) {
            throw $th;
        }

        return redirect()->back()->with('message', 'Successfully updated.');
    }

    public function dueCollection(Request $request, $id)
    {
        // return $id;
        try {

           $sale = Sale::query()->where('pay_booking_id', $id)->first();

           if ($sale) {

                DB::transaction(function () use($sale, $id){

                    // SALE UPDATE
                    $sale->update([
                        'paid_amount'    => $sale->payable_amount,
                        'change_amount'  => 0,
                        'due_amount'     => 0,
                        'payment_way'    => 'Cash',
                        'payment_status' => 'Paid',

                    ]);


                    $accountType = AccountType::where('name', 'Cash')->first();

                    // TRANSACTION UPDATE
                    (new HotelTransactionService())->storeTransaction(
                        $sale,
                        $sale->id,
                        'Bar Sale',
                        $accountType->id ?? null,
                        $sale->payable_amount, // total_amount
                        $sale->discount,
                        0, // collection
                        $sale->vat_amount,
                        $sale->service_amount,
                        $id, // booking_id
                        $sale->invoice_no,
                        fdate($sale->date ?? date('Y-m-d'),'Y-m-d')
                    );

                });

           }


        } catch (\Throwable $th) {
            throw $th;
        }

        return redirect()->back()->with('message', 'Successfully updated.');
    }



}
