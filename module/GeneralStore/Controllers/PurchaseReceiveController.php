<?php

namespace Module\GeneralStore\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Module\GeneralStore\Models\Item;
use Module\GeneralStore\Models\Stock;

use Module\GeneralStore\Models\Purchase;
use Module\GeneralStore\Models\Supplier;
use App\Models\SystemSetting;
use App\Traits\CheckPermission;
use App\Traits\FormNumber;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Module\GeneralStore\Models\PurchaseReceive;

class PurchaseReceiveController extends Controller
{
    use FormNumber, CheckPermission;


    /**
     *  View    :   print purchase receive detail
     */
    public function print(PurchaseReceive $purchaseReceive)
    {
        return view('purchase_receives.print-receive', compact('purchaseReceive'));
    }

    /**
     *  View    :   gs.purchase_receives.purchase_receive_list
     *  Task    :   RETURN PURCHASE RECEIVES AGAINST SELECTED PURCHASE
     *  Url path:   /gs/purchase-receive/list/{purchase}
     *  Url name:   purchase.receive.list
     */
    public function purchaseReceiveList(Purchase $purchase)
    {
        //        return $purchase->all();
        $this->hasAccess("purchases.grn.list");
        $purchase_receives = PurchaseReceive::where('purchase_id', $purchase->id)
            ->with(['purchase_receive_details.supplier', 'purchase', 'company'])
            ->with('purchase_receive_details.is_in_stock')
            ->paginate(30);
        return view('purchase_receives.purchase_receive_list', compact('purchase_receives'));
    }



    /**
     *  View    :   gs.purchase_receives.grn_list
     *  Task    :   RETURN GRN LIST
     *  Url path:   /gs/grn-list
     *  Url name:   grn.list
     */
    public function grnList(Request $request)
    {

        $companies = Company::userCompanies();
        $purchase_receives = PurchaseReceive::with('purchase_receive_details.supplier', 'purchase.purchase_details.item.item_unit', 'company', 'updated_user')
            ->with('purchase_receive_details.is_in_stock')->orderByDesc('id');
        if ($request->filled('company_id')) {
            $purchase_receives->where('company_id', $request->company_id);
        } else {
            $purchase_receives->whereIn('company_id', $companies->keys());
        }
        if ($request->filled('from_date')) {
            $purchase_receives->whereDate('purchase_receive_date', '>=', Carbon::parse($request->from_date));
        }
        if ($request->filled('to_date')) {
            $purchase_receives->whereDate('purchase_receive_date', '<=', Carbon::parse($request->to_date));
        }
        if ($request->filled('grn_no')) {
            $purchase_receives->where('form_number', $request->grn_no);
        }

        if ($request->filled('purchase_number')) {
            $purchase_receives->whereHas('purchase', function ($q) use ($request) {
                $q->where('form_number', $request->purchase_number);
            });
        }
        $purchase_receives = $purchase_receives->paginate(30);
        return view('purchase_receives.grn_list', compact('purchase_receives', 'companies'));
    }


    public function grnListShow($id)
    {
        $companies = Company::userCompanies();
        $purchase_receives = PurchaseReceive::where('id', $id)->with('purchase_receive_details.supplier', 'purchase', 'company', 'totalQuantity')->orderByDesc('id');

        $purchase_receives = $purchase_receives->paginate(30);
        return view('purchase_receives.grn_list', compact('purchase_receives', 'companies'));
    }


    /**
     *  View    :   gs.purchase_receives.create
     *  Task    :   RETURN SELECTED PURCHASE AND SUPPLIERS
     *  Url path:   /gs/purchase-receive/create/{purchase}
     *  Url name:   purchase.receives.create
     */
    public function purchaseReceiveCreate(Purchase $purchase)
    {
        $data = [];
        $this->hasAccess("purchase.receives.create");   // check permission
        $data['suppliers'] = Supplier::pluck('name', 'id');

        $data['receive_items'][] = [];
        $data['requisition_from_item'][] = [];
        $data['requisition_number'][] = [];

        $data['purchase'] = Purchase::with(['last_receive' => function ($q) {
            $q->orderByDesc('id')->with('purchase_receive_details')->first();
        }])->with('purchase_details')->find($purchase->id);
        $data['systemSetting'] = SystemSetting::where('key', 'general_store_reference_no_change')->first();

        //        $data['purchase'] = Purchase::with('last_receive.purchase_receive_details', 'purchase_details')->find($purchase->id);
        return view('purchase_receives.create', $data);
    }




    /**
     *  Task    :   STORE DATA TO PURCHASE Receive TABLE FROM 'gs.purchase_receives.create' VIEW
     *  Url path:   purchase-receive/store
     *  Url name:   purchase.receives.store
     */
    public function purchaseReceiveStore(Request $request)
    {
        // check validation
        $this->validate($request, [
            'purchase_receive_date'   => 'required',
            'supplier_id.*'           => 'required',
            'rate.*'                  => 'required',
            'quantity.*'              => 'required',
            'remarks.*'               => 'nullable',
            'quantity'                => 'required',
            'challan_image'           => 'nullable|mimes:jpg,jpeg,png,PNG,pdf|max:1000'
        ]);

        // get purchase receive / grn number
        $purchase = Purchase::find($request->purchase_id);
        $purchase_receive_number = $this->purchase_receive_number($purchase);

        // ready data to store into purchase receive table
        $data = [
            'company_id'                 => $request->company_id,
            'purchase_receive_date'      => $request->purchase_receive_date,
            'purchase_receive_reference' => $request->purchase_receive_reference,
            'purchase_challan_number'    => $request->purchase_challan_number,
            'quantity'                   => collect($request->quantity)->sum(),
            'purchase_id'                => $request->purchase_id,
            'is_approved'                => 1,
            'form_number'                => $purchase_receive_number
        ];

        // use transaction to safely store data into multiple table
        DB::transaction(function () use ($request, $data, $purchase_receive_number, $purchase) {
            // create purchase receive
            $purchase_receives = PurchaseReceive::create($data);
            // upload challan
            $this->uploadPurchaseReceiveChallan($request, $purchase_receives);
            foreach ($request->supplier_id as $key => $supplier) {
                //store into purchase receive details
                $this->savePurchaseReceiveDetails($request, $key, $purchase_receives, $purchase);
                // create stock
                $this->createStockDetails($request, $purchase_receives->id, $purchase_receive_number, $key);
                // $this->save_item_stock($item_data);
                $this->updateCurrentStockAndRate($request, $key);
            }
        });

        // return to GRN List
        return redirect()->route('grn.list')->with('message-number', 'Purchase receive success! GRN number is <br><h3>' . $purchase_receive_number . '</h3>');
    }



    /**
     *  Task    :   DELETE PURCHASE RECEIVE DETAILS
     */
    public function purchaseReceiveDelete(PurchaseReceive $purchaseReceive, Request $request)
    {
        $this->hasAccess("purchase.receives.delete");

        foreach ($purchaseReceive->purchase_receive_details as $key => $detail) {
            $item = Item::where('id', $detail['item_id'])->first();

            $item_amount    = $item->current_stock * $item->average_rate;
            $input_amount   = $detail->quantity * $detail->rate;

            $total_quantity = $item->current_stock - $detail->quantity;
            $total_amount   = $item_amount - $input_amount;
            $average_rate   = $total_amount / $total_quantity;

            $item->update([
                'current_stock' => $total_quantity,
                'average_rate' => $average_rate,
            ]);
        }

        Stock::where('source_number', $purchaseReceive->form_number)->delete();
        $purchaseReceive->purchase_receive_details()->delete();
        $purchaseReceive = $purchaseReceive->delete();

        if ($purchaseReceive) {
            return redirect()->back()->with('message', 'Purchase Receive delete successfully!');
        }
    }





    // ################################     HELPER METHODS     ################################



    // upload challan
    public function uploadPurchaseReceiveChallan($request, $purchase_receives)
    {
        if ($purchase_receives) {
            if ($request->file('challan_image')) {
                $upload_image_name = "";

                $image = $request->file('challan_image');
                $slug  = Str::slug($request->name);
                $year  = date('Y');
                $month = date('m');

                if (isset($image)) {
                    $imageName   = $slug . '-' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $directory = './uploads/' . 'challan/' . $year . '/' . $month . '/';
                    $image->move($directory, $imageName);
                    $upload_image_name =  $directory . $imageName;

                    $purchase_receives->challan_image = $upload_image_name;
                    $purchase_receives->save();
                }
            }
        }
    }


    // update current stock and rate into item table
    public function updateCurrentStockAndRate($request, $key)
    {
        $item = Item::where('id', $request->item_id[$key])->first();

        $item_amount    = $item->current_stock * $item->average_rate;
        $input_amount   = $request->quantity[$key] * $request->rate[$key];
        $total_quantity = $item->current_stock + $request->quantity[$key];
        $total_amount   = $item_amount + $input_amount;
        if ($total_quantity == 0) {
            $average_rate = $item->average_rate;
        } else {
            $average_rate   = $total_amount / $total_quantity;
        }

        $item->update([
            'current_stock' => $total_quantity,
            'average_rate' => $average_rate,
        ]);
    }



    // create stock record to stock table
    public function createStockDetails($request, $purchase_receives_id, $purchase_receive_number, $key)
    {
        Stock::create([
            'item_id'       => $request->item_id[$key],
            'date'          => $request->purchase_receive_date,
            'type'          => "Purchase Receive",
            'source_id'     => $purchase_receives_id,
            'source_number' => $purchase_receive_number,
            'credit_qty'    => $request->quantity[$key],
            'credit_rate'   => $request->rate[$key]
        ]);
    }



    // store data to purchase receive details table
    public function savePurchaseReceiveDetails($request, $key, $purchase_receive, $purchase)
    {
        $data = [
            'company_id'          => $request->company_id,
            'purchase_details_id' => $request->purchase_details_id[$key],
            'item_id'             => $request->item_id[$key],
            'supplier_id'         => $request->supplier_id[$key],
            'rate'                => $request->rate[$key],
            'quantity'            => $request->quantity[$key],
            'remaining_quantity'  => $request->quantity[$key],
            'remarks'             => $request->remarks[$key]
        ];
        $purchase->purchase_details[$key]->received_quantity +=  $request->quantity[$key];
        $purchase->purchase_details[$key]->save();
        return $purchase_receive->purchase_receive_details()->create($data);
    }
}
