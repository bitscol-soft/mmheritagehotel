<?php

namespace Module\Restaurant\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Module\Hotel\Models\Vat;
use Module\Hotel\Models\Guest;
use Illuminate\Support\Facades\DB;
use Module\Restaurant\Models\Sale;
use App\Http\Controllers\Controller;
use Module\Hotel\Models\AccountType;
use Module\Hotel\Models\HotelTransection;
use Module\Hotel\Models\NightAuditDetail;
use Module\Restaurant\Models\KitchenOrder;
use Module\Restaurant\Request\SaleRequest;
use Module\Restaurant\Services\SaleService;
use Module\Restaurant\Services\ResturentTransectionService;
use Module\CRM\Models\CRMCustomer;

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
        $this->hasAccess("resturant.sales.index");

        $data = [
            'sales' => Sale::query()
                ->searchByField('invoice_no')
                ->searchByField('date')
                ->where('is_bar', 0)
                ->latest()
                ->paginate(25)
        ];

        return view('sales.index', $data);
    }













    /*
     |--------------------------------------------------------------------------
     | CREATE METHOD
     |--------------------------------------------------------------------------
    */
    public function create()
    {
        $this->hasAccess("resturant.sales.create");

        $data['invoice_id']     = (new ResturentTransectionService())->getInvoiceNo('Restaurant Sale');
        $data['vat_percent']    = Vat::first()->resturent_vat;
        $data['account_types']  = AccountType::get();

        return view('sales.create', $data);
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

            return redirect()->route('rst.sales.show', $sale->id)->with('message', 'Sale created success !');

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
        $this->hasAccess("resturant.sales.view");

        return view('sales.show', [
            'sale'          => Sale::query()->with('items', 'transaction_ledgers')->find($id),
            'vat_number'    => Vat::first()->vat_number,
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

        $this->hasAccess("resturant.sales.delete");

        try {

            DB::transaction(function () use($id) {

                $sale = Sale::find($id);
                $kit_sale = KitchenOrder::with('order_items')
                    ->where('sale_id', $id)->first();

                if (!$sale) {
                    // Handle the case where the sale record is not found.
                    return redirect()->back()->with('error', 'Sale not found.');
                }

                // DELETE KITCHEN ORDERS FIRST
                if ($kit_sale) {
                    // Delete kitchen order items first
                    foreach ($kit_sale->order_items as $kitItem) {
                        $kitItem->delete();
                    }
                    // Then delete the kitchen order itself
                    $kit_sale->delete();
                }

                $this->service->sale = $sale;

                $hotelTransaction = HotelTransection::where([
                    'source_id'   => $sale->id,
                    'source_type' => 'Restaurant Sale',
                ])->with('night_audits')->first();

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
                foreach ($sale->items as $saleItem) {
                    $this->service->stockUpdateIfSaleDelete($saleItem->product_id, $saleItem->quantity);
                    $saleItem->delete();
                }

                // SALE DELETE
                $sale->forceDelete();


            });

        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }

        return redirect()->back()->with('success', 'Sale deleted success !');

    }


    /*
     |--------------------------------------------------------------------------
     | OLD DELETE METHOD
     |--------------------------------------------------------------------------
    */
    public function olddelete($id)
    {

        $this->hasAccess("resturant.sales.delete");

        try {

            DB::transaction(function () use($id) {

                $sale                = Sale::find($id);
                $this->service->sale = $sale;

                $hotelTransaction = HotelTransection::where([
                    'source_id'         => $sale->id,
                    'source_type'       => 'Restaurant Sale',
                ])->with('night_audits')->first();


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

                    $this->service->stockUpdateIfSaleDelete($saleItem->product_id, $saleItem->quantity);

                    $saleItem->delete();
                }

                // SALE DELETE
                $sale->forceDelete();

            });

        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }

        return redirect()->back()->with('success', 'Sale deleted success !');

    }




    //--------------------------------------------------------------------------//
    //                        PAYMENT COLLECTION METHOD                         //
    //--------------------------------------------------------------------------//
    public function paymentCollection(Request $request){

        $this->hasAccess("resturant.sales.index");

        $data['hotelGuests']    =   Guest::where('status', 1)->get();
        $data['customers']      =   CRMCustomer::orderByDesc('id')->get();
        $data['account_type']   = AccountType::where('status', 1)->pluck('name', 'id');

        $data['transactions']   = 0;
        $data['hotelGuest']     = '';

        if (request()->has('hotel_guest_id')) {

            $data['hotelGuest']     = Guest::where('id', $request->hotel_guest_id)->first();

            $data['transactions']   =   HotelTransection::where('source_type', 'Restaurant Sale')
                                                        ->where('due_amount', '>', 0)
                                                        ->whereHas('rstSale', function($q) use($request){
                                                            $q->where('hotel_guest_id', $request->hotel_guest_id);
                                                        })
                                                        ->with('source')
                                                        ->get();
        }

        return view('rst-payment-collection.index', $data);
    }






    //--------------------------------------------------------------------------//
    //                      STORE PAYMENT COLLECTION METHOD                     //
    //--------------------------------------------------------------------------//
    public function storePaymentCollection(Request $request){

        $request->validate([
                'payment_type'=> 'required',
            ]);

        try {

            $this->service->collectDue($request);

            return redirect()->route('rst.sales.index')->with('message', 'Payment Collected Successfully!');
        }
        catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }

    }




}
