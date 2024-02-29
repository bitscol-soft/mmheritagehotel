<?php

namespace Module\Restaurant\Controllers;

use Illuminate\Http\Request;
use Module\Hotel\Models\Vat;
use Module\Hotel\Models\Guest;
use Module\Hotel\Models\Rooms;
use Module\Restaurant\Models\Sale;
use App\Http\Controllers\Controller;
use Module\Hotel\Models\AccountType;
use Module\Restaurant\Models\SaleItem;
use Module\Restaurant\Request\SaleRequest;
use Module\Restaurant\Services\SaleService;
use Module\Restaurant\Models\RstTableManage;
use Module\Bar\Services\BarTransectionService;

class SaleV2Controller extends Controller
{
    private $service;
    private $card_info;


    /*
     |--------------------------------------------------------------------------
     | CONSTRUCTOR
     |--------------------------------------------------------------------------
    */
    public function __construct()
    {
        $this->card_info = new SaleService();
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
            'sales' => Sale::query()->searchByField('invoice_no')
                ->latest()
                ->paginate(25)
        ];

        return view('rst.sales-v2.index', $data);
    }







    /*
     |--------------------------------------------------------------------------
     | CREATE METHOD
     |--------------------------------------------------------------------------
    */
    public function create()
    {
        $this->hasAccess("resturant.sales.create");

        $invoice                = (new BarTransectionService())->getInvoiceNo('Restaurant Sale');
        $data['invoice_id']     = $invoice;
        $data['vat_percent']    = Vat::first()->restaurant_vat;
        $data['account_types']  = AccountType::pluck('name', 'id');
        $data['guests']         = Guest::paginate(2);
        $data['tables']         = RstTableManage::query()->get();
        $data['room_numbers']   = Rooms::pluck('room_number', 'id');

        return view('rst/sales-v2/create', $data);
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

            if($request->card_info != null){

                $this->card_info->StoreCardInfo($request, $sale);
            }

            return response()->json([
                'status'    => 1,
                'message'   => 'Success',
                'data'      => $sale,
                'invoice_no'=>(new BarTransectionService())->getInvoiceNo('Restaurant Sale'),
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
     | SHOW METHOD
     |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        $this->hasAccess("resturant.sales.view");

        $view = 'rst.sales-v2.show';

        if (request('invoice_type') == 'pos') {
            $view = 'rst.sales-v2.pos-print';
        }

        return view($view, [
            'sale'          => Sale::with('items')->find($id),
            'vat_number'    => Vat::first()->vat_number,
        ]);
    }



    /*
     |--------------------------------------------------------------------------
     | OFFICE COPY METHOD
     |--------------------------------------------------------------------------
    */
    public function officeCopy($id)
    {
        $this->hasAccess("resturant.sales.view");

        $view = 'rst.sales-v2.show';

        if (request('invoice_type') == 'pos') {
            $view = 'rst.sales-v2.pos-office-print';
        }

        return view($view, [
            'sale'          => Sale::with('items')->find($id),
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
        $data['sale']           = Sale::query()
                                ->with(['items' => function($query){
                                    $query->with(['product' => function($query){
                                        $query->querySum('stocks', 'total_quantity', 'available_quantity');
                                    }]);
                                }])
                                ->find($id);
        $data['account_types']  = AccountType::pluck('name', 'id');
        $data['guests']         = Guest::query()->where('id', $data['sale']->hotel_guest_id)->get();
        $data['tables']         = RstTableManage::query()->get();

        return view('rst.sales-v2._inc.edit-sale', $data)->render();
    }





    /*
     |--------------------------------------------------------------------------
     | UPDATE METHOD
     |--------------------------------------------------------------------------
    */
    public function update($id, Request $request)
    {
        try {

            $saleService = (new SaleService());
            $sale = $saleService->update($id);
            $saleService->updateSaleItem();

            return response()->json([
                'status'    => 1,
                'message'   => 'Success',
                'data'      => $sale,
                'invoice_no'=>(new BarTransectionService())->getInvoiceNo('Bar Sale'),
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
        $this->hasAccess("resturant.sales.delete");

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
        $this->hasAccess("resturant.sales.delete");

        try {

            $sale = SaleItem::where('id',$id)->first();
            (new SaleService())->stockUpdateIfSaleUpdate($sale->product_id, $sale->quantity);
            $sale->delete();
            (new SaleService())->calculateSaleAmount($sale->sale_id);

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
}
