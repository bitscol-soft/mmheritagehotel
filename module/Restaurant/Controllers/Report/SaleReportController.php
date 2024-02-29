<?php

namespace Module\Restaurant\Controllers\Report;

use Illuminate\Http\Request;
use App\Services\ExportService;
use Module\Restaurant\Models\Sale;
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
        $data['account_types']  = AccountType::whereHas('hotelTransactions')->pluck('name', 'id');

        $data['sales'] =  $data['sales'] =  Sale::query()->where('is_bar', 0)->searchByField('invoice_no')->searchByField('guest_name')->searchByField('date');
        if (request('export_type')) {

            $file_path = 'rst/reports/sales/export/';

            $data['sales'] = $data['sales']->get();
            return (new ExportService())->exportData($data, $file_path, 'Sale Report');
        }

        $data['sales'] = $data['sales']->paginate(25);

        return view('rst.reports.sales.index', $data);
    }



}
