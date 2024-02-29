<?php

namespace Module\Bar\Controllers\Report;

use Module\Bar\Models\Sale;
use Illuminate\Http\Request;
use App\Services\ExportService;
use App\Http\Controllers\Controller;
use Module\Hotel\Models\AccountType;

class SaleReportController extends Controller
{






    /*
     |--------------------------------------------------------------------------
     | INDEX METHOD
     |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $data['sales'] =  Sale::query()->searchByField('invoice_no')->dateFilter();
        $data['account_types']  = AccountType::whereHas('hotelTransactions')->pluck('name', 'id');

        if (request('export_type')) {

            $file_path = 'bar/reports/sales/export/';
            $data['sales'] = $data['sales']->get();

            return (new ExportService())->exportData($data, $file_path, 'Sale Report');
        }

        $data['sales'] = $data['sales']->paginate(25);

        return view('bar.reports.sales.index', $data);
    }



}
