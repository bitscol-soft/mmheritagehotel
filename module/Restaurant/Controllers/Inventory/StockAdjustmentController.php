<?php

namespace Module\Restaurant\Controllers\Inventory;

use Illuminate\Http\Request;
use Module\Bar\Models\Purchase;
use Module\Product\Models\Stock;
use Illuminate\Support\Facades\DB;
use Module\Account\Models\Account;
use App\Http\Controllers\Controller;
use Module\Restaurant\Models\Product;
// use App\Services\NextInvoiceNoService;
// use Module\Inventory\Models\Warehouse;
// use Module\Product\Models\StockSummary;
// use Module\Inventory\Models\StockAdjustment;
// use Module\Inventory\Models\StockAdjustmentDetail;
use Module\Restaurant\Models\Supplier;
use Module\Restaurant\Request\AdjustRequest;
use Module\Restaurant\Models\StockAdjustment;


use Module\Bar\Services\BarTransectionService;
use Module\Restaurant\Request\StockAdjustRequest;
use Module\Restaurant\Services\StockAdjustService;
use Module\Inventory\Services\DynamicLotManageService;
use Module\Restaurant\Services\ResturentTransectionService;
// use Module\Bar\Models\Supplier;

class StockAdjustmentController extends Controller
{
    private $service;
    public $nextInvoiceNumberService;
    public $dynamicLotManageService;

    /*
     |--------------------------------------------------------------------------
     | CONSTRUCTOR
     |--------------------------------------------------------------------------
    */
    public function __construct()
    {
        $this->service                         = new StockAdjustService;
        // $this->nextInvoiceNumberService     = (new ResturentTransectionService())->getInvoiceNo();
        // $this->dynamicLotManageService      = new DynamicLotManageService;
    }


    /*
     |--------------------------------------------------------------------------
     | INDEX METHOD
     |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {


        $this->hasAccess("rst.stock-adjustment.index");

        return view('inventory.adjustment-v2.index', [
            'stock_adjustment' => StockAdjustment::with('adjustment_details')->orderByDesc('id')->paginate(20),

        ]);
    }


    /*
     |--------------------------------------------------------------------------
     | CREATE METHOD
     |--------------------------------------------------------------------------
    */
    public function create()
    {

        $this->hasAccess("rst.stock-adjustment.create");

        $data['challan_id']     = (new BarTransectionService())->getInvoiceNo('Stock Adjust');
        $data['accounts']       = Account::companies()->get();
        $data['suppliers']      = Supplier::query()->companies()->whereStatus(true)->get();
        return view('inventory.adjustment-v2.create', $data);
    }



    /*
     |--------------------------------------------------------------------------
     | STORE METHOD
     |--------------------------------------------------------------------------
    */
    public function store(StockAdjustRequest $request)
    {
        $this->hasAccess("rst.stock-adjustment.store");
        try {
            $stock_adjust = $request->store();
        } catch (\Exception $ex) {
            return redirect()->back()->withInput()->withError($ex->getMessage());
        }


        return redirect()->route('rst.stock-adjustment.index', $stock_adjust->id)->withSuccess('Stock Adjustment Successfully!');
    }








   /*
     |--------------------------------------------------------------------------
     | SHOW METHOD
     |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        $this->hasAccess("rst.stock-adjustment.show");
        $data['stockAdjustment']       = StockAdjustment::find($id);
        $data['Adjustment']            = StockAdjustment::with('adjustment_details')->find($id);

        return view('inventory.adjustment-v2.view', $data);
    }





    /*
     |--------------------------------------------------------------------------
     | EDIT METHOD
     |--------------------------------------------------------------------------
    */
    public function edit($id)
    {
        $this->hasAccess("rst.stock-adjustment.edit");
        $data['stockAdjustment']       = StockAdjustment::find($id);
        $data['Adjustment']            = StockAdjustment::with('adjustment_details')
                                                        ->find($id);
        return view('inventory.adjustment-v2.edit', $data);
    }





    /*
     |--------------------------------------------------------------------------
     | UPDATE METHOD
     |--------------------------------------------------------------------------
    */
    public function update($id, AdjustRequest $request)
    {
        $this->hasAccess("rst.stock-adjustment.update");
        try {
            $stock_adjust = $request->datastore();
        } catch (\Exception $ex) {

            return redirect()->back()->withInput()->withError($ex->getMessage());
        }


        return redirect()->route('rst.stock-adjustment.index')->withSuccess('Stock Adjustment Approved!');
    }





    /*
     |--------------------------------------------------------------------------
     | GET PURCHASE DATA
     |--------------------------------------------------------------------------
    */
    public function getStockAdjustmentData($id)
    {
        return  StockAdjustment::query()
                ->where('id', $id)
                ->with(['stock_adjustment_details' => function ($q1) {
                    $q1->with('product_variation:id,name,sku')
                    ->with(['product' => function ($q2) {
                        $q2->select('id', 'name', 'code','sku', 'category_id', 'unit_measure_id');
                    }]);
                }])
                ->first();
    }



    /*
     |--------------------------------------------------------------------------
     | PURCHASE APPROVE AND RECEIVE (METHOD)
     |--------------------------------------------------------------------------
    */
    public function stockAdjustmentsApprove(Request $request, $id)
    {
        if ($request->isMethod('post')) {
            try {
                DB::transaction(function () use ($request, $id) {

                    $stockAdjustment = StockAdjustment::with('stock_adjustment_details')->find($id);
                    $stockAdjustment->update([
                        'current_status' => 'Approved',
                        'approve_date'   => date('Y-m-d'),
                    ]);

                    foreach($stockAdjustment->stock_adjustment_details as $key => $stockAdjustmentDetail) {

                        if($stockAdjustmentDetail->stock_type == 'In'){
                            $this->dynamicLotManageService->stockInByLot($stockAdjustmentDetail, abs($stockAdjustmentDetail->quantity), $stockAdjustment->company_id, $stockAdjustment->warehouse_id,  $stockAdjustmentDetail->supplier_id, $stockAdjustmentDetail->product_id, $stockAdjustmentDetail->product_variation_id, $stockAdjustment->invoice_no, 0, $stockAdjustmentDetail->purchase_price, $stockAdjustmentDetail->lot, $stockAdjustmentDetail->expire_date);
                        }else{
                            $this->dynamicLotManageService->stockOutByLot($stockAdjustmentDetail, abs($stockAdjustmentDetail->quantity), $stockAdjustment->company_id, $stockAdjustment->warehouse_id,  $stockAdjustmentDetail->supplier_id, $stockAdjustmentDetail->product_id, $stockAdjustmentDetail->product_variation_id, $stockAdjustment->invoice_no, 0, $stockAdjustmentDetail->purchase_price, $stockAdjustmentDetail->lot, $stockAdjustmentDetail->expire_date);
                        }

                    }

                });

            } catch(\Exception $ex) {
                return redirect()->back()->withError($ex->getMessage());
            }

            return redirect()->route('inv.stock-adjustments.index')->withMessage('Stock has been adjusted successfully');

        } else {

            $data['warehouses']     = Warehouse::when(auth()->user()->id != 1, function($q){
                                        $q->whereIn('id', warehouse_access());
                                    })->pluck('name', 'id');

            $data['stockAdjustment'] = StockAdjustment::with('stock_adjustment_details')->find($id);

            return view('stock-adjustment.approve', $data);
        }
    }





    /*
     |--------------------------------------------------------------------------
     | CANCEL METHOD
     |--------------------------------------------------------------------------
    */
    public function stockAdjustmentCancel($id){

        try {

            $this->service->cancelAdjustment($id);

        } catch (\Throwable $th) {
            return redirect()->back()->withMessage($th->getMessage());
        }

        return redirect()->back()->withMessage('Stock Adjustment Cancelled!');

    }










    /*
     |--------------------------------------------------------------------------
     | DELETE/DESTORY METHOD
     |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $this->service->deleteAdjustment($id);
        });
        return redirect()->back();

    }

    /*
     |--------------------------------------------------------------------------
     | DELETE/DESTORY METHOD
     |--------------------------------------------------------------------------
    */
    public function delete($id)
    {
        DB::transaction(function () use ($id) {
            $this->service->deleteAdjustment($id);
        });
        return redirect()->back()->withSuccess('Adjustment Delete Successfully!');;

    }


}
