<?php

namespace Module\GeneralStore\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Module\GeneralStore\Models\GoodsRequisition;
use Module\GeneralStore\Models\Item;
use App\Models\Gs\ItemStock;
use Module\GeneralStore\Models\ItemUnit;
use Module\GeneralStore\Models\PurchaseReceive;
use Module\GeneralStore\Models\Stock;
use Module\GeneralStore\Models\StockTracking;
use App\Models\SystemSetting;
use App\Models\User;
use App\Traits\CheckPermission;
use Carbon\Carbon;
use Illuminate\Http\Request;

class InventoryReportController extends Controller
{
    use CheckPermission;
    // item ledger
    public function items_stock(Request $request)
    {
        $this->hasAccess("gs.reports.item.ledger");   // check permission

        $data['items']       = Item::items()->orderBy('name')->when($request->filled('company_id'), function ($q) use($request) {
            $q->where('company_id', $request->company_id);
        })->pluck('name', 'id');

        $data['companies']   = Company::userCompanies();

        $data['units']   = ItemUnit::orderBy('name', 'asc')->pluck('name', 'id');

        $data['item_stocks'] = Item::with('item_unit');

        if ($request->filled('item_id')) {
            $data['item_stocks']->where('id', $request->item_id);
        }
        if ($request->filled('from_date')) {
            $data['item_stocks']->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $data['item_stocks']->whereDate('created_at', '<=', Carbon::parse($request->to_date));
        }

        if ($request->filled('company_id')) {
            $data['item_stocks']->where('company_id', $request->company_id);
        }

        if ($request->filled('unit_id')) {
            $data['item_stocks']->where('item_unit_id', $request->unit_id);
        }

        if ($request->filled('item_type')) {
            $data['item_stocks']->where('current_stock', '<=', 0);
        }

        $data['item_stocks'] = $data['item_stocks']->orderBy('name')->paginate(30);

        return view('reports.stock-in-hand', $data);
    }

    public function getCompanyItems(Request $request)
    {
        $items = Item::items()->orderBy('name')->select('name', 'id')->where('company_id', $request->company_id)->get();

        return response()->json($items);
    }

    // item details
    public function item_details(Request $request)
    {
        $this->hasAccess("gs.reports.item.details");   // check permission
        $companies = Company::userCompanies();

        if ($request->filled('item_id')) {

            $query         = Stock::query();
            $items         = Item::where('company_id', $request->company_id)->pluck('name', 'id');
            $selected_item = Item::where('name', $request->item_id)->where('company_id', $request->company_id)->first();
            $query         = $query->where('item_id', $selected_item->id);


            if ($request->from_date) {
                $query = $query->whereDate('date', '>=', date($request->from_date));
            }
            if ($request->to_date != null) {
                $query = $query->whereDate('date', '<=', date($request->to_date));
            } else {
                $query = $query->whereDate('date', '<=', Carbon::tomorrow());
            }

//            return $selected_item->current_stock;
            $item_stock_details = $query->paginate(2000);

            if ($request->filled('from_date')) {
                $data = Stock::orderBy('date')->where('item_id', $selected_item->id)->whereDate('date', '<', $request->from_date)->get();

                // get quantity
                $opening_qty = $selected_item->opening_balance;
                $dabit_qty   = $data->sum('debit_qty');
                $credit_qty  = $data->sum('credit_qty');

                // get amount
                $openning_amount  = $selected_item->opening_balance * $selected_item->rate;
                $credit_amount = $data->sum(function ($product) {
                    return $product->credit_qty * $product->credit_rate;
                });
                $debit_amount = $data->sum(function ($product) {
                    return $product->debit_qty * $product->debit_rate;
                });


                $opening_stock  = $credit_qty + $opening_qty - $dabit_qty;
                $total_amount   = $openning_amount + $credit_amount - $debit_amount;
                $opening_rate   = $selected_item->rate;

                if($opening_stock != 0) {
                    $opening_rate = $total_amount / $opening_stock;
                }
            } else {
                $opening_stock      = $selected_item ? $selected_item->opening_balance : 0;
                $opening_rate       = $selected_item ? $selected_item->rate : 0;
            }

            return view('reports.item-ledger', compact('item_stock_details', 'companies', 'items', 'selected_item', 'opening_stock', 'opening_rate'));
        } else {
            return view('reports.item-ledger', compact('companies'));
        }
    }




    // item details
    public function weaklyMovementIssue(Request $request)
    {

        $this->hasAccess("create.requisitions.gin.list");   // check permission

        $data = [];
        $data['companies']   = Company::userCompanies();
        $data['departments'] = [];

        $data['goods_requisitions'] = GoodsRequisition::orderByDesc('issue_date')->with('company', 'department', 'goods_requisition_details.item.item_unit', 'goods_requisition_details.item', 'updated_user')
            ->orderBy('id')
            // ->whereIn('department_id', $data['departments']->keys())
            ->whereIn('company_id', $data['companies']->keys());

        if ($request->filled('company_id')) {
            $data['goods_requisitions']->where('company_id', $request->company_id);
        }

        if ($request->filled('department_id')) {
            $data['goods_requisitions']->where('department_id', $request->department_id);
        }

        if ($request->filled('reference')) {
            $data['goods_requisitions']->where('goods_requisition_reference', $request->reference);
        }

        if ($request->filled('from_date')) {
            $data['goods_requisitions']->whereBetween('issue_date', [Carbon::parse($request->from_date)->subDays(7), Carbon::parse($request->from_date)]);
        } else {
            $data['goods_requisitions']->whereBetween('issue_date', [Carbon::parse(today())->subDays(7), Carbon::parse(today())]);
        }

        $data['goods_requisitions'] = $data['goods_requisitions']->where('is_approved', 1)->orderByDesc('id')->paginate(30);


        $data['requisition_from_receives'][][] = [];
        $data['requisition_from_items'][][]   = [];

        foreach ($data['goods_requisitions'] as $i => $goods_requisition) {
            foreach ($goods_requisition->goods_requisition_details as $key => $detail) {
                $item_id = $detail->item_id;
                $data['requisition_from_receives'][$i][$key] = null;
                $data['requisition_from_items'][$i][$key] = false;


                $trackings = StockTracking::where('goods_requisition_detail_id', $detail->id)->get();

                $data['requisition_from_receives'][$i][$key] = PurchaseReceive::whereHas('purchase_receive_details', function($q) use ($item_id, $trackings) {
                    $q->where('item_id', $item_id)->whereIn('id', $trackings->where('type', 'receive')->pluck('tracking_id'));
                })->with(['purchase_receive_details' => function($q) use ($item_id,$trackings) {
                    $q->where('item_id', $item_id)->whereIn('id', $trackings->where('type', 'receive')->pluck('tracking_id'));
                }])->get();

                if ($trackings->where('type', 'item')->first()) {
                    $data['requisition_from_items'][$i][$key] = Item::where('id', $item_id)->select('rate', 'id')->first();
                }
            }
        }
        $data['systemSetting'] = SystemSetting::where('key', 'general_store_reference_no_change')->first();

//        return $data;
        return view('reports.weakly_movement_issue', $data);

    }



    public function print_item_details($item_id, $company_id, $from_date, $to_date)
    {

        $item_id    = ltrim($item_id, $item_id[0]);
        $from_date  = ltrim($from_date, $from_date[0]);
        $to_date    = ltrim($to_date, $to_date[0]);

        $companies = User::userCompanies();

        if ($item_id != "") {

            $selected_item = Item::where('id', $item_id)->first();

            $query  = Stock::query();

            $query = $query->where('item_id', $item_id);

            if ($from_date) {
                $query = $query->where('created_at', '>=', date($from_date) . ' 00:00:00');
            }
            if ($to_date != "") {
                $query = $query->where('created_at', '<=', date($to_date) . ' 23:00:00');
            } else {
                $query = $query->where('created_at', '<=', Carbon::tomorrow());
            }

            $item_stock_details = $query->paginate(30);

            if ($from_date != "") {
                $item_stock_details = Stock::where('item_id', $selected_item->id)->whereDate('date', '<', Carbon::parse($from_date)->format('Y-m-d'))->get();
                $opening_stock      = $item_stock_details->sum('credit_qty') - $item_stock_details->sum('debit_qty') + $selected_item->opening_balance;
                $opening_rate       = $selected_item->rate;
            } else {
                $from_date = "2015-11-10";
                $opening_stock      = $selected_item ? $selected_item->opening_balance : 0;
                $opening_rate       = $selected_item ? $selected_item->rate : 0;
            }

            return view('gs/reports/print_item_details', compact('item_stock_details', 'selected_item', 'opening_stock', 'opening_rate'));
        } else {
            return view('gs/reports/item_details', compact('companies'));
        }
    }

    public function print_item_stock($item_id, $company_id, $from_date, $to_date)
    {
        $item_id    = ltrim($item_id, $item_id[0]);
        $company_id = ltrim($company_id, $company_id[0]);
        $from_date  = ltrim($from_date, $from_date[0]);
        $to_date    = ltrim($to_date, $to_date[0]);

        $items     = Item::pluck('name', 'id');
        $companies = Company::userCompanies();
        $company   = "";

        if ($item_id != "" || $company_id != "" || $from_date != "" || $to_date != "") {
            $query = ItemStock::query();

            if ($from_date != "") {
                $query = $query->where('created_at', '>=', date($from_date) . ' 00:00:00');
            }
            if ($to_date != "") {
                $query = $query->where('created_at', '<=', date($to_date) . ' 23:00:00');
            }

            if ($item_id != "") {
                $query = $query->where('item_id', $item_id);
            }
            if ($company_id != "") {
                $query = $query->where('company_id', $company_id);
            }
            $item_stocks = $query->get();


            return view('gs/reports/print_items_stock', compact('company', 'item_stocks'));
        } else {
            return view('gs/reports/items_stock', compact('companies', 'items'));
        }
    }
}
