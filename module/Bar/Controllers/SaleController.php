<?php

namespace Module\Bar\Controllers;

use Module\Bar\Models\Sale;
use Illuminate\Http\Request;
use Module\Hotel\Models\Vat;
use Module\Bar\Request\SaleRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Module\Bar\Services\SaleService;
use Module\Hotel\Models\AccountType;
use Module\Hotel\Models\HotelTransection;
use Module\Bar\Services\BarTransectionService;

class SaleController extends Controller
{
    private $service;


    /*
     |--------------------------------------------------------------------------
     | CONSTRUCTOR
     |--------------------------------------------------------------------------
    */
    public function __construct()
    {
        $this->service = new SaleService();
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
            'sales' => Sale::query()
                ->searchByField('invoice_no')
                ->searchByField('date')
                ->latest()
                ->paginate(25)
        ];


        if (request('update_transaction') == 1) {
            $this->updateWithTrans();
            // Sale::query()->with('barTransaction')->get()->map(function($query){
            //     $query->update([
            //         'vat_amount'        => $query->vat_amount > 0 ?: 0,
            //         'service_amount'    => $query->service_amount > 0 ?: 0,
            //     ]);
            //     $query->refresh();
            //     $query->barTransaction()->update([
            //         'total_amount'  => $query->payable_amount,
            //         'collection'    => $query->paid_amount
            //     ]);
            // });
        }

        return view('bar.sales.index', $data);
    }













    /*
     |--------------------------------------------------------------------------
     | CREATE METHOD
     |--------------------------------------------------------------------------
    */
    public function create()
    {
        $this->hasAccess("bar.sales.create");

        $data['invoice_id']     = (new BarTransectionService())->getInvoiceNo('Bar Sale');
        $data['vat_percent']    = Vat::first()->resturent_vat;
        $data['account_types']  = AccountType::get();
        // return $data;
        return view('bar/sales/create', $data);
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


            return redirect()->route('bar.sales.show', $sale->id)->with('message', 'Sale created success !');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
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

        return view('bar.sales.show', [
            'sale'          => Sale::with('items', 'transaction_ledgers')->find($id),
            'vat_number'    => Vat::first()->vat_number,
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
        # code...
    }













    /*
     |--------------------------------------------------------------------------
     | UPDATE METHOD
     |--------------------------------------------------------------------------
    */
    public function update($id, Request $request)
    {
        # code...
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

            DB::transaction(function () use($id) {

                $sale                = Sale::find($id);
                $this->service->sale = $sale;

                $hotelTransaction = HotelTransection::where([
                    'source_id'         => $sale->id,
                    'source_type'       => 'Bar Sale',
                ])->first();

                // DELETE HOTEL TRANSACTION
                if ($hotelTransaction != null) {

                    if (count($hotelTransaction->night_audits) > 0) {
                        return redirect()->back()->with('error', 'This Sale has been generated in Night Audit');
                    }

                    $hotelTransaction->transaction_ledgers()->delete();
                    $hotelTransaction->delete();
                }

                // DELETE ACC ACCOUNT TRANSACTION
                if ($sale->transactions != null) {
                    $sale->transactions()->delete();
                }


                // SALE ITEM DELETE
                foreach($sale->items as $saleItem){
                    $this->service->stockUpdateIfSaleDelete($saleItem->product_id, $saleItem->quantity, $saleItem->unit_id);
                    $saleItem->delete();
                }

                // SALE DELETE
                $sale->delete();

            });

        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'You can not delete this record.');
        }
        return redirect()->back()->with('success', 'Sale deleted success !');
    }




    //=================== SALE TOTAL AMOUNT UPDATE WITH TRANSACTION =====================//
    public function updateWithTrans()
    {
        $id = request('sale_id');


        $sale = Sale::query()->with('details')->where('id', $id)->get()->map(function($item) use($id){

            $subtotal = $item->details->sum('item_price');
            $paid_amount = $item->paid_amount > 0 ? $subtotal : $item->paid_amount; //SAME AMOUNT WILL BE PAID

            $item->update([
                'subtotal'      => $subtotal,
                'paid_amount'   => $paid_amount
            ]);

            HotelTransection::where('source_type', 'Bar Sale')->where('source_id', $id)->update([
                'total_amount'  => $subtotal,
                'collection'    => $paid_amount,
            ]);
        });

    }
}
