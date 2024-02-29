<?php

namespace Module\Restaurant\Controllers\Report;

use Illuminate\Http\Request;
use Module\Bar\Models\ProductCategory;
use Module\Hotel\Models\Rooms;
use App\Services\ExportService;
use Module\Hotel\Models\Booking;
use Illuminate\Support\Facades\DB;
use Module\Restaurant\Models\Sale;
use App\Http\Controllers\Controller;
use Module\Restaurant\Models\Product;
use Module\Hotel\Models\HotelTransection;
use Module\Hotel\Models\HotelTransactionLedger;
use Module\Restaurant\Models\ProductLedger;
use Module\Hotel\Models\AccountType;

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
                                        ->whereIn('source_type', ['Restaurant Sale', 'Resturent Sale'])
                                        ->get();

        $data['paginate']   = 1;

        if(request('export_type')){

            $data['paginate']   = 0;
            return $this->service->exportData($data, 'reports/today-activities/export/', 'Today Report');
        }

        return view('reports/today-activities/index', $data);
    }







    /*
     |--------------------------------------------------------------------------
     | INVENTORY METHOD
     |--------------------------------------------------------------------------
    */
    public function inventory(Request $request)
    {

        $data['categorys'] = ProductCategory::all();
        $data['products']   = Product::searchByField('id')
                                    ->when(request()->filled('from_date') && request()->filled('to_date'), function($query) {
                                        $fromDate = date('Y-m-d', strtotime(request('from_date')));
                                        $toDate = date('Y-m-d', strtotime(request('to_date')));

                                        $query->whereHas('saleItems.sale', function($q) use ($fromDate, $toDate) {
                                            $q->whereBetween('date', [$fromDate, $toDate]);
                                        });
                                    })
                                    ->with('category:id,name')
                                    ->when(request()->filled('category_id'), function ($query) {
                                        $query->whereHas('category', function ($q) {
                                            $q->where('id', request('category_id'));
                                        });
                                    })
                                    ->withCount(['stocks as bar_sold_qty'=> function($q){
                                        $q->select(DB::raw('SUM(sold_quantity)'))->where('is_bar', 1);
                                    }])
                                    ->withCount(['stocks as rst_sold_qty'=> function($q){
                                        $q->select(DB::raw('SUM(sold_quantity)'))->where('is_bar', 0);
                                    }])
                                    ->withSum('stocks as available_quantity', 'available_quantity')
                                    ->withSum('stocks as sold_quantity', 'sold_quantity')
                                    ->withSum('stocks as return_quantity', 'return_quantity')
                                    ->withSum('stocks as purchased_quantity', 'purchased_quantity')
                                    ->withSum('stocks as opening_quantity', 'opening_quantity');
            $data['paginate']   = 1;

            if(request('export_type')){

                $data['products']   = $data['products']->get();
                $data['paginate']   = 0;

                return $this->service->exportData($data, 'reports/inventory/export/', 'Inventory Report '. date('Y_m_d'));
            }

            $data['products']   = $data['products']->paginate(25);

        return view('reports/inventory/index', $data);
    }






    /*
     |--------------------------------------------------------------------------
     | INVENTORY LEDGER METHOD
     |--------------------------------------------------------------------------
    */
    public function inventoryLedger()
    {

        $data['categorys'] = ProductCategory::all();
        $data['product_ledgers']   = Product::searchByField('id')
                                    ->with('category:id,name')
                                    ->when(request()->filled('category_id'), function ($query) {
                                        $query->whereHas('category', function ($q) {
                                            $q->where('id', request('category_id'));
                                        });
                                    })
                                    ->whereHas('stock_ledgers', function ($q) {
                                        $q->when(request('from_date') && request('to_date'), function ($query) {
                                            if (setting('report_with_night_audit') == 1) {
                                                $query->dateFilter('audit_date');
                                            } else {
                                                $query->dateFilter('date');
                                            }
                                        });
                                    })
                                    // ->whereHas('stock_ledgers', fn($q) => request('from_date') && request('to_date') ? $q->dateFilter('audit_date') : $q->where('audit_date', date('Y-m-d')))
                                    ->withSum(['stock_ledgers as total_bar_in'=> fn($q) => request('from_date') && request('to_date') ? $q->dateFilter('audit_date')->where('sourceable_type', 'Bar Sale') : $q->where('audit_date', date('Y-m-d'))->where('sourceable_type', 'Bar Sale')], 'in')
                                    ->withSum(['stock_ledgers as total_bar_out'=> fn($q) => request('from_date') && request('to_date') ? $q->dateFilter('audit_date')->where('sourceable_type', 'Bar Sale') : $q->where('audit_date', date('Y-m-d'))->where('sourceable_type', 'Bar Sale')], 'out')
                                    ->withSum(['stock_ledgers as total_rst_in'=> fn($q) => request('from_date') && request('to_date') ? $q->dateFilter('audit_date')->where('sourceable_type', 'Restaurant Sale') : $q->where('audit_date', date('Y-m-d'))->where('sourceable_type', 'Restaurant Sale')], 'in')
                                    ->withSum(['stock_ledgers as total_rst_out'=> fn($q) => request('from_date') && request('to_date') ? $q->dateFilter('audit_date')->where('sourceable_type', 'Restaurant Sale') : $q->where('audit_date', date('Y-m-d'))->where('sourceable_type', 'Restaurant Sale')], 'out');



        $data['paginate']   = 1;

        if(request('export_type')){

            $data['product_ledgers']   = $data['product_ledgers']->get();
            $data['paginate']   = 0;

            return $this->service->exportData($data, 'reports/inventory-ledger/export/', 'Inventory Report '. date('Y_m_d'));
        }

        $data['product_ledgers']   = $data['product_ledgers']->paginate(25);

        return view('reports/inventory-ledger/index', $data);
    }


}
