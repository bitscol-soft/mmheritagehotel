<?php

namespace Module\Account\Controllers;

use App\Models\Company;
use App\Traits\CheckPermission;
use Exception;
use Illuminate\Http\Request;
use Module\Account\Models\Product;
use Module\Account\Models\Purchase;
use Illuminate\Support\Facades\DB;
use Module\Account\Models\Account;

use Module\Account\Models\PurchaseDetail;
use Module\Account\Models\Supplier;
use Module\Account\Services\AccountTransactionService;
use Module\Account\Services\AccPurchaseService;
use Module\Account\Services\ProductStockService;
use Module\Account\Services\StockService;

class PurchaseController extends Controller
{
    use CheckPermission;


    public $transactionService;
    public $purchaseService;
    public $stockService;
    public $productStockService;


    /*
     |--------------------------------------------------------------------------
     | CONSTRUCTOR METHOD
     |--------------------------------------------------------------------------
    */
    public function __construct()
    {
        $this->transactionService   = new AccountTransactionService();
        $this->purchaseService      = new AccPurchaseService();
        $this->stockService         = new StockService();
        $this->productStockService  = new ProductStockService();
    }



    /*
     |--------------------------------------------------------------------------
     | index METHOD
     |--------------------------------------------------------------------------
    */
    public function index()
    {
        $this->hasAccess("account-purchases.index");

        $purchases = Purchase::latest()->paginate(30);

        return view('purchase.purchases.index', compact('purchases'));
    }




    /*
     |--------------------------------------------------------------------------
     | CREATE METHOD
     |--------------------------------------------------------------------------
    */
    public function create()
    {
        $this->hasAccess("account-purchases.create");

        $data['products']   = Product::with('unit')
                            ->withCount(['product_stocks as current_stock'=> fn($q)=> $q->select(DB::raw('SUM(stock)'))])
                            ->get();
        $data['companies']  = Company::userCompanies();
        $data['suppliers']  = Supplier::select('id', 'name')->get();
        $data['account']    = Account::where('name', 'Cash')->first();


        return view('purchase.purchases.create', $data);
    }



    /*
     |--------------------------------------------------------------------------
     | STORE/SAVE METHOD
     |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $this->hasAccess("account-purchases.create");


        try {

            $this->purchaseService->validateData($request);

            $this->purchaseService->invoiceNumberService->setNextInvoiceNo($request->company_id, 'Purchase', date('Y'));

            DB::transaction(function () use($request) {

                $this->purchaseService->storePurchase($request);

                $this->purchaseService->storePurchaseDetails($request);

                $this->purchaseService->makeTransaction();

                $this->purchaseService->invoiceNumberService->setNextInvoiceNo($request->company_id, 'Purchase', date('Y'));
            });


        } catch (Exception $ex) {


            return redirect()->back()->with('error', $ex->getMessage());
        }

        return redirect()->route('acc-purchases.show', $this->purchaseService->purchase->id)->with('message', 'Purchase Created Successfully!');

    }



    /*
     |--------------------------------------------------------------------------
     | SHOW/DETAIL METHOD
     |--------------------------------------------------------------------------
    */
    public function show($purchase)
    {
        $this->hasAccess("account-purchases.show");

        $purchase = Purchase::with('details', 'company')->find($purchase);

        return view('purchase.purchases.invoice', compact('purchase'));
    }



    /*
     |--------------------------------------------------------------------------
     | EDIT METHOD
     |--------------------------------------------------------------------------
    */
    public function edit($id)
    {
        $this->hasAccess("account-purchases.edit");

        $data['purchase']       = Purchase::find($id);
        $data['products']       = Product::select('id', 'name', 'purchase_price')->get();
        $data['companies']      = Company::pluck('name', 'id');
        $data['suppliers']      = Supplier::pluck('name', 'id');
        $data['account']        = Account::where('name', 'Cash')->first();

        return view('purchase.purchases.edit', $data);
    }




    /*
     |--------------------------------------------------------------------------
     | UPDATE METHOD
     |--------------------------------------------------------------------------
    */
    public function update(Request $request, $id)
    {
        $this->hasAccess("account-purchases.edit");


        try {

            DB::transaction(function () use($request, $id) {


                $this->purchaseService->validateData($request);


                $this->purchaseService->updatePurchase($request, $id);


                $this->purchaseService->updatePurchaseDetails($request);


                $this->purchaseService->makeTransaction();


            });


        } catch (Exception $ex) {


            return redirect()->back()->withInput()->with('error', $ex->getMessage());
        }


        return redirect()->route('acc-purchases.show', $this->purchaseService->purchase->id)->with('message', 'Purchase Updated Successfully!');
    }




    /*
     |--------------------------------------------------------------------------
     | DELETE/DESTROY METHOD
     |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        $this->hasAccess("account-purchases.delete");

        try {

            DB::transaction(function () use($id) {


                $purchase = Purchase::find($id);


                $purchase->transactions()->delete();


                $purchaseDetails = PurchaseDetail::select('id', 'product_id', 'quantity', 'price')
                    ->where('purchase_id', $id)
                    ->get();


                foreach ($purchaseDetails as $detail) {

                    $detail->pos_stocks()->delete();

                    $this->productStockService->updateStockInHand($detail->product_id, $purchase->company_id, $purchase->branch_id, date('Y-m-d'));
                }

                $purchase->delete();

            });


            return redirect()->route('acc-purchases.index')->with('message', 'Purchase Successfully Deleted!');


        } catch (\Exception $ex) {

            return redirect()->back()->withErrors($ex->getMessage());
        }
    }
}
