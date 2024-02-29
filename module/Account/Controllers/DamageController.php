<?php

namespace Module\Account\Controllers;

use App\Models\Company;
use App\Traits\CheckPermission;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Module\Account\Models\Account;
use Module\Account\Models\Damage;
use Module\Account\Models\DamageDetail;
use Module\Account\Models\Product;
use Module\Account\Services\AccDamageService;
use Module\Account\Services\InvoiceNumberService;
use Module\Account\Services\StockService;

class DamageController extends Controller
{
    use CheckPermission;



    private $transactionService;
    private $service;
    public $stockService;










    /*
     |--------------------------------------------------------------------------
     | CONSTRUCTOR METHOD
     |--------------------------------------------------------------------------
    */
    public function __construct()
    {
        $this->invoiceNumberService     = new InvoiceNumberService();
        $this->service                  = new AccDamageService();
        $this->stockService             = new StockService();
    }










    /*
     |--------------------------------------------------------------------------
     | index METHOD
     |--------------------------------------------------------------------------
    */
    public function index()
    {
        $this->hasAccess("account-purchases.index");


        $damages = Damage::latest()->paginate(30);

        return view('product.damages.index', compact('damages'));
    }










    /*
     |--------------------------------------------------------------------------
     | CREATE METHOD
     |--------------------------------------------------------------------------
    */
    public function create()
    {
        $this->hasAccess("account-purchases.create");

        $data['products']   = Product::with('unit')->get();
        $data['companies']  = Company::userCompanies();
        $data['account']    = Account::where('name', 'Cash')->first();

        return view('product.damages.create', $data);
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
            
            $this->service->validateData($request);
            

            DB::transaction(function () use($request) {


                $this->service->storeDamage($request);


                $this->service->storeDamageDetails($request);


                $this->service->makeTransaction();


                $this->service->invoiceNumberService->setNextInvoiceNo($request->company_id, 'Damage', date('Y'));

            });

        } catch (Exception $ex) {

            return redirect()->back()->with('error', $ex->getMessage());
        }


        return redirect()->back()->with('message', 'Damage Created Successfully!');

    }










    /*
     |--------------------------------------------------------------------------
     | SHOW/DETAIL METHOD
     |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        $this->hasAccess("account-purchases.show");

        $damage = Damage::with('details')->find($id);

        return view('product.damages.invoice', compact('damage'));
    }












    /*
     |--------------------------------------------------------------------------
     | DELETE/DESTROY METHOD
     |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        $this->hasAccess("account-purchases.delete");

        // try {


            DB::transaction(function () use($id) {


                $damage = Damage::find($id);


                optional($damage->transactions())->delete();


                $details = DamageDetail::where('damage_id', $id)->get();


                foreach ($details as $detail) {
                    
                    $detail->pos_stocks()->delete();


                    $this->service->productStockService->updateStockInHand($detail->product_id, $damage->company_id, $damage->branch_id, date('Y-m-d'));

                    $detail->delete();
                }


                $damage->delete();

            });


            return redirect()->back()->with('message', 'Damage Successfully Deleted!');


        // } catch (\Exception $ex) {

        //     return redirect()->back()->withMessage($ex->getMessage());
        // }
    }
}
