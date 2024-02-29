<?php

namespace Module\Restaurant\Controllers\Report;

use Module\Restaurant\Models\Sale;
use Illuminate\Http\Request;
use App\Services\ExportService;
use App\Http\Controllers\Controller;
use Module\Hotel\Models\HotelTransection;

class CashFlowController extends Controller
{





    /*
     |--------------------------------------------------------------------------
     | INDEX METHOD
     |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {

        $data['cashFlows'] = Sale::query()->has('RstTransaction')
            ->searchByField('invoice_no')
            ->when($request->filled('from_date') && $request->filled('to_date'), function ($q) use ($request) {
                $q->where('date', '>=', $request->from_date)
                    ->where('date', '<=', $request->to_date);
            })
            ->with('RstTransaction', 'user:id,name')->paginate(25);

        $data['cashFlows'] = HotelTransection::whereIn('source_type', ['Restaurant Sale', 'Restaurant Sale Return'])->dateFilter()->latest()->paginate(25);

        if (request('export_type')) {

            $file_path = 'rst/reports/cash-flow/export/';
            return (new ExportService())->exportData($data, $file_path, 'Sale Report');
        }
        return view('rst/reports/cash-flow/index', $data);
    }





}
