<?php

namespace Module\Restaurant\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Module\Restaurant\Models\Sale;
use App\Http\Controllers\Controller;
use Module\Bar\Models\RstTableManage;
use Module\Bar\Models\Sale as ModelsSale;
use Module\Restaurant\Services\SaleService;
use Module\Bar\Services\SaleService as BarSaleService;
use Module\Hotel\Models\AccountType;
use Module\Hotel\Models\Guest;
use Module\Restaurant\Models\RstTableManage as ModelsRstTableManage;

class SaleController extends Controller
{


    private $service;
    private $barSaleService;


    /*
     |--------------------------------------------------------------------------
     | CONSTRUCTOR
     |--------------------------------------------------------------------------
    */
    public function __construct()
    {
        $this->service      = new SaleService;
        $this->barSaleService = new BarSaleService;
    }


    /*
     |--------------------------------------------------------------------------
     | INDEX METHOD
     |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {

        try {

            $sales   = Sale::searchByField('is_bar')
                        ->searchByField('invoice_no')
                        ->searchByField('date')
                        ->searchByField('payment_status')
                        ->with('items')
                        ->latest();

            $data['total_due']  = $sales->sum('due_amount');
            $data['sales']      = $sales->paginate(25);

            return response()->json([
                'status'    => 1,
                'message'   => 'Success',
                'data'      => $data,
            ]);

        } catch (\Throwable $th) {

            return response()->json([
                'status'    => 0,
                'message'   => 'Error',
                'data'      => $th->getMessage(),
            ]);

        }
    }




    /*
     |--------------------------------------------------------------------------
     | STORE METHOD
     |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {

        try {
            DB::transaction(function () {

                if (request('is_bar') == 1) {

                    $this->barSaleService->store();

                    $this->barSaleService->storeItem();

                }
                else{

                    $this->service->store();

                    $this->service->storeItem();

                }

            });

        } catch (\Throwable $th) {

            return response()->json([
                'status'    => 0,
                'message'   => 'Error',
                'data'      => $th->getMessage(),
                'lineNumber'=> $th->getLine(),

            ]);

        }

        return response()->json([
            'status'    => 1,
            'message'   => 'Success',
            'data'      => request('is_bar') == 1 ? $this->barSaleService->sale : $this->service->sale,
        ]);
    }






    /**
     * ---------------------------------------------------------------------
     * SHOE METHOD
     * ---------------------------------------------------------------------
     */

     public function show($id)
     {
        try {

            $sale = Sale::with('details.product.unit:id,name')->find($id);

        } catch (\Throwable $th) {
            return response()->json([
                'status'    => 0,
                'message'   => 'Error',
                'data'      => $th->getMessage(),
            ]);
        }
        return response()->json([
            'status'    => 1,
            'message'   => 'Success',
            'data'      => $sale,
        ]);
     }




    /**
     * ---------------------------------------------------------------------
     * EDIT BAR SALE METHOD
     * ---------------------------------------------------------------------
     */
     public function editBarSale($id)
     {
        try {

            $data['sale']           =  ModelsSale::query()
                                            ->where('is_bar', 1)
                                            ->with(['items' => function($query){
                                                $query->with(['product' => function($query){
                                                    $query->querySum('stocks', 'total_quantity', 'available_quantity');
                                                }]);
                                            }])
                                            ->find($id);

            if ($data['sale'] != null)
            {
                $data['account_types']  = AccountType::pluck('name', 'id');
                $data['guests']         = Guest::query()->where('id', $data['sale']->hotel_guest_id)->get();
                $data['tables']         = RstTableManage::query()->get(['id','name','table_no']);
            }
            else{
                return response()->json([
                    'status'    => 0,
                    'message'   => 'Error',
                    'data'      => 'Sale Not Found!',
                ]);
            }

        } catch (\Throwable $th) {
            return response()->json([
                'status'    => 0,
                'message'   => 'Error',
                'data'      => $th->getMessage(),
            ]);
        }
        return response()->json([
            'status'    => 1,
            'message'   => 'Success',
            'data'      => $data,
        ]);
     }






    /*
     |--------------------------------------------------------------------------
     | STORE METHOD
     |--------------------------------------------------------------------------
    */
    public function updateBarSale(Request $request)
    {

        try {
            DB::transaction(function () use($request){

                $sale = ModelsSale::find($request->id);

                if ($sale != null)
                {
                    $this->barSaleService->update($request->id);

                    $this->barSaleService->updateSaleItem();
                }
                else{
                    return response()->json([
                        'status'    => 0,
                        'message'   => 'Error',
                        'data'      => 'Sale Not Found!'
                    ]);
                }

            });

        } catch (\Throwable $th) {

            return response()->json([
                'status'    => 0,
                'message'   => 'Error',
                'data'      => $th->getMessage(),
                'lineNumber'=> $th->getLine(),
            ]);

        }

        return response()->json([
            'status'    => 1,
            'message'   => 'Success',
            'data'      => $this->barSaleService->sale
        ]);
    }









    /**
     * ---------------------------------------------------------------------
     * EDIT RESTAURANT SALE METHOD
     * ---------------------------------------------------------------------
     */
     public function editRestaurantSale($id)
     {
        try {

            $data['sale']           =  Sale::query()
                                            ->where('is_bar', 0)
                                            ->with(['items' => function($query){
                                                $query->with(['product' => function($query){
                                                    $query->querySum('stocks', 'total_quantity', 'available_quantity');
                                                }]);
                                            }])
                                            ->find($id);

            if ($data['sale'] != null)
            {
                $data['account_types']  = AccountType::pluck('name', 'id');
                $data['guests']         = Guest::query()->where('id', $data['sale']->hotel_guest_id)->get();
                $data['tables']         = ModelsRstTableManage::query()->get(['id','name','table_no']);
            }
            else{
                return response()->json([
                    'status'    => 0,
                    'message'   => 'Error',
                    'data'      => 'Sale Not Found!',
                ]);
            }

        } catch (\Throwable $th) {
            return response()->json([
                'status'    => 0,
                'message'   => 'Error',
                'data'      => $th->getMessage(),
            ]);
        }
        return response()->json([
            'status'    => 1,
            'message'   => 'Success',
            'data'      => $data,
        ]);
     }




    /*
     |--------------------------------------------------------------------------
     | STORE METHOD
     |--------------------------------------------------------------------------
    */
    public function updateRestaurantSale(Request $request)
    {

        try {
            DB::transaction(function () use($request){

                $sale = Sale::find($request->id);

                if ($sale != null) {

                    $this->service->update($request->id);

                    $this->service->updateSaleItem();

                }
                else{
                    return response()->json([
                        'status'    => 0,
                        'message'   => 'Error',
                        'data'      => 'Sale Not Found!'
                    ]);
                }


            });

        } catch (\Throwable $th) {

            return response()->json([
                'status'    => 0,
                'message'   => 'Error',
                'data'      => $th->getMessage(),
                'lineNumber'=> $th->getLine(),
            ]);

        }

        return response()->json([
            'status'    => 1,
            'message'   => 'Success',
            'data'      => $this->service->sale
        ]);
    }





}
