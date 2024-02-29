<?php

namespace Module\GeneralStore\Controllers;

use App\Http\Controllers\Controller;
use App\Imports\ImportItemCSV;
use App\Models\Company;
use Module\GeneralStore\Models\GoodsRequisition;

use Module\GeneralStore\Models\ItemUnit;
use Module\GeneralStore\Models\Purchase;
use Module\GeneralStore\Models\PurchaseReceive;

use Module\GeneralStore\Models\StockTracking;
use App\Traits\CheckPermission;
use Illuminate\Http\Request;
use Excel;
use Module\GeneralStore\Models\Item;
use Module\GeneralStore\Models\Stock;

class ItemController extends Controller
{
    use CheckPermission;

    /**
     *  View    :   gs.items.index
     *  Task    :   RETURN LIST OF ITEMS OF THE COMPANIES THAT USER HAVE PERMISSION
     *  Url path:   /gs/items
     *  Url name:   items.index
     */
    public function index(Request $request)
    {
        $this->hasAccess("items.view");   // check permission
        $data['companies'] = Company::userCompanies();
        $data['item_ids']  = Item::whereIn('company_id', $data['companies']->keys())->pluck('name', 'id');
        $data['items']     = Item::with('company', 'item_unit', 'created_user', 'updated_user')
                            ->withCount(['purchase_detail', 'goods_requisition'])
                            ->orderByDesc('id');


        if ($request->filled('company_id')) {
            $data['items']->where('company_id', $request->company_id);
        }

        if ($request->filled('item_id')) {
            $data['items']->where('id', $request->item_id);
        }

        if ($request->filled('from_date')) {
            $data['items']->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $data['items']->whereDate('created_at', '<=', $request->to_date);
        }

        $data['items'] = $data['items']->paginate(30);
        return view('items.index', $data);
    }


    public function show(Item $item)
    {
        $this->hasAccess("items.view");   // check permission
        $data['companies'] = Company::userCompanies();
        $data['item_ids']  = Item::whereIn('company_id', $data['companies']->keys())->pluck('name', 'id');
        $data['items']     = Item::where('id', $item->id)->with('company', 'item_unit', 'created_user', 'updated_user')
            ->withCount(['purchase_detail', 'goods_requisition'])
            ->orderByDesc('id');

        $data['items'] = $data['items']->paginate(30);
        return view('items.index', $data);
    }


    /**
     *  View    :   gs.items.create
     *  Task    :   RETURN ITEM UNITS AND ACCESSIBLE COMPANIES TO THE FORM
     *  Url path:   /gs/items/create
     *  Url name:   items.create
     */
    public function create()
    {
        $this->hasAccess("items.create");   // check permission
        $data['companies']  = Company::userCompanies();
        $data['item_units'] = ItemUnit::pluck('name', 'id');
        return view('items.create', $data);
    }




    /**
     *  Task    :   STORE DATA TO ITEM TABLE FROM 'gs.items.create' VIEW
     *  Url path:   /gs/items/store
     *  Url name:   items.store
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'company_id'   => 'required|not_in:0',
            'item_unit_id' => 'required',
            'name'         => 'unique_with:items, name, company_id',
        ]);

        //  store data
        $created = Item::create([
            'company_id'         => $request->company_id,
            'item_unit_id'       => $request->item_unit_id,
            'name'               => $request->name,
            'opening_balance'    => (int)$request->opening_balance,
            'rate'               => $request->rate,
            'remaining_quantity' => (int)$request->opening_balance,
            'current_stock'      => (int)$request->opening_balance,
            'average_rate'       => $request->rate
        ]);

        if ($created) {
            return redirect()->route('items.index')->with('message', 'Item create successfully!');
        }
    }


    /**
     *  View    :   gs.items.edit
     *  Task    :   RETURN SELECTED ITEM, UNITS AND ACCESSIBLE COMPANIES
     *  Url path:   /gs/items/{id}/edit
     *  Url name:   items.edit
     */
    public function edit($id)
    {
        $this->hasAccess("items.edit");   // check permission
        $data['item']       = Item::where('id', $id)->withCount(['purchase_detail', 'goods_requisition'])->first();
        $data['companies']  = Company::userCompanies();
        $data['item_units'] = ItemUnit::orderBy('name')->pluck('name', 'id');
        return view('items.edit', $data);
    }


    /**
     *  Task    :   UPDATE EDITED DATA FROM THE 'gs.item.edit' VIEW
     *  Url path:   /gs/items/{id}/update
     *  Url name:   items.update
     */
    public function update(Request $request, Item $item)
    {
        $this->validate($request, [
            'item_unit_id' => 'required',
            'name'         => 'required',
        ]);


        $item->update([
            'company_id'         => $request->company_id,
            'item_unit_id'       => $request->item_unit_id,
            'name'               => $request->name,
            'opening_balance'    => (int)$request->opening_balance,
            'current_stock'      => $item->opening_balance != (int)$request->opening_balance ? (int)$request->opening_balance : $item->current_stock,
            'remaining_quantity' => $item->opening_balance != (int)$request->opening_balance ? (int)$request->opening_balance : $item->remaining_quantity,
            'rate'               => $request->rate,
            'average_rate'       => $request->rate
        ]);

        return redirect()->route('items.index')->with('message', 'Item update successfully!');
    }



    /**
     *  Task    :   DELETE ITEM FORM THE ITEM TABLE
     */
    public function destroy(Item $Item)
    {
        $this->hasAccess("items.delete");   // check permission
        $deleted = $Item->delete();
        if ($deleted) {
            return redirect()->back()->with('message', 'Item unit delete success!');
        }
    }




    /**
     *  Task    :  RETURN ITEM LIST FORM THE AJAX REQUEST
     *  Url Path:  ajax/items/get-item-list
     */
    public function getItemList(Request $request)
    {
        $data['items'] = Item::where('company_id', $request->id)->orderBy('name')->pluck('name', 'id');
        return $data;
    }



    /**
     *  Task    :  RETURN ITEM LIST FORM THE AJAX REQUEST
     *  Url Path:  ajax/items/get-item-list-by-type
     */
    public function getItemListByType(Request $request)
    {
        $data['items'] = Item::where('company_id', $request->company_id)->orderBy('name')->where('name', 'like', '%'.$request->name.'%')->pluck('name', 'id');
        return $data;
    }

    /**
     *  Task    :  RETURN ITEM DETAIL WHEN CHANGE ITEM
     *  Url Path:  ajax/get-item-details
     */

    public function getItemDetailsForPurchase(Request $request)
    {
        $item = Item::where('id', $request->id)->with('item_unit')->first();
        $data['current_stock'] = $item->current_stock;
        $data['item_unit']     = $item->item_unit->name;

//        return $data;
        $item_id = $request->id;
        $last_purchase_receive = PurchaseReceive::whereHas('purchase_receive_details', function ($q) use ($item_id) {
            $q->where('item_id', $item_id);
        })->with('purchase_receive_details')->first();
        if ($last_purchase_receive) {
            $data['receive_number'] = $last_purchase_receive->form_number;
            $data['last_receive'] = $last_purchase_receive->purchase_receive_details->where('item_id', $item_id)->first();
        }
        return $data;
    }

    public function getItemDetails(Request $request)
    {
        $item = Item::where('id', $request->id)->with('item_unit')->first();
        $data['current_stock'] = $item->current_stock;
        $data['item_unit']     = $item->item_unit->name;


        $data['receive_items']         = [];
        $data['requisition_number']    = [];
        $data['requisition_from_item'] = [];

        $item_id = $request->id;

        $data['receive_items']         = null;
        $data['requisition_number']    = null;
        $data['requisition_from_item'] = false;


        $last_requisition_from_stock = null;

        // check first any requisition in stock table
        $last_requisition_from_stock = Stock::where('item_id', $item_id)->where('type', 'Requisition Receive')->orderByDesc('source_number')->select('source_number', 'source_id', 'debit_rate')->first();


        if ($last_requisition_from_stock != null) {
            // find requisition that is exist in stock table
            $requisition = GoodsRequisition::orderByDesc('id')->where('issue_number', $last_requisition_from_stock->source_number)
                ->whereHas('goods_requisition_details', function($q) use ($item_id) {
                    $q->where('item_id', $item_id);
                })->with(['goods_requisition_details' => function($q) use ($item_id) {
                    $q->where('item_id', $item_id);
                }])->first();

            $data['requisition_number'] = $requisition;

            // get the requisition detail id from the goods requisition detail table
            $goods_requisition_details_id = $requisition->goods_requisition_details->where('item_id', $item_id)->first()->id;


            if ($goods_requisition_details_id != null) {
                $trackings = StockTracking::where('goods_requisition_detail_id', $goods_requisition_details_id)->get();

                $data['receive_items'] = PurchaseReceive::whereHas('purchase_receive_details', function($q) use ($item_id, $trackings) {
                    $q->where('item_id', $item_id)->whereIn('id', $trackings->where('type', 'receive')->pluck('tracking_id'));
                })->with(['purchase_receive_details' => function($q) use ($item_id,$trackings) {
                    $q->where('item_id', $item_id)->whereIn('id', $trackings->where('type', 'receive')->pluck('tracking_id'));
                }])->get();
                $data['receive_items_quantity'] = $trackings->where('type', 'receive')->pluck('quantity');


                if($trackings->where('type', 'item')->first()) {
                    $issue_quantity = $trackings->where('type', 'item')->first()->quantity;
                    $abc = Item::where('id', $item_id)->select('rate', 'id')->first();
                    $data['requisition_from_item'] = collect($abc)->merge(['issue_quantity'=>$issue_quantity]);
                } else {
                    $data['requisition_from_item'] = [];
                }
            } else {
                $data['receive_items'] = [];
            }
        } else {
            $data['receive_items'] = [];
        }


        return response()->json($data);
    }

    public function getItemDetailsForApprove (Request $request)
    {
        $item = Item::where('id', $request->id)->with('item_unit')->first();
        $data['current_stock'] = $item->current_stock;
        $data['item_unit']     = $item->item_unit->name;

            $item_id = $request->id;
            $last_purchase = Purchase::whereIsApproved(1)->orderByDesc('purchase_date')->whereHas('purchase_details', function($q) use ($item_id) {
                $q->where('item_id', $item_id);
            })->first();

            if ($last_purchase) {
                $data['last_purchase'] = $last_purchase;
            } else {
                $data['last_purchase'] = '';
            }

        return response()->json($data);
    }


    // item upload show
    public function itemUploadShow()
    {
        $this->hasAccess("items.upload");   // check permission
        return view('items.upload');
    }

    // item upload store
    public function itemUpload(Request $request)
    {
        if ($request->file('item_csv_file')) {
            try {
                $data = Excel::import(new ImportItemCSV(), $request->file('item_csv_file'));
            } catch (Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
            return redirect()->route('items.index')->with('message', 'File Uploaded Successfully');
        } else {
            return redirect()->back();
        }
    }


    // item export as csv
    public function export()
    {
        $this->hasAccess("items.index");   // check permission
        return Excel::download(new ExportItemCSV(), 'Item-list.csv');
    }
}
