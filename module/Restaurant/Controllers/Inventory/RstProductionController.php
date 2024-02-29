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
use Module\Restaurant\Models\RstProduction;
use Module\Restaurant\Request\PurchaseRequest;
use Module\Restaurant\Services\PurchaseService;
use Module\Restaurant\Models\RstPurchaseDetails;
use Module\Restaurant\Request\RstPurchasesRequest;
use Module\Restaurant\Request\RstProductionRequest;
use Module\Restaurant\Services\ProductMaterialService;
use Module\Restaurant\Services\ResturentTransectionService;

class RstProductionController extends Controller
{
    use FormNumber, CheckPermission;


    public $service;
    public $product_material;

    public function __construct(PurchaseService $purchaseService)
    {
        $this->service = $purchaseService;
        $this->product_material = new ProductMaterialService();
    }
    /*
     |--------------------------------------------------------------------------
     | INDEX METHOD
     |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $this->hasAccess("rst.purchase.view");   // check permission
        $data['productions']    = RstProduction::with(['metrial_details', 'fgood_details'])
        ->when($request->filled('is_approved') && !$request->filled('is_not_approved'), function ($q) {
                    $q->where('is_approved', 1);
                })->when($request->filled('is_not_approved') && !$request->filled('is_approved'), function ($q) {
                    $q->where('is_approved', 0);
                });


        if ($request->filled('from_date')) {
            $data['productions']->whereDate('date', '>=', Carbon::parse($request->from_date));
        }
        if ($request->filled('to_date')) {
            $data['productions']->whereDate('date', '<=', Carbon::parse($request->to_date));
        }
        if ($request->filled('purchase_number')) {
            $data['productions']->where('challan_no', $request->purchase_number);
        }
        $data['productions']  = $data['productions']->orderByDesc('id')->paginate(30);

        // dd($data['productions']);
        return view('inventory.production.goods_requisitions.index', $data);
    }



    /*
     |--------------------------------------------------------------------------
     | CREATE METHOD
     |--------------------------------------------------------------------------
    */
    public function create()
    {

        $this->hasAccess("rst.purchase.create");
        $items                  = RestMaterial::items()->select('name', 'id', 'company_id')->get();
        $data['challan_id']     = (new ResturentTransectionService())->getInvoiceNo('Rest Production');

        return view('inventory.production.goods_requisitions.create', $data);
    }



     /*
     |--------------------------------------------------------------------------
     | STORE METHOD
     |--------------------------------------------------------------------------
    */
    public function store(RstProductionRequest $request)
    {
        $this->hasAccess("rst.purchase.create");

        try {

            $production = $request->store();

        } catch (\Exception $ex) {

            return redirect()->back()->withInput()->withError($ex->getMessage());

        }

        return redirect()->route('rst.production.index')->with('message', 'Production created successfully!');
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
        $purchase = RstProduction::find($id);
        $purchase->metrial_details()->delete();
        $purchase->fgood_details()->delete();


        $purchase = $purchase->delete();
        return redirect()->back()->with('message', 'Production delete success!');
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
        // dd($data['purchase']);


        return view('inventory.production.purchases.approve', $data);
    }



    /*
     |--------------------------------------------------------------------------
     | Purchase Approve METHOD
     |--------------------------------------------------------------------------
    */
    public function purchaseApprove(Request $request, $id)
    {
        // return $request->all();
        // check validation
        // $this->validate($request, [
        //     'company_id'    => 'required',
        //     'item_id.*'     => 'required',
        //     'quantity.*'    => 'required'
        // ]);

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




    //  /**
    //  * ---------------------------------------------------------------------
    //  * RETURN ITEM LIST FORM THE AJAX REQUEST
    //  * ---------------------------------------------------------------------
    //  */
    public function getMatList(Request $request)
    {
        $data['items'] = Product::where('is_matrial', 1)
                                ->orderBy('name')->pluck('name', 'id');
        return $data;
    }


    public function getProductsList(Request $request)
    {
        $data['products'] = Product::NotBar()->NotMaterial()
                                ->orderBy('name')->pluck('name', 'id');
        return $data;
    }



    public function getMetrialDetails (Request $request)
        {
            $item = Product::where('id', $request->id)->with('unit','stocks')->Material()->first();
            $data['mat_current_stock'] = $item->stocks[0]->available_quantity;
            $data['mat_item_unit']     = $item->unit->name;
            $data['mat_unit_id']       = $item->unit->id;
            $data['mat_name']          = $item->name;
            $data['mat_price']         = $item->sale_price;
            $data['mat_category_id']   = $item->category_id;

            return response()->json($data);
        }


    public function getItemDetails (Request $request)
        {
            $item = Product::where('id', $request->id)->with('unit','stocks')->NotBar()->first();
            $data['current_stock']      = $item->stocks[0]->available_quantity;
            $data['item_name']          = $item->name;
            $data['item_unit']          = $item->unit->name;
            $data['item_price']         = $item->sale_price;
            $data['item_category_id']   = $item->category_id;

            return response()->json($data);
        }



    public function AssignMatrialToProduct(Request $request){

        try {

            $this->product_material->store();

        } catch (\Exception $ex) {

            return redirect()->back()->withInput()->withError($ex->getMessage());
        }

        return redirect()->back()->withSuccess('Assign Material Successfully!');

    }

}
