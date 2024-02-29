<?php

namespace Module\Bar\Controllers\Report;

use Illuminate\Http\Request;
use Module\Bar\Models\Product;
use App\Services\ExportService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Module\Hotel\Models\HotelTransection;
use Module\Hotel\Models\HotelTransactionLedger;

class ReportController extends Controller
{

    private $service;



    /*
     |--------------------------------------------------------------------------
     | CONSTRUCTOR
     |--------------------------------------------------------------------------
    */
    public function __construct()
    {
        $this->service = new ExportService();
    }




    /*
     |--------------------------------------------------------------------------
     | INDEX METHOD
     |--------------------------------------------------------------------------
    */
    public function todayReport(Request $request)
    {

        $date                           = $request->date;
        $data['transactions']           = HotelTransection::query()
                                        ->when(!$request->filled('from_date'), function($query){
                                            $query->where('date', today_from_system());
                                        })
                                        ->dateFilter()
                                        ->where('source_type', 'Bar Sale')->get();

        $data['paginate']   = 1;

        if(request('export_type')){

            $data['paginate']   = 0;
            return $this->service->exportData($data, 'bar/reports/today-activities/export/', 'Today Report '. date('Y_m_d'));
        }

        return view('bar/reports/today-activities/index', $data);
    }







    //-----------------------------------------------//
    //              BAR INVENTORY METHOD             //
    //-----------------------------------------------//
    public function inventory()
    {

        $data['products']   = Product::searchByField('id')
                                    ->when(request()->filled('from_date') && request()->filled('to_date'), function($query){
                                        $query->whereHas('saleItems', function($q){
                                            $q->with('sale', function($q){
                                                $q->whereBetween('date', [request('from_date'), request('to_date')]);
                                            });
                                        });
                                    })
                                    ->with('category:id,name')
                                    ->withCount(['stocks as bar_sold_qty'=> function($q){
                                        $q->select(DB::raw('SUM(sold_quantity)'))->where('is_bar', 1);
                                    }])
                                    ->withCount(['stocks as rst_sold_qty'=> function($q){
                                        $q->select(DB::raw('SUM(sold_quantity)'))->where('is_bar', 0);
                                    }])
                                    ->querySum('stocks', 'available_quantity',  'available_quantity')
                                    ->querySum('stocks', 'sold_quantity',       'sold_quantity')
                                    ->querySum('stocks', 'return_quantity',     'return_quantity')
                                    ->querySum('stocks', 'purchased_quantity',  'purchased_quantity')
                                    ->querySum('stocks', 'opening_quantity',    'opening_quantity');

        $data['paginate']   = 1;

        if(request('export_type')){

            $data['products']   = $data['products']->get();
            $data['paginate']   = 0;

            return $this->service->exportData($data, 'bar/reports/inventory/export/', 'Inventory Report '. date('Y_m_d'));
        }

        $data['products']   = $data['products']->paginate(25);

        return view('bar/reports/inventory/index', $data);
    }




}
