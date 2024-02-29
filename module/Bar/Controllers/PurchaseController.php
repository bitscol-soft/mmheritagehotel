<?php

namespace Module\Bar\Controllers;

use Illuminate\Http\Request;
use Module\Bar\Models\Purchase;
use Module\Bar\Models\Supplier;
use Module\Account\Models\Account;
use App\Http\Controllers\Controller;
use Module\Bar\Request\PurchaseRequest;
use Module\Bar\Services\BarTransectionService;
use Module\Bar\Services\PurchaseService;

class PurchaseController extends Controller
{



    /*
     |--------------------------------------------------------------------------
     | INDEX METHOD
     |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $this->hasAccess("bar.purchases.index");

        // return Purchase::orderByDesc('id')->companies()->paginate(20);
        return view('bar.purchase.index', [
            'purchases' => Purchase::query()->orderByDesc('id')->companies()->paginate(20),

        ]);
    }













    /*
     |--------------------------------------------------------------------------
     | CREATE METHOD
     |--------------------------------------------------------------------------
    */
    public function create()
    {
        $this->hasAccess("bar.purchases.create");

        $data['challan_id']     = (new BarTransectionService())->getInvoiceNo('Bar Purchases');
        $data['accounts']       = Account::companies()->get();
        $data['suppliers']      = Supplier::query()->companies()->whereStatus(true)->get();

        return view('bar/purchase-v2/create', $data);
    }













    /*
     |--------------------------------------------------------------------------
     | STORE METHOD
     |--------------------------------------------------------------------------
    */
    public function store(PurchaseRequest $request)
    {
        $this->hasAccess("bar.purchases.create");

        try {

            $purchase = $request->store();

        } catch (\Exception $ex) {

            return redirect()->back()->withInput()->withError($ex->getMessage());

        }


        return redirect()->route('bar.purchases.show', $purchase->id)->withSuccess('Purchase Store in Stock Successfully!');
    }













    /*
     |--------------------------------------------------------------------------
     | SHOW METHOD
     |--------------------------------------------------------------------------
    */
    public function show(Purchase $purchase)
    {
        $this->hasAccess("bar.purchases.view");

        return view('bar.purchase.show', [
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
    public function destroy($id)
    {
        try {
            (new PurchaseService())->delete($id);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error','Purchase deleted Successfully!');
        }
        return redirect()->route('bar.purchases.index')->withSuccess('Purchase deleted Successfully!');

    }
}
