<?php

namespace Module\GeneralStore\Controllers;

use App\Exports\GeneralStore\ExportItemDetails;
use App\Http\Controllers\Controller;
use Module\GeneralStore\Models\Item;
use Module\GeneralStore\Models\Stock;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Excel;
use Module\GeneralStore\Services\Export\GSExportExcel;

class ExportGsExcelController extends Controller
{
    public function export(Request $request)
    {
        return Excel::download((new GSExportExcel($request)), Carbon::parse(now())->format('Y_m_d_') . $request->model . '.xlsx');
    }

    public function exportItemDetails(Request $request)
    {
        $item_stock_details = [];
        $selected_item = [];
        $opening_stock = 0;
        $opening_rate = 0;

        if ($request->filled('item_id')) {
            $selected_item = Item::where('id', $request->item_id)->orWhere('name', $request->item_id)->first();
          
            $query         = Stock::query();
            $query = $query->where('item_id', $selected_item->id);

            if ($request->from_date) {
                $query = $query->whereDate('date', '>=', date($request->from_date));
            }
            if ($request->to_date != null) {
                $query = $query->whereDate('date', '<=', date($request->to_date));
            } else {
                $query = $query->whereDate('date', '<=', Carbon::tomorrow());
            }

            $item_stock_details = $query->get();

            if ($request->from_date) {
                $from_date          = $request->from_date;
                $data = Stock::where('item_id', $selected_item->id)->whereDate('date', '<', Carbon::parse($from_date)->format('Y-m-d'))->get();

                $dabit_rate = $data->avg('debit_rate');
                $credit_rate = $data->avg('credit_rate');
                $dabit_qty = $data->sum('debit_qty');
                $credit_qty = $data->sum('credit_qty');

                $debit_amount = $data->sum(function ($t) {
                    return $t->debit_qty * $t->debit_rate;
                });
                $credit_amount = $data->sum(function ($t) {
                    return $t->credit_qty * $t->credit_rate;
                });
                $opening_amount = $credit_amount - $debit_amount + ($selected_item->opening_stock * $selected_item->opening_rate);
                $opening_stock = $credit_qty - $dabit_qty  + $selected_item->opening_stock;
                if ($opening_stock != 0) {
                    $opening_rate = $opening_amount / $opening_stock;
                } else {
                    $opening_rate = 0;
                }
            } else {
                $from_date = "2015-11-10";
                $opening_stock      = $selected_item ? $selected_item->opening_balance : 0;
                $opening_rate       = $selected_item ? $selected_item->rate : 0;
            }
        }
        $data['item_stock_details'] = $item_stock_details;
        $data['selected_item']      = $selected_item;
        $data['opening_stock']      = $opening_stock;
        $data['opening_rate']       = $opening_rate;

        // return view('export.gs.item_details', $data);
        return Excel::download(new ExportItemDetails($data), Carbon::parse(now())->format('Y_m_d_') . 'item_details.xls');
    }
}
