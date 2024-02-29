<?php

namespace Module\Account\Controllers;

use App\Models\Company;
use App\Traits\CheckPermission;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Module\Account\Models\Product;
use Module\Account\Models\ProductStock;
use Module\Account\Models\ProductStockDetail;
use Module\Account\Models\Unit;

class InventoryReportController extends Controller
{
    use CheckPermission;

    public function getStockInHand(Request $request)
    {

        $data['companies']      = Company::pluck('name', 'id');
        // $data['factories']      = Factory::where('company_id', $request->company_id)->pluck('name', 'id');
        $data['units']          = Unit::pluck('name', 'id');
        $data['products']       = Product::whereHas('product_stock')->accountProduct()->pluck('name', 'id');

        
        $data['itemStocks']     = ProductStock::when($request->filled('product_id'), function($q) use($request) {
                                                    $q->where('product_id', $request->product_id);
                                                })
                                                ->where('company_id', $request->company_id)
                                                ->when($request->filled('factory_id'), function($q) use($request) {
                                                    $q->where('factory_id', $request->factory_id);
                                                })
                                                ->when($request->filled('unit_id'), function($q) use($request) {
                                                    $q->whereHas('product', function($qr) use($request) {
                                                       $qr->where('unit_id', $request->unit_id);
                                                    });
                                                })
                                                ->whereIn('product_id', $data['products']->keys())
                                                ->paginate(30);


        return view('reports.inventory-reports.stock-in-hand', $data);
    }













    public function getItemLedger(Request $request)
    {
        $companies = Company::userCompanies();
        // $factories      = Factory::where('company_id', $request->company_id)->pluck('name', 'id');
       
        $items     = Product::where('name', '!=', null)->accountProduct()->pluck('name', 'id');

        if ($request->filled('product_id')) {

            $query         = ProductStockDetail::query();
            $items         = Product::where('name', '!=', null)->accountProduct()->where('company_id', $request->company_id)->pluck('name', 'id');
            $selected_item = Product::where('id', $request->product_id)->accountProduct()->where('company_id', $request->company_id)->first();

            $query         = $query->where('product_id', $selected_item->id)
                                ->when($request->filled('company_id'), function($q) use($request) {
                                    $q->where('company_id', $request->company_id);
                                })

                                // ->when($request->filled('factory_id'), function($q) use($request) {
                                //     $q->where('factory_id', $request->factory_id);
                                // })
                                ;


            if ($request->from_date) {
                $query = $query->whereDate('date', '>=', date($request->from_date));
            }
            if ($request->to_date != null) {
                $query = $query->whereDate('date', '<=', date($request->to_date));
            } else {
                $query = $query->whereDate('date', '<=', Carbon::tomorrow());
            }

            $item_stock_details = $query->paginate(2000);

            if ($request->filled('from_date')) {
                $data = ProductStockDetail::orderBy('date')->where('product_id', $selected_item->id)->whereDate('date', '<', $request->from_date)->when($request->filled('company_id'), function($q) use($request) {
                    $q->where('company_id', $request->company_id);
                })

                ->when($request->filled('factory_id'), function($q) use($request) {
                    $q->where('factory_id', $request->factory_id);
                })
                ->get();

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

            return view('reports.inventory-reports.item-ledger', compact('item_stock_details', 'companies', 'items', 'selected_item', 'opening_stock', 'opening_rate'));

        } else {

            return view('reports.inventory-reports.item-ledger', compact('companies', 'items'));

        }
    }
}
