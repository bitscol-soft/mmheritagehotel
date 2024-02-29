<?php

namespace Module\GeneralStore\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Module\GeneralStore\Models\Item;
use Module\GeneralStore\Models\Purchase;

use App\Models\SystemSetting;
use App\Traits\CheckPermission;
use App\Traits\FormNumber;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Module\GeneralStore\Models\PurchaseDetails;

class PurchaseController extends Controller
{
    use FormNumber, CheckPermission;


    /**
     *  View    :   gs.purchases.index
     *  Task    :   RETURN LIST OF PURCHASES OF THE COMPANIES THAT USER HAVE PERMISSION
     *  Url path:   /gs/purchases
     *  Url name:   purchases.index
     */
    public function index(Request $request)
    {
        $this->hasAccess("purchases.view");   // check permission
        $data['companies']    = Company::userCompanies();
        $data['systemSetting'] = SystemSetting::where('key', 'general_store_reference_no_change')->first();

        $data['purchases']    = Purchase::with(['company', 'created_user', 'updated_user', 'purchase_details.itemReceived'])
            ->when($request->filled('is_approved') && !$request->filled('is_not_approved'), function ($q) {
                $q->where('is_approved', 1);
            })->when($request->filled('is_not_approved') && !$request->filled('is_approved'), function ($q) {
                $q->where('is_approved', 0);
            });

        //        $purchase_ids = Purchase::whereIn('company_id', $companies->keys())->pluck('form_number', 'id');


        if ($request->filled('company_id')) {
            $data['purchases']->where('company_id', $request->company_id);
        } else {
            $data['purchases']->whereIn('company_id', $data['companies']->keys());
        }
        if ($request->filled('from_date')) {
            $data['purchases']->whereDate('purchase_date', '>=', Carbon::parse($request->from_date));
        }
        if ($request->filled('to_date')) {
            $data['purchases']->whereDate('purchase_date', '<=', Carbon::parse($request->to_date));
        }
        if ($request->filled('purchase_number')) {
            $data['purchases']->where('form_number', $request->purchase_number);
        }
        $data['purchases']  = $data['purchases']->orderByDesc('id')->paginate(30);


        return view('purchases.index', $data);
    }



    /**
     *  View    :   gs.purchases.create
     *  Task    :   RETURN ITEM AND ACCESSIBLE COMPANIES TO THE FORM
     *  Url path:   /gs/purchases/create
     *  Url name:   purchases.create
     */
    public function create()
    {
        $this->hasAccess("purchases.create");
        $items     = Item::items()->select('name', 'id', 'company_id')->get();
        $companies = Company::userCompanies();
        $systemSetting = SystemSetting::where('key', 'general_store_reference_no_change')->first();


        return view('purchases.create', compact(['items', 'companies', 'systemSetting']));
    }



    /**
     *  Task    :   STORE DATA TO PURCHASE TABLE FROM 'gs.purchases.create' VIEW
     *  Url path:   /gs/purchase.store
     *  Url name:   purchases.store
     */
    public function store(Request $request)
    {
        // check validation
        $this->validate($request, [
            'company_id'    => 'required',
            'item_id.*'     => 'required',
            'quantity.*'    => 'required'
        ]);

        // use transaction to safely store data
        DB::transaction(function () use ($request) {
            $purchases = Purchase::create([
                'company_id'         => $request->company_id,
                'purchase_reference' => $request->reference,
                'is_approved'        => 0,
                'purchase_date'      => Carbon::parse($request->purchase_date)->format('Y-m-d'),
                'total'              => collect($request->quantity)->sum(),
            ]);

            $this->savePurchseDetails($request, $purchases);

            if(class_exists('Module\HRM\Models\News\TaskNotification')) {
                $purchases->task_notifications()->create([
                    'route_name' => 'purchases.index',
                    'slug' => 'purchases.index'
                ]);
            }
        });

        // return to Purchase List
        return redirect()->route('purchases.index')->with('message', 'Purchase created successfully!');
    }



    /**
     *  View    :   gs.purchases.show
     *  Task    :   RETURN SELECTED PURCHASE TO SHOW DETAILS
     *  Url path:   /gs/purchases/*
     *  Url name:   purchases.show
     */
    public function show(Purchase $purchase)
    {
        $this->hasAccess("purchases.view");
        $purchase = Purchase::with('purchase_details.item.item_unit')->where('id', $purchase->id)->first();
        $systemSetting = SystemSetting::where('key', 'general_store_reference_no_change')->first();

        return view('purchases.show', compact('purchase', 'systemSetting'));
    }



    /**
     *  View    :   gs.purchases.edit
     *  Task    :   RETURN SELECTED PURCHASE, ACCESSIBLE ITEMS AND COMPANIES TO 'gs.purchases.edit' FORM
     *  Url path:   /gs/purchases/{purchase}/edit
     *  Url name:   purchases.edit
     */
    public function edit(Purchase $purchase)
    {
        $this->hasAccess("purchases.edit");

        $items     = Item::items()->select('name', 'id', 'company_id')->get();
        $companies = Company::userCompanies();
        $purchase  = Purchase::where('id', $purchase->id)->with('purchase_details')->first();
        $systemSetting = SystemSetting::where('key', 'general_store_reference_no_change')->first();

        return view('purchases.edit', compact('purchase', 'companies', 'items', 'systemSetting'));
    }



    /**
     *  Task    :   UPDATE EDITED DATA FROM THE 'gs.purchases.edit' VIEW
     *  Url path:   /gs/purchases/{purchase}/update
     *  Url name:   purchases.update
     */
    public function update(Request $request, Purchase $purchase)
    {
        // check validation
        $this->validate($request, [
            'company_id'    => 'required',
            'item_id.*'     => 'required',
            'quantity.*'    => 'required'
        ]);

        // use transaction to safely update purchase
        DB::transaction(function () use ($request, $purchase) {
            $purchase->update([
                'company_id'           =>  $request->company_id,
                'purchase_date'        =>  Carbon::parse($request->purchase_date)->format('Y-m-d'),
                'purchase_reference'   =>  $request->reference,
                'total'                =>  collect($request->quantity)->sum()
            ]);
            $this->updatePurchaseDetails($purchase, $request);
        });

        // return to Purchase List
        return redirect()->route('purchases.index')->with('message', 'Purchase updated successfully');
    }



    /**
     *  Task    :   DELETE PURCHASE FORM THE PURCHASE TABLE
     */
    public function destroy(Purchase $purchase)
    {
        $this->hasAccess("purchases.delete");    // check permission

        $purchase->purchase_details()->delete();
        $purchase = $purchase->delete();
        return redirect()->back()->with('message', 'Purchase delete success!');
    }



    /**
     *  View    :   gs.purchases.approve
     *  Task    :   RETURN SELECTED PURCHASE, ACCESSIBLE ITEMS AND COMPANIES TO 'gs.purchases.approve' FORM
     *  Url path:   /gs/purchase-approve/{id}
     *  Url name:   gs.approve.purchase.show
     */
    public function purchaseApproveShow($id)
    {
        $this->hasAccess("purchases.approve");

        $data['items']     = Item::items()->select('name', 'id', 'company_id')->get();
        $data['companies'] = Company::userCompanies();
        $data['purchase']  = Purchase::where('id', $id)->with('purchase_details')->first();

        foreach ($data['purchase']->purchase_details as $key => $detail) {
            $item_id = $detail->item_id;
            $last_purchase = Purchase::whereIsApproved(1)->orderByDesc('purchase_date')->whereHas('purchase_details', function ($q) use ($item_id) {
                $q->where('item_id', $item_id);
            })->first();

            if ($last_purchase) {
                $data['last_purchases'][] = $last_purchase;
            } else {
                $data['last_purchases'][] = '';
            }
        }
        return view('purchases.approve', $data);
    }



    /**
     *  View    :   gs.purchase.approve
     *  Task    :   APPROVE PURCHASE FROM 'gs.purchases.approve' VIEW
     *  Url path:   /gs/purchase-approve/{purchase}
     *  Url name:   gs.approve.purchase.show
     */
    public function purchaseApprove(Request $request, Purchase $purchase)
    {
        // return $request->all();
        // check validation
        $this->validate($request, [
            'company_id'    => 'required',
            'item_id.*'     => 'required',
            'quantity.*'    => 'required'
        ]);

        DB::transaction(function () use ($request, $purchase) {
            $purchase->update([
                'form_number'          => $purchase->form_number ?? $this->purchase_number($purchase->company_id),
                'is_approved'          => 1,
                'company_id'          =>  $request->company_id,
                'purchase_date'        =>  Carbon::parse($request->purchase_date)->format('Y-m-d'),
                'purchase_reference'   =>  $request->reference,
                'total'                =>  collect($request->quantity)->sum()
            ]);
            $this->updatePurchaseDetails($purchase, $request);

            // delete purchase item from notification table
            if(class_exists('Module\HRM\Models\News\TaskNotification')) {
                $purchase->task_notifications()->delete();
            }
        });

        return redirect()->route('purchases.index')->with('message-number', 'Purchase approve success! Purchase number is <br><h3>' . $purchase->form_number . '</h3>');
    }



    /**
     *  View    :   gs.purchase.unapprove
     *  Task    :   UNAPPROVE PURCHASE FROM 'gs.purchases.index' VIEW
     *  Url path:   'purchase/unapprove/{purchase}'
     *  Url name:   gs.unapprove.purchase
     */
    public function purchaseUnapprove(Purchase $purchase)
    {
        $purchase->update(['is_approved' => 0]);
        return redirect()->route('purchases.index')->with('message-number', 'Purchase un-approve successfully.');
    }












    // ################################     HELPER METHODS     ################################

    // help for 'update'
    public function updatePurchaseDetails($purchase, $request)
    {
        //  if old and new is equal
        if (count($purchase->purchase_details) == count($request->item_id)) {
            foreach ($purchase->purchase_details as $key => $detail) {
                $detail->update([
                    'company_id' => $request->company_id,
                    'item_id'    => $request->item_id[$key],
                    'quantity'   => $request->quantity[$key]
                ]);
            }
        } elseif (count($request->item_id) > count($purchase->purchase_details)) {
            //  if new is greater than old
            foreach ($purchase->purchase_details as $key => $detail) {
                $detail->update([
                    'company_id' => $request->company_id,
                    'item_id'    => $request->item_id[$key],
                    'quantity'   => $request->quantity[$key]
                ]);
            }

            // create new added
            for ($i = count($purchase->purchase_details); $i < count($request->item_id); $i++) {
                PurchaseDetails::create([
                    'purchase_id' => $purchase->id,
                    'company_id'  => $request->company_id,
                    'item_id'     => $request->item_id[$i],
                    'quantity'    => $request->quantity[$i]
                ]);
            }
        } else {
            foreach ($request->item_id as $key => $item) {
                $purchase->purchase_details[$key]->update([
                    'company_id' => $request->company_id,
                    'item_id'    => $item,
                    'quantity'   => $request->quantity[$key],
                ]);
            }
            // delete old extra
            for ($i = count($request->item_id); $i < count($purchase->purchase_details); $i++) {
                $purchase->purchase_details[$i]->delete();
            }
        }
    }


    //   help for 'store'
    public function savePurchseDetails($request, $purchase)
    {
        foreach ($request->item_id as $key => $item_id) {
            PurchaseDetails::create([
                'purchase_id' => $purchase->id,
                'company_id'  => $request->company_id,
                'item_id'     => $request->item_id[$key],
                'quantity'    => $request->quantity[$key]
            ]);
        }
    }
}
