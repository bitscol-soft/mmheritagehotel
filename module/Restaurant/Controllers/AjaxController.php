<?php

namespace Module\Restaurant\Controllers;

use Illuminate\Http\Request;
use Module\Hotel\Models\Guest;
use Illuminate\Support\Facades\DB;
use Module\Restaurant\Models\Sale;
use Module\Restaurant\Models\Stock;
use App\Http\Controllers\Controller;
use Module\Hotel\Models\AccountType;
use Module\Restaurant\Models\Product;
use Module\Restaurant\Models\RstTableManage;
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
        return view('purchase.ajax.products', $data)->render();
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
        $data['products'] = Product::companies()->where('supplier_id', $request->invoice_no)->get();
        return view('purchase.ajax.products', $data)->render();
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

        $sale = Sale::query()->doesntHave('return_items')->when($request->filled('invoice_no'), function ($query) use ($request) {

                return $query->where('invoice_no', $request->invoice_no);
            })
            ->with('items.product')
            ->withCount(['items as total_quantity' => function ($query) {
                return $query->select(DB::raw('SUM(quantity)'));
            }])
            ->first();

            return view('sales/return/saleable-product-render', compact('sale'))->render();
    }





    public function saveGuestData(Request $request)
    {
        try {
            $guest = Guest::query()->firstOrCreate([
                'phone_no'  => $request->guest_mobile,
            ],[
                'name'      => $request->guest_name,
                'country_id'=> 18,
                'is_stuff'  => $request->is_stuff,
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



    /*
     |--------------------------------------------------------------------------
     | GET TABLE
     |--------------------------------------------------------------------------
    */
    public function getTable(Request $request)
    {
        return RstTableManage::query()->when($request->filled('search'), fn($q)=> $q->where('name', 'LIKE', $request->search .'%'))->take(25)->get();
    }




    /*
     |--------------------------------------------------------------------------
     | GET TABLE
     |--------------------------------------------------------------------------
    */
    public function getDate()
    {
        return getSaleDate(request('date'));
    }



    public function getSaleOLD(Request $request)
    {

        $data['sales'] = Sale::query()->where('is_bar', 0)
                                ->whereHas('RstTransactions', function($query){
                                    $query->whereDoesntHave('night_audits');
                                })
                                ->when($request->filled('payment_status'), fn($q)=> $q->where('payment_status', $request->payment_status))
                                // ->when($request->filled('date'), fn($q)=> $q->where('date', $request->date))
                                ->when($request->filled('invoice_no'), fn($q)=> $q->where('invoice_no', $request->invoice_no))
                                ->paginate(30);



        // $data['sales'] = Sale::query()->where('is_bar', 0)
        // ->when($request->filled('payment_status'), fn($q)=> $q->where('payment_status', $request->payment_status))
        // ->when($request->filled('date'), fn($q)=> $q->where('date', $request->date))
        // ->when($request->filled('invoice_no'), fn($q)=> $q->where('invoice_no', $request->invoice_no))
        // ->paginate(30);

        return view('rst.sales-v2._inc/sales-data', $data)->render();
    }

    public function getSale(Request $request)
    {

        // $data['sales'] = Sale::query()->where('is_bar', 0)
        //                         ->whereHas('RstTransactions', function($query){
        //                             $query->whereDoesntHave('night_audits');
        //                         })
        //                         ->when($request->filled('payment_status'), fn($q)=> $q->where('payment_status', $request->payment_status))
        //                         // ->when($request->filled('date'), fn($q)=> $q->where('date', $request->date))
        //                         ->when($request->filled('invoice_no'), fn($q)=> $q->where('invoice_no', $request->invoice_no))
        //                         ->paginate(30);



        // $data['sales'] = Sale::query()->where('is_bar', 0)
        // ->when($request->filled('payment_status'), fn($q)=> $q->where('payment_status', $request->payment_status))
        // ->when($request->filled('date'), fn($q)=> $q->where('date', $request->date))
        // ->when($request->filled('invoice_no'), fn($q)=> $q->where('invoice_no', $request->invoice_no))
        // ->paginate(30);


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
                                ->whereHas('RstTransactions', function($query){
                                    $query->whereDoesntHave('night_audits');
                                })
                                // ->with('hotel_transaction_ledgers')
                                ->when($request->filled('payment_status'), fn($q)=> $q->where('payment_status', $request->payment_status))
                                ->when($request->filled('date'), fn($q)=> $q->where('date', $request->date))
                                // ->when($request->filled('invoice_no'), fn($q)=> $q->where('invoice_no', $request->invoice_no))
                                ->likeSearch('invoice_no')
                                ->paginate(30);

        $sale_view          = view('rst.sales-v2._inc/sales-data', $data)->render();
        $transaction_view   = view('rst.sales-v2._inc/transaction-ledger', $data)->render();
        return response()->json([
            'sale_view'         => $sale_view,
            'transaction_view' => $transaction_view,
        ]);
    }
}
