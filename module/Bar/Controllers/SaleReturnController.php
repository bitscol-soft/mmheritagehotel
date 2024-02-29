<?php

namespace Module\Bar\Controllers;

use Module\Bar\Models\Sale;
use Illuminate\Http\Request;
use Module\Bar\Models\SaleItem;
use Module\Bar\Models\SaleReturn;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Module\Bar\Models\SaleReturnDetail;
use Module\Bar\Services\SaleReturnService;
use Module\Restaurant\Models\SaleReturnDetail as Rdetails;

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
        $this->hasAccess("bar.sales.index");

        $data = [
            'sales' => SaleReturn::searchByField('invoice_no')
                ->latest()
                ->paginate(25)
        ];

        return view('bar.sales.return.index', $data);
    }













    /*
     |--------------------------------------------------------------------------
     | CREATE METHOD
     |--------------------------------------------------------------------------
    */
    public function create()
    {
        $this->hasAccess("bar.sales.create");

        $last_invoice_id    = SaleReturn::latest()->value('invoice_no') ?: 1000;
        $data['invoice_id'] = $last_invoice_id;

        return view('bar.sales.return.create', $data);
    }













    /*
     |--------------------------------------------------------------------------
     | STORE METHOD
     |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        // $this->hasAccess("bar.sales.create");

        $request->validate([
            'date'              => 'required',
            'subtotal'          => 'required',
            // 'discount'          => 'nullable',
            'return_amount'     => 'nullable',
            'payable_amount'    => 'required',
            'due_amount'        => 'nullable',
        ]);
        // dd($request->all());


        try {
            return DB::transaction(function () use ($request) {

                $saleService    = new SaleReturnService();

                $sale           = $saleService->store();

                $saleService->storeItem();


                return redirect()->route('bar.sale-returns.show', $sale->id)->with('message', 'Sale Return created success !');
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
        $this->hasAccess("bar.sales.view");

        return view('bar.sales.return.show', [
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
        $sale =  SaleReturn::find($id);
        SaleReturnDetail::where('sale_return_id', $sale->id)->delete();

        $sale->delete();

        return redirect()->back()->with('success', 'Success');
    }
}
