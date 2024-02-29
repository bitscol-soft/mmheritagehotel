<?php

namespace Module\Restaurant\Controllers;

use Carbon\Carbon;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Models\SystemSetting;
use Module\Account\Models\Account;
use App\Http\Controllers\Controller;
use Module\Restaurant\Models\Purchase;
use Module\Restaurant\Models\Supplier;
use Module\Restaurant\Models\RstPurchase;
use Module\Restaurant\Models\RestMaterial;
use Module\Restaurant\Request\PurchaseRequest;
use Module\Restaurant\Services\PurchaseService;
use Module\Restaurant\Services\ResturentTransectionService;


class PurchaseController extends Controller
{


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


        $this->hasAccess("resturant.purchases.index");

        $this->hasAccess("rst.purchase.view");   // check permission
        $data['companies']    = Company::userCompanies();
        $data['suppliers']      = Supplier::query()->companies()->whereStatus(true)->get();
        $data['systemSetting'] = SystemSetting::where('key', 'general_store_reference_no_change')->first();

        $data['purchases']    = Purchase::with(['company', 'created_user', 'updated_user','purchase_details'])
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
            $data['purchases']->whereDate('date', '>=', Carbon::parse($request->from_date));
        }
        if ($request->filled('to_date')) {
            $data['purchases']->whereDate('date', '<=', Carbon::parse($request->to_date));
        }
        if ($request->filled('purchase_number')) {
            $data['purchases']->where('challan_id', $request->purchase_number);
        }
        $data['purchases']  = $data['purchases']->orderByDesc('id')->paginate(30);

        return view('purchase-v2.index', $data);
    }













    /*
     |--------------------------------------------------------------------------
     | CREATE METHOD
     |--------------------------------------------------------------------------
    */
    public function create()
    {
        $this->hasAccess("resturant.purchases.create");

        $purchase               = Purchase::query()->latest()->first();
        $data['challan_id']     = (new ResturentTransectionService())->getInvoiceNo('Rest Purchases');

        $data['accounts']       = Account::companies()->get();
        $data['suppliers']      = Supplier::query()->companies()->whereStatus(1)->get();

        return view('purchase-v2.create', $data);
    }













    /*
     |--------------------------------------------------------------------------
     | STORE METHOD
     |--------------------------------------------------------------------------
    */
    public function store(PurchaseRequest $request)
    {
        $this->hasAccess("resturant.purchases.create");

        try {
            $purchase = $request->store();
        } catch (\Exception $ex) {


            return redirect()->back()->withInput()->withError($ex->getMessage());
        }


        return redirect()->route('rst.purchases.show', $purchase->id)->withSuccess('Purchase Store in Stock Successfully!');
    }













    /*
     |--------------------------------------------------------------------------
     | SHOW METHOD
     |--------------------------------------------------------------------------
    */
    public function show(Purchase $purchase)
    {
        $this->hasAccess("resturant.purchases.view");

        return view('purchase-v2.show', [
            'purchases' => $purchase->load('company', 'purchase_details.product'),
        ]);
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
    public function destroy(Purchase $purchase)
    {
        $this->hasAccess("purchases.delete");    // check permission

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


        return view('purchase-v2.approve', $data);
    }



    /*
     |--------------------------------------------------------------------------
     | Purchase Approve METHOD
     |--------------------------------------------------------------------------
    */
    public function purchaseApprove(Request $request, $id)
    {
        try {

            $this->service->ApprovedRstPurchase($request);

        } catch (\Exception $ex) {

            return redirect()->back()->withInput()->withError($ex->getMessage());

        }

        return redirect()->route('rst.purchases.index')->with('message', 'Purchase approve success!');
    }

}
