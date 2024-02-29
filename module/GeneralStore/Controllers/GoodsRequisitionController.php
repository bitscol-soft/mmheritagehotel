<?php

namespace Module\GeneralStore\Controllers;


use Carbon\Carbon;
use App\Models\Company;
use App\Traits\FormNumber;
use Illuminate\Http\Request;
use App\Models\SystemSetting;
use App\Traits\CheckPermission;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Module\GeneralStore\Models\Item;
use Module\GeneralStore\Models\Stock;
use Module\GeneralStore\Models\StockTracking;
use Module\GeneralStore\Models\PurchaseReceive;
use Module\GeneralStore\Models\GoodsRequisition;
use Module\GeneralStore\Models\GoodsRequisitionDetails;
use Module\GeneralStore\Models\PurchaseReceiveDetails;

class GoodsRequisitionController extends Controller
{
    use FormNumber, CheckPermission;


    public function printGin(GoodsRequisition $goodsRequisition)
    {
        return view('goods_requisitions.print-gin-details', compact('goodsRequisition'));
    }
    /**
     *  View    :   gs.goods_requisitions.index
     *  Task    :   RETURN LIST OF GOODS REQUISITIONS OF THE COMPANIES THAT USER HAVE PERMISSION
     *  Url path:   /gs/goods-requisitions
     *  Url name:   goods-requisitions.index
     */
    public function index(Request $request)
    {
        $companies = Company::userCompanies();
        $this->hasAccess("create.requisitions.view");   // check permission


        $goods_requisitions = GoodsRequisition::with('company', 'department', 'goods_requisition_details.item.item_unit', 'goods_requisition_details.item', 'created_user', 'updated_user')
            ->orderByDesc('id')
            // ->whereIn('department_id', Department::userDepartments()->keys())
            ->when($request->filled('is_approved') && !$request->filled('is_not_approved'), function ($q) {
                $q->where('is_approved', 1);
            })->when($request->filled('is_not_approved') && !$request->filled('is_approved'), function ($q) {
                $q->where('is_approved', 0);
            });

        $systemSetting = SystemSetting::where('key', 'general_store_reference_no_change')->first();



        if ($request->filled('company_id')) {
            $goods_requisitions->where('company_id', $request->company_id);
        } else {
            $goods_requisitions->whereIn('company_id', $companies->keys());
        }
        if ($request->filled('from_date')) {
            $goods_requisitions->whereDate('goods_requisition_date', '>=', Carbon::parse($request->from_date));
        }
        if ($request->filled('to_date')) {
            $goods_requisitions->whereDate('goods_requisition_date', '<=', Carbon::parse($request->to_date));
        }
        if ($request->filled('requisition_number')) {
            $goods_requisitions->where('form_number', $request->requisition_number);
        }
        if ($request->filled('gin_number')) {
            $goods_requisitions->where('issue_number', $request->gin_number);
        }
        $goods_requisitions = $goods_requisitions->paginate(30);

        return view('goods_requisitions.index', compact('goods_requisitions', 'companies', 'systemSetting'));
    }



    /**
     *  View    :   gs.goods_requisitions.create
     *  Task    :   RETURN ITEM AND ACCESSIBLE COMPANIES, DEPARTMENTS TO THE FORM
     *  Url path:   /gs/goods-requisitions/create
     *  Url name:   goods-requisitions.create
     */
    public function create()
    {
        $this->hasAccess("create.requisitions.create");   // check permission
        $data['companies']   = Company::userCompanies();
        $data['departments'] = [];
        $data['items']       = Item::items()->select('name', 'id', 'company_id')->get();
        $data['systemSetting'] = SystemSetting::where('key', 'general_store_reference_no_change')->first();

        return view('goods_requisitions.create', $data);
    }



    /**
     *  Task    :   STORE DATA TO ITEM TABLE FROM 'gs.goods_requisitions.create' VIEW
     *  Url path:   /gs/goods-requisitions/store
     *  Url name:   goods-requisitions.store
     */
    public function store(Request $request)
    {
        $goodsRequisition = $this->validate($request, [
            'company_id'                  => 'required|not_in:0',
            'department_id'               => 'nullable|not_in:0',
            'date'                        => '',
            'goods_requisition_reference' => '',
            'item_id.*'                   => 'required',
            'quantity.*'                  => 'required'
        ]);

        // get goods requisition number from ForNumber trait
        $goods_requisition_number = $this->goods_requisition_number($request->company_id);

        // use db transaction to safely create Goods requistion
        DB::transaction(function () use ($request, $goods_requisition_number) {
            $goods_requisition = GoodsRequisition::create([
                'company_id'                  => $request->company_id,
                'department_id'               => $request->department_id,
                'goods_requisition_date'      => $request->date,
                'goods_requisition_reference' => $request->goods_requisition_reference,
                'total_quantity'              => collect($request->quantity)->sum(),
                'form_number'                 => $goods_requisition_number,
            ]);
            $this->saveGoodsRequisitionDetails($request, $goods_requisition->id);

            $goods_requisition->task_notifications()->create([
                'route_name' => 'goods-requisitions.index',
                'slug' => 'goods-requisitions.index'
            ]);
        });
        // return to Goods Requisition List
        return redirect()->route('goods-requisitions.index')->with('message-number', 'Goods requisition success! Goods requisition number is <br><h3>' . $goods_requisition_number . '</h3>');
    }



    /**
     *  View    :   gs.goods_requisitions.edit
     *  Task    :   RETURN SELECTED GOODS REQUISITION, ITEMS ACCESSIBLE COMPANIES AND DEPARTMENTS
     *  Url path:   /gs/goods-requisitions/'*'/edit
     *  Url name:   goods-requisitions.edit
     */
    public function edit(GoodsRequisition $goodsRequisition)
    {
        $this->hasAccess("create.requisitions.edit");   // check permission
        $data['items']        = Item::items()->select('name', 'id', 'company_id')->get();
        $data['companies']    = Company::userCompanies();
        $data['departments']  = [];
        $data['goodsRequisition'] = $goodsRequisition;

        // tracking history
        foreach ($goodsRequisition->goods_requisition_details as $key => $detail) {
            $item_id = $detail->item_id;
            $data['receive_items'][$key] = null;
            $data['requisition_from_item'][$key] = false;
            $data['requisition_number'][$key] = null;

            $last_requisition_from_stock = null;
            // check first any requisition in stock table
            $last_requisition_from_stock = Stock::where('item_id', $item_id)->where('type', 'Requisition Receive')->orderByDesc('source_number')->select('source_number', 'source_id', 'debit_rate')->first();

            if ($last_requisition_from_stock != null) {
                // find requisition that is exist in stock table
                $requisition = GoodsRequisition::orderByDesc('id')->where('issue_number', $last_requisition_from_stock->source_number)
                    ->whereHas('goods_requisition_details', function ($q) use ($item_id) {
                        $q->where('item_id', $item_id);
                    })->with(['goods_requisition_details' => function ($q) use ($item_id) {
                        $q->where('item_id', $item_id);
                    }])->first();

                $data['requisition_number'][$key] = $requisition;

                // get the requisition detail id from the goods requisition detail table
                $goods_requisition_details_id = $requisition->goods_requisition_details->where('item_id', $item_id)->first()->id;

                if ($goods_requisition_details_id != null) {
                    $trackings = StockTracking::where('goods_requisition_detail_id', $goods_requisition_details_id)->get();

                    $data['receive_items'][$key] = PurchaseReceive::whereHas('purchase_receive_details', function ($q) use ($item_id, $trackings) {
                        $q->where('item_id', $item_id)->whereIn('id', $trackings->where('type', 'receive')->pluck('tracking_id'));
                    })->with(['purchase_receive_details' => function ($q) use ($item_id, $trackings) {
                        $q->where('item_id', $item_id)->whereIn('id', $trackings->where('type', 'receive')->pluck('tracking_id'));
                    }])->get();
                    $data['receive_items_quantity'][$key] = $trackings->where('type', 'receive')->pluck('quantity');


                    if ($trackings->where('type', 'item')->first()) {
                        $issue_quantity = $trackings->where('type', 'item')->first()->quantity;
                        $abc = Item::where('id', $item_id)->select('rate', 'id')->first();
                        $data['requisition_from_item'][$key] = collect($abc)->merge(['issue_quantity' => $issue_quantity]);
                    } else {
                        $data['requisition_from_item'][$key] = [];
                    }
                } else {
                    $data['receive_items'][$key] = PurchaseReceive::where('id', -100)->get();
                }
            } else {
                $data['receive_items'][$key] = PurchaseReceive::where('id', -100)->get();
            }
        }
        $data['systemSetting'] = SystemSetting::where('key', 'general_store_reference_no_change')->first();

        return view('goods_requisitions.edit', $data);
    }



    /**
     *  Task    :   UPDATE EDITED DATA FROM THE 'gs.goods_requisitions.edit' VIEW
     *  Url path:   /gs/goods-requisitions/'*'/update
     *  Url name:   goods-requisitions.update
     */
    public function update(Request $request, GoodsRequisition $goodsRequisition)
    {
        $this->validate($request, [
            'company_id'                  => 'required|not_in:0',
            'department_id'               => 'required|not_in:0',
            'date'                        => '',
            'goods_requisition_reference' => '',
            'item_id.*'                   => 'required',
            'quantity.*'                  => 'required'
        ]);

        // use transaction method to safely update data
        DB::transaction(function () use ($request, $goodsRequisition) {
            $goodsRequisition->update([
                'company_id'                  =>  $request->company_id,
                'department_id'               =>  $request->department_id,
                'goods_requisition_reference' =>  $request->goods_requisition_reference,
                'total_quantity'              =>  collect($request->quantity)->sum()
            ]);
            // update goods requisition details
            $this->updateGoodsRequisitionDetails($goodsRequisition, $request);
        });

        // return to Goods Requisition List
        return redirect()->route('goods-requisitions.index')->with('message', 'Goods requisition updated successfully');
    }



    /**
     *  Task    :   DELETE GOODS REQUISITION FORM THE GOODS REQUISITION AND GOODS REQUISITION DETAILS TABLE BEFORE APPROVE
     */
    public function destroy(GoodsRequisition $goodsRequisition)
    {
        $this->hasAccess("create.requisitions.delete");   // check permission
        $issue_number = $goodsRequisition->issue_number;
        foreach ($goodsRequisition->goods_requisition_details as $detail) {
            if ($goodsRequisition->is_approved == 1) {
                $this->updateByTracking($detail, $issue_number);
            }
        }
        $goodsRequisition->goods_requisition_details()->delete();
        $goods_requisition = $goodsRequisition->delete();
        if ($goods_requisition) {
            return redirect()->back()->with('message', 'Goods requisition delete success!');
        }
    }



    /**
     *  Task    :   UN-APPROVE GOODS REQUISITION
     */
    public function unapproveGoodsRequisition(GoodsRequisition $goodsRequisition)
    {
        $this->hasAccess("create.requisitions.approve");   // check permission

        $issue_number = $goodsRequisition->issue_number;
        if ($goodsRequisition->is_approved == 1) {
            foreach ($goodsRequisition->goods_requisition_details as $detail) {
                $this->updateByTracking($detail, $issue_number);
            }
        }

        // set un-approve
        $goodsRequisition->update([
            'is_approved' => 0
        ]);
        return redirect()->back()->with('message', 'Goods requisition un-approve successfully!');
    }



    /**
     *  View    :   gs.goods_requisitions.gin_list
     *  Task    :   RETURN GIN LIST
     *  Url path:   'gs/gin-list'
     *  Url name:   gin.list
     */
    public function GINList(Request $request)
    {
        $this->hasAccess("create.requisitions.gin.list");   // check permission

        $companies = Company::userCompanies();
        $goods_requisitions = GoodsRequisition::with('company', 'department', 'goods_requisition_details.item.item_unit', 'goods_requisition_details.item', 'updated_user')
            ->orderByDesc('id')
            // ->whereIn('department_id', Department::userDepartments()->keys())
            ;

        if ($request->filled('company_id')) {
            $goods_requisitions->where('company_id', $request->company_id);
        } else {
            $goods_requisitions->whereIn('company_id', $companies->keys());
        }
        if ($request->filled('from_date')) {
            $goods_requisitions->whereDate('issue_date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $goods_requisitions->whereDate('issue_date', '<=', Carbon::parse($request->to_date));
        }
        if ($request->filled('requisition_number')) {
            $goods_requisitions->where('form_number', $request->requisition_number);
        }
        if ($request->filled('gin_number')) {
            $goods_requisitions->where('issue_number', $request->gin_number);
        }
        $goods_requisitions = $goods_requisitions->where('is_approved', 1)->orderByDesc('id')->paginate(30);
        $systemSetting = SystemSetting::where('key', 'general_store_reference_no_change')->first();

        return view('goods_requisitions.gin_list', compact('goods_requisitions', 'companies', 'systemSetting'));
    }


    public function GINListShow(GoodsRequisition $goodsRequisition)
    {
        $this->hasAccess("create.requisitions.gin.list");   // check permission

        $companies = Company::userCompanies();
        $goods_requisitions = GoodsRequisition::with('company', 'department', 'goods_requisition_details.item.item_unit', 'goods_requisition_details.item', 'updated_user')
            ->orderByDesc('id')->where('id', $goodsRequisition->id);

        $goods_requisitions = $goods_requisitions->where('is_approved', 1)->orderByDesc('id')->paginate(30);

        return view('goods_requisitions.gin_list', compact('goods_requisitions', 'companies'));
    }


    /**
     *  View    :   gs.goods_requisitions.approve
     *  Task    :   RETURN SELECTED GOODS REQUISITION TO APPROVE
     *  Url path:   'goods-requisition/approve/{goodsRequisition}'
     *  Url name:   approve.goods.requisition.show
     */
    public function approveGoodsRequisitionShow(GoodsRequisition $goodsRequisition)
    {
        $this->hasAccess("create.requisitions.approve");   // check permission

        $items              = Item::items()->select('name', 'id', 'company_id')->get();
        $companies          = Company::userCompanies();
        $departments        = [];
        $previous_unapprove = GoodsRequisition::where('is_approved', 0)
            ->orderBy('goods_requisition_date')->where('goods_requisition_date', '<', $goodsRequisition->goods_requisition_date)
            ->where('company_id', $goodsRequisition->company_id)
            ->first();

        return view('goods_requisitions.approve', compact('goodsRequisition', 'companies', 'departments', 'items', 'previous_unapprove'));
    }



    /**
     *  View    :   gs.goods_requisitions.approve
     *  Task    :   APPROVE GOODS REQUISITION FROM 'gs.goods_requisitions.approve' VIEW
     *  Url path:   'goods-requisition/approve/{goodsRequisition}'
     *  Url name:   'goods-requisition/{goodsRequisition}/approve'
     */
    public function approveGoodsRequisition(Request $request, GoodsRequisition $goodsRequisition)
    {
        $this->hasAccess("create.requisitions.approve");   // check permission


        // get gin number from FormNumber trait
        $gin_issue_number = $this->gin_issue_number($goodsRequisition);

        // check stock is available or not for all requested item
        foreach ($goodsRequisition->goods_requisition_details as $key => $details) {
            if (in_array($details->item_id, $request->item_id)) {
                if ($details->item->current_stock < $details->quantity) {
                    return redirect()->back()->with('error', $details->item->name . ' has not enough stock');
                } else if ($request->quantity == 0) {
                    return redirect()->back()->with('error', $details->item->name . ' can not be zero');
                }
            }
        }

        // user transaction to safely approve and update data
        DB::transaction(function () use ($request, $goodsRequisition, $gin_issue_number) {
            // update goods requisition status
            $goodsRequisition->is_approved  = 1;
            $goodsRequisition->issue_date   = $request->goods_requisition_date;
            $goodsRequisition->issue_number = $gin_issue_number;
            $goodsRequisition->save();
            // update goods requisition details
            $this->updateGoodsRequisitionDetails($goodsRequisition, $request);

            // update stock
            $this->updateCurrentStock(GoodsRequisition::find($goodsRequisition->id), $request);

            // delete notification
            $goodsRequisition->task_notifications()->delete();
        });

        // return to GIN LIST
        return redirect()->route('gin.list')->with('message-number', 'Requisition approve success! GIN number is <br><h3>' . $gin_issue_number . '</h3>');
    }








    // ################################     HELPER METHODS     ################################

    //  update stock with tracking when delete requisition
    public function updateByTracking($detail, $issue_number)
    {
        $item      = Item::find($detail->item_id);
        $trackings = StockTracking::where('goods_requisition_detail_id', $detail->id)->get();

        foreach ($trackings as $t => $tracking) {
            // existing rate of item
            $old_rate     = $item->average_rate;
            $old_quantity = $item->current_stock;
            $old_amount   = $old_rate * $old_quantity;

            // rate from tracking
            $new_rate     = $tracking->price;
            $new_quantity = $tracking->quantity;
            $new_amount   = $new_quantity * $new_rate;

            // set current amount
            $current_amount     = $old_amount + $new_amount;
            $current_quantity   = $old_quantity + $new_quantity;
            $current_rate       = $item->average_rate;
            if ($current_quantity > 0) {
                $current_rate       = $current_amount / $current_quantity;
            }

            $remaining_quantity = $item->remaining_quantity + $new_quantity;


            //  update current stock and average rate
            if ($tracking->type == 'item') {
                $item->update([
                    'average_rate'       => $current_rate,
                    'remaining_quantity' => $remaining_quantity,
                ]);
            } else {
                $item->update([
                    'average_rate'       => $current_rate,
                ]);

                $purchase_receive_detail = PurchaseReceiveDetails::where('id', $tracking->tracking_id)->first();
                if ($purchase_receive_detail) {
                    if ($purchase_receive_detail->remaining_quantity + $new_quantity >= $purchase_receive_detail->quantity) {
                        $receive_remaining = $purchase_receive_detail->quantity;
                    } else {
                        $receive_remaining = $purchase_receive_detail->remaining_quantity + $new_quantity;
                    }
                }

                $purchase_receive_detail->update([
                    'remaining_quantity' => $receive_remaining
                ]);
            }
        }
        StockTracking::where('goods_requisition_detail_id', $detail->id)->delete();

        $stock = Stock::where('source_number', $issue_number)->where('item_id', $detail->item_id)->delete();

        //  update current stock and average rate
        $stock      = Stock::where('item_id', $detail->item_id)->get();
        $debit_qty  =  collect($stock)->sum('debit_qty');
        $credit_qty =  collect($stock)->sum('credit_qty');

        //  update current stock and rate
        $selected_item = Item::where('id', $detail->item_id)->first();
        $selected_item->update([
            'current_stock' => $selected_item->opening_balance + $credit_qty - $debit_qty
        ]);
    }




    // helper for approve
    public function updateCurrentStock($goodsRequisition, $request)
    {
        foreach ($goodsRequisition->goods_requisition_details as $key => $requisition_item) {
            $total_amount = 0;

            $required_quantity = $requisition_item->quantity;
            $item = Item::where('id', $requisition_item->item_id)->first();

            // stock minus and getting rate from item
            if ($item->remaining_quantity > 0) {

                if ($item->remaining_quantity >= $required_quantity) {
                    $total_amount += ($item->rate * $required_quantity);
                    $item->remaining_quantity -= $required_quantity;
                    $item->save();
                    $this->trackStock($requisition_item->id, 'item', $item->id, $required_quantity, $item->rate);
                    $required_quantity = 0;
                } else {
                    $required_quantity -= $item->remaining_quantity;
                    $total_amount += ($item->rate * $item->remaining_quantity);
                    $this->trackStock($requisition_item->id, 'item', $item->id, $item->remaining_quantity, $item->rate);
                    $item->remaining_quantity = 0;
                    $item->save();
                }
            }

            // stock minus and getting rate from item
            while ($required_quantity > 0) {
                $purchase_receive = PurchaseReceive::orderBy('purchase_receive_date')
                    ->whereHas('purchase_receive_details', function ($q) use ($requisition_item) {
                        $q
                            ->where('item_id', $requisition_item->item_id)
                            ->where('remaining_quantity', '>', 0);
                    })->with(['purchase_receive_details' =>  function ($q) use ($requisition_item) {
                        $q
                            ->where('item_id', $requisition_item->item_id)
                            ->where('remaining_quantity', '>', 0);
                    }])
                    ->first();
                //                return dd($purchase_receive);
                if (optional(optional($purchase_receive)->purchase_receive_details)->count() > 0) {
                    $receive_item = $purchase_receive->purchase_receive_details->first();

                    if ($receive_item->remaining_quantity >= $required_quantity) {
                        $total_amount += ($required_quantity * $receive_item->rate);
                        $this->trackStock($requisition_item->id, 'receive', $receive_item->id, $required_quantity, $receive_item->rate);
                        $receive_item->remaining_quantity -= $required_quantity;
                        $receive_item->save();
                        $required_quantity = 0;
                    } else {
                        $total_amount += ($receive_item->remaining_quantity * $receive_item->rate);
                        $this->trackStock($requisition_item->id, 'receive', $receive_item->id, $receive_item->remaining_quantity, $receive_item->rate);
                        $required_quantity -= $receive_item->remaining_quantity;
                        $receive_item->remaining_quantity = 0;
                        $receive_item->save();
                    }
                } else {
                    break;
                }
            }

            $rate = ($total_amount / $requisition_item->quantity);
            $this->createStock($requisition_item->item_id, $goodsRequisition, $goodsRequisition->issue_number, $requisition_item->quantity, $rate, $request->goods_requisition_date);

            //  update current stock and average rate
            $stock      = Stock::where('item_id', $requisition_item->item_id)->get();
            $debit_qty  =  collect($stock)->sum('debit_qty');
            $credit_qty =  collect($stock)->sum('credit_qty');

            //  update current stock and rate
            $selected_item = Item::where('id', $requisition_item->item_id)->first();
            $selected_item->update([
                'current_stock' => $selected_item->opening_balance + $credit_qty - $debit_qty,
                'average_rate'  => $rate,
            ]);
        }
    }


    // update helper
    public function updateGoodsRequisitionDetails($goodsRequisition, $request)
    {
        //  if old and new is equal
        if (count($goodsRequisition->goods_requisition_details) == count($request->item_id)) {
            foreach ($goodsRequisition->goods_requisition_details as $key => $detail) {
                $detail->update([
                    'company_id' => $request->company_id,
                    'item_id'    => $request->item_id[$key],
                    'quantity'   => $request->quantity[$key],
                    // 'stock'      => $request->current_stock[$key],
                    'remarks'    => $request->remarks[$key]
                ]);
            }
        } elseif (count($request->item_id) > count($goodsRequisition->goods_requisition_details)) {
            //  if new is greater than old
            foreach ($goodsRequisition->goods_requisition_details as $key => $detail) {
                $detail->update([
                    'company_id' => $request->company_id,
                    'item_id'    => $request->item_id[$key],
                    'quantity'   => $request->quantity[$key],
                    // 'stock'      => $request->current_stock[$key],
                    'remarks'    => $request->remarks[$key]
                ]);
            }

            // create new added
            for ($i = count($goodsRequisition->goods_requisition_details); $i < count($request->item_id); $i++) {
                GoodsRequisitionDetails::create([
                    'goods_requisition_id' => $goodsRequisition->id,
                    'company_id'           => $request->company_id,
                    'item_id'              => $request->item_id[$i],
                    'quantity'             => $request->quantity[$i],
                    // 'stock'                => $request->current_stock[$key],
                    'remarks'              => $request->remarks[$i]
                ]);
            }
        } else {
            foreach ($request->item_id as $key => $item) {
                $goodsRequisition->goods_requisition_details[$key]->update([
                    'company_id' => $request->company_id,
                    'item_id'    => $request->item_id[$key],
                    'quantity'   => $request->quantity[$key],
                    // 'stock'      => $request->current_stock[$key],
                    'remarks'    => $request->remarks[$key]
                ]);
            }
            // delete old extra
            for ($i = count($request->item_id); $i < count($goodsRequisition->goods_requisition_details); $i++) {
                $goodsRequisition->goods_requisition_details[$i]->delete();
            }
        }
    }



    // create stock to
    public function createStock($item_id, $goodsRequisition, $issue_number, $required_quantity, $rate, $date)
    {
        Stock::create([
            'item_id'       => $item_id,
            'date'          => $date,
            'type'          => "Requisition Receive",
            'source_id'     => $goodsRequisition->id,
            'source_number' => $issue_number,
            'debit_qty'     => $required_quantity,
            'debit_rate'    => $rate
        ]);
    }



    // create for stock tracking
    public function trackStock($item_id, $type, $tracking_id, $quantity, $price)
    {
        StockTracking::create([
            'goods_requisition_detail_id' => $item_id,
            'type'                        => $type,
            'tracking_id'                 => $tracking_id,
            'quantity'                    => $quantity,
            'price'                       => $price
        ]);
    }



    // store detail helper of store
    public function saveGoodsRequisitionDetails($request, $goods_requisition_id)
    {
        foreach ($request->item_id as $key => $item_id) {
            GoodsRequisitionDetails::create([
                'goods_requisition_id' => $goods_requisition_id,
                'company_id'  => $request->company_id,
                'item_id'     => $request->item_id[$key],
                'quantity'    => $request->quantity[$key],
                'remarks'     => $request->remarks[$key]
            ]);
        }
    }
}
