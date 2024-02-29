<?php

namespace Module\Bar\Controllers;

use Module\Bar\Models\Sale;
use Illuminate\Http\Request;
use Module\Bar\Models\Stock;
use Module\Bar\Models\Product;
use Module\Hotel\Models\Guest;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Module\Hotel\Models\AccountType;
use Module\Hotel\Models\HotelTransactionLedger;

class AjaxController extends Controller
{
    private $service;


    /*
     |--------------------------------------------------------------------------
     | CONSTRUCTOR
     |--------------------------------------------------------------------------
    */
    public function __construct()
    {
        // $this->hasAccess("pharmacy.view");
    }











    /**
     * ---------------------------------------------------------------------
     * AJAX METHODS - GET GuestData
     * ---------------------------------------------------------------------
     **/

    public function GetGuestData(Request $request){
        return Guest::query()
            ->where(function($q) use($request) {
                $q->where("name", "like", "%{$request->search}%")
                    ->orWhere("name", "like", "%{$request->search}")
                    ->orWhere("name", "like", "{$request->search}%");
            })
            ->take(25)
            ->get()
            ->map(function ($item) {
                return [
                    'id'            => $item->id,
                    'name'          => $item->name,
                ];
            });
    }




    /**
     * ---------------------------------------------------------------------
     * AJAX METHODS - GET DRUGS
     * ---------------------------------------------------------------------
     **/

    public function getDrug(Request $request)
    {
        return Stock::query()->with('category', 'unit', 'brand', 'medicineType')
            ->whereCompanyId(company_id())
            ->where('name', 'LIKE', "%{$request->query('name')}%")
            ->take(15)
            ->get();
    }







    /**
     * ---------------------------------------------------------------------
     * AJAX METHODS - GET PURCHASABLE PRODUCTS
     * ---------------------------------------------------------------------
     **/

    public function getPurchasableProduct($id)
    {
        $data['products'] = Product::query()->companies()->where('supplier_id', $id)->get();
        // dd($data);
        return view('bar.purchase.ajax.products', $data)->render();
    }






    /**
     * ---------------------------------------------------------------------
     * AJAX METHODS - GET PURCHASABLE PRODUCTS
     * ---------------------------------------------------------------------
     **/

    public function getProductBySaleInvoice(Request $request)
    {
        return
            Sale::query()->when($request->filled('invoice_no'), function ($query) use ($request) {

                return $query->where('invoice_no', $request->invoice_no);
            })
            ->with('items.product')
            ->withCount(['items as total_quantity' => function ($query) {
                return $query->select(DB::raw('SUM(quantity)'));
            }])
            ->get();
        $data['products'] = Product::query()->companies()->where('supplier_id', $request->invoice_no)->get();

        return view('purchase.ajax.products', $data)->render();
        // bar.purchase.ajax.products
    }






    /**
     * ---------------------------------------------------------------------
     * AJAX METHODS - GET SALABLE PRODUCTS
     * ---------------------------------------------------------------------
     **/

    public function getSaleableProduct(Request $request)
    {

        // return Product::whereHas('saleItems')->whereHas('saleItems', function ($query) use ($request) {
        //     $query->with(['sale' => function ($q) use ($request) {
        //         $q->where('invoice_no', $request->invoice_no);
        //     }])->get();
        // });

        $sale = Sale::query()->doesntHave('return_items')
                    ->when($request->filled('invoice_no'), function ($query) use ($request) {
                        return $query->where('invoice_no', $request->invoice_no);
                    })
                    ->with('items.product')
                    ->withCount(['items as total_quantity' => function ($query) {
                        return $query->select(DB::raw('SUM(quantity)'));
                    }])
                    ->first();

            return view('sales/return/saleable-product-render', compact('sale'))->render();
            // return view('sales/return/saleable-product-render', compact('sale'))->render();
    }





    public function saveGuestData(Request $request)
    {
        try {
            $guest = Guest::firstOrCreate([
                'phone_no'  => $request->guest_mobile,
            ],[
                'name'      => $request->guest_name,
                'country_id'=> 18,
                'is_bar'    => 1,
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'status'    => 0,
                'data'      => [],
                'message'   => $th->getMessage(),
            ]);
        }

        return response()->json([
            'status'    => 1,
            'data'      => $guest,
            'message'   => 'Success',
        ]);
    }





    //-----------------------------------------------------------------------//
    //                              GET SALE METHOD                          //
    //-----------------------------------------------------------------------//
    public function getSale(Request $request)
    {

        $data['accountTypes']           = AccountType::get();
        $data['transaction_ledgers']    = HotelTransactionLedger::whereHas('sale', function($q){
                                                                    $q->where('payment_status', 'paid')
                                                                      ->whereHas('barTransactions', function($query){
                                                                            $query->whereDoesntHave('night_audits');
                                                                      });
                                                                })
                                                                ->where('source_type', 'Bar Sale')
                                                                ->get();



        $data['sales']  =   Sale::query()
                                ->whereHas('barTransaction', function($query){
                                    $query->whereDoesntHave('night_audits');
                                })
                                // ->with('hotel_transaction_ledgers')
                                ->when($request->filled('payment_status'), fn($q)=> $q->where('payment_status', $request->payment_status))
                                ->when($request->filled('date'), fn($q)=> $q->where('date', $request->date))
                                ->when($request->filled('invoice_no'), fn($q)=> $q->where('invoice_no', $request->invoice_no))
                                ->paginate(30);


        $sale_view          = view('bar.sales-v2._inc/sales-data', $data)->render();
        $transaction_view   = view('bar.sales-v2._inc/transaction-ledger', $data)->render();
        return response()->json([
            'sale_view'         => $sale_view,
            'transaction_view' => $transaction_view,
        ]);

    }
}
