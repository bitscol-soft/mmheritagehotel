<?php

namespace Module\Restaurant\Controllers\Inventory;

use Carbon\Carbon;
use App\Models\Company;
use App\Traits\FormNumber;
use Illuminate\Http\Request;
use App\Models\SystemSetting;
use App\Traits\CheckPermission;
use Illuminate\Support\Facades\DB;
use Module\Account\Models\Account;
use App\Http\Controllers\Controller;
use Module\Restaurant\Models\Product;
use Module\Restaurant\Models\Supplier;
use Module\Restaurant\Models\RstPurchase;
use Module\Restaurant\Models\RestMaterial;
use Module\Restaurant\Request\PurchaseRequest;
use Module\Restaurant\Services\PurchaseService;
use Module\Restaurant\Models\RstPurchaseDetails;
use Module\Restaurant\Request\RstPurchasesRequest;
use Module\Restaurant\Services\ResturentTransectionService;

class RstPurchaseController extends Controller
{
    use FormNumber, CheckPermission;


    public $service;

    public function __construct(PurchaseService $purchaseService)
    {
        $this->service = $purchaseService;
    }
    /*
     |--------------------------------------------------------------------------
     | INDEX METHOD
     |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $this->hasAccess("rst.purchase.view");   // check permission
        $data['companies']    = Company::userCompanies();
        $data['suppliers']      = Supplier::query()->companies()->whereStatus(true)->get();
        $data['systemSetting'] = SystemSetting::where('key', 'general_store_reference_no_change')->first();

        $data['purchases']    = RstPurchase::with(['company', 'created_user', 'updated_user','purchase_details'])
        // $data['purchases']    = RstPurchase::with(['company', 'created_user', 'updated_user', 'purchase_details.itemReceived'])
            ->when($request->filled('is_approved') && !$request->filled('is_not_approved'), function ($q) {
                $q->where('is_approved', 1);
            })->when($request->filled('is_not_approved') && !$request->filled('is_approved'), function ($q) {
                $q->where('is_approved', 0);
            });

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


        return view('inventory.production.purchases.index', $data);
    }



    /*
     |--------------------------------------------------------------------------
     | CREATE METHOD
     |--------------------------------------------------------------------------
    */
    public function create()
    {

        $this->hasAccess("rst.purchase.create");
        $items     = RestMaterial::items()->select('name', 'id', 'company_id')->get();
        $data['companies'] = Company::userCompanies();
        $data['suppliers']      = Supplier::query()->companies()->whereStatus(true)->get();
        $data['challan_id']     = (new ResturentTransectionService())->getPurInvoiceNo('RestMaterial Purchase');
        $data['accounts']       = Account::companies()->get();

        return view('inventory.production.purchase-v2.create', $data);
    }



     /*
     |--------------------------------------------------------------------------
     | STORE METHOD
     |--------------------------------------------------------------------------
    */
    public function store(RstPurchasesRequest $request)
    {
        $this->hasAccess("rst.purchase.create");

        try {

            $purchase = $request->store();

        } catch (\Exception $ex) {

            return redirect()->back()->withInput()->withError($ex->getMessage());

        }


        // return redirect()->route('rst.purchase.index', $purchase->id)->withSuccess('Purchase Store in Stock Successfully!');
        return redirect()->route('rst.purchase.index')->with('message', 'Purchase created successfully!');
    }



    /*
     |--------------------------------------------------------------------------
     | SHOW METHOD
     |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        $this->hasAccess("rst.purchase.view");
        $purchase = RstPurchase::with('purchase_details.product')->where('id', $id)->first();

        return view('inventory.production.purchases.show', compact('purchase'));
    }



      /*
     |--------------------------------------------------------------------------
     | EDIT METHOD
     |--------------------------------------------------------------------------
    */
    public function edit(Purchase $purchase)
    {
        $this->hasAccess("rst.purchase.edit");

        $items     = Item::items()->select('name', 'id', 'company_id')->get();
        $companies = Company::userCompanies();
        $purchase  = Purchase::where('id', $purchase->id)->with('purchase_details')->first();
        $systemSetting = SystemSetting::where('key', 'general_store_reference_no_change')->first();

        return view('inventory.production.purchases.edit', compact('purchase', 'companies', 'items', 'systemSetting'));
    }



      /*
     |--------------------------------------------------------------------------
     | UPDATE METHOD
     |--------------------------------------------------------------------------
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



     /*
     |--------------------------------------------------------------------------
     | DELETE METHOD
     |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        $this->hasAccess("rst.purchase.delete");    // check permission
        $purchase = RstPurchase::find($id);
        $purchase->purchase_details()->delete();
        $purchase = $purchase->delete();
        return redirect()->back()->with('message', 'Purchase delete success!');
    }



      /*
     |--------------------------------------------------------------------------
     | APPROVED SHOW METHOD
     |--------------------------------------------------------------------------
    */
    public function purchaseApproveShow($id)
    {
        $this->hasAccess("rst.purchase.approve");

        $data['items']     = RestMaterial::select('name', 'id', 'company_id')->get();
        $data['companies'] = Company::userCompanies();
        $data['purchase']  = RstPurchase::where('id', $id)->with('purchase_details.product')->first();


        return view('inventory.production.purchases.approve', $data);
    }



    /*
     |--------------------------------------------------------------------------
     | Purchase Approve METHOD
     |--------------------------------------------------------------------------
    */
    public function purchaseApprove(Request $request, $id)
    {

        try {

            $this->service->ApprovedPurchase($request);

        } catch (\Exception $ex) {

            return redirect()->back()->withInput()->withError($ex->getMessage());

        }

        return redirect()->route('rst.purchase.index')->with('message', 'Purchase approve success!');
    }



     /*
     |--------------------------------------------------------------------------
     | Purchase Unapprove METHOD
     |--------------------------------------------------------------------------
    */
    public function purchaseUnapprove($id)
    {
        // return $id;
        $purchase  = RstPurchase::where('id', $id)->with('purchase_details.product')->first();
        // $this->service->UnaprovedApprovedPurchase($purchase);
        $purchase->update(['is_approved' => 0]);
        return redirect()->route('rst.purchase.index')->with('message', 'Purchase un-approve successfully.');
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
            RstPurchaseDetails::create([
                'purchase_id' => $purchase->id,
                'company_id'  => $request->company_id,
                'item_id'     => $request->item_id[$key],
                'quantity'    => $request->quantity[$key]
            ]);
        }
    }



  /*
     |--------------------------------------------------------------------------
     | GET ITEM WITH COMPANY METHOD
     |--------------------------------------------------------------------------
    */
    public function getItemList(Request $request)
    {
        $data['items'] = RestMaterial::where('company_id', $request->id)->orderBy('name')->pluck('name', 'id');
        return $data;
    }



    //    /**
    //  * ---------------------------------------------------------------------
    //  * AJAX METHOD
    //  * ---------------------------------------------------------------------
    //  */

     public function getProduct(Request $request)
     {
         return Product::where(function($q) use($request) {
                 $q->where("name", "like", "%{$request->search}%")
                     ->orWhere("name", "like", "%{$request->search}")
                     ->orWhere("name", "like", "{$request->search}%");
             })
             ->where('is_matrial', 1)
             ->get()
             ->map(function ($item) {
                 return [
                    'id'            => $item->id,
                    'name'          => $item->name,
                    'barcode'       => $item->barcode,
                    'bar'           => $item->is_bar,
                    'total_qty'     => $item->total_quantity ?? 0,
                    'vat_amount'    => (int)$item->vat_amount,
                    'vat_percent'   => getPercentOfXAmount($item->sale_price, $item->vat_amount),
                    'unit_price'    => $item->unit_cost,
                    'sale_price'    => $item->sale_price,
                    'unit_id'       => $item->unit_id,
                    'pack_unit_id'  => $item->pack_unit_id,
                    'pack_size'     => $item->pack_size,
                    'unit'          => optional($item->unit)->name,
                    'unit_type'     => optional($item->unit)->type,
                    'pack_unit'     => optional($item->pack_unit)->name,
                    'pack_price'    => ($item->is_bar && $item->pack_size != 0) ? $item->sale_price / $item->pack_size : $item->sale_price,
                 ];
             });

     }






}
