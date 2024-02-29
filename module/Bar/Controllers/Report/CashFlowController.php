<?php

namespace Module\Bar\Controllers\Report;

use Module\Bar\Models\Sale;
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

        $data['cashFlows'] = Sale::query()->has('RstTransactions')
            ->searchByField('invoice_no')
            ->when($request->filled('from_date') && $request->filled('to_date'), function ($q) use ($request) {
                $q->where('date', '>=', $request->from_date)
                    ->where('date', '<=', $request->to_date);
            })
            ->with('RstTransactions', 'user:id,name')->paginate(25);

        $data['cashFlows'] = HotelTransection::whereIn('source_type', ['Bar Sale', 'Bar Sale Return'])->dateFilter()->latest()->paginate(25);

        if (request('export_type')) {

            $file_path = 'bar/reports/cash-flow/export/';
            return (new ExportService())->exportData($data, $file_path, 'Sale Report');
        }
        return view('bar/reports/cash-flow/index', $data);
    }













    /*
     |--------------------------------------------------------------------------
     | CREATE METHOD
     |--------------------------------------------------------------------------
    */
    public function create()
    {
        # code...
    }













    /*
     |--------------------------------------------------------------------------
     | STORE METHOD
     |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        # code...
    }













    /*
     |--------------------------------------------------------------------------
     | SHOW METHOD
     |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        # code...
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
        # code...
    }
}
