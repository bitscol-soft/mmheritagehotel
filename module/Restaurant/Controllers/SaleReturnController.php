<?php

namespace Module\Restaurant\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Module\Restaurant\Models\Sale;
use App\Http\Controllers\Controller;
use Module\Restaurant\Services\SaleReturnService;
use Module\Restaurant\Models\SaleReturn;
// use Module\Bar\Services\SaleReturnService;

class SaleReturnController extends Controller
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
        $this->hasAccess("resturant.sales.index");

        $data = [
            'sales' => SaleReturn::query()->searchByField('invoice_no')
                ->latest()
                ->paginate(25)
        ];

        return view('sales.return.index', $data);
    }













    /*
     |--------------------------------------------------------------------------
     | CREATE METHOD
     |--------------------------------------------------------------------------
    */
    public function create()
    {
        $this->hasAccess("resturant.sales.create");

        $last_invoice_id    = SaleReturn::query()->latest()->value('invoice_no') ?: 1000;
        $data['invoice_id'] = $last_invoice_id;

        return view('sales.return.create', $data);
    }













    /*
     |--------------------------------------------------------------------------
     | STORE METHOD
     |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $this->hasAccess("resturant.sales.create");


        $request->validate([
            'date'              => 'required',
            'subtotal'          => 'required',
            // 'discount'          => 'nullable',
            'return_amount'     => 'nullable',
            'payable_amount'    => 'required',
            'due_amount'        => 'nullable',
        ]);


        try {
            return DB::transaction(function () use ($request) {

                $saleService    = new SaleReturnService();

                $sale           = $saleService->store();

                $saleService->storeItem();


                return redirect()->route('rst.sale-returns.show', $sale->id)->with('message', 'Sale Return created success !');
            });
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

        return view('sales.return.show', [
            'sale' => SaleReturn::with('items')->find($id),
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
        return redirect()->back()->with('error', 'Working is processing');
    }
}
