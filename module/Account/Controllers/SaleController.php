<?php

namespace Module\Account\Controllers;

use App\Models\Company;
use App\Traits\CheckPermission;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Module\Account\Models\Account;
use Module\Account\Models\Customer;
use Module\Account\Models\Product;
use Module\Account\Models\Sale;
use Module\Account\Models\SaleDetail;
use Module\Account\Services\AccSaleService;
use Module\Account\Services\InvoiceNumberService;
use Module\Account\Services\StockService;

class SaleController extends Controller
{
    use CheckPermission;



    private $transactionService;
    private $saleService;
    public $stockService;
    public $invoiceNumberService;










    /*
     |--------------------------------------------------------------------------
     | CONSTRUCTOR METHOD
     |--------------------------------------------------------------------------
    */
    public function __construct()
    {
        $this->invoiceNumberService     = new InvoiceNumberService();
        $this->saleService              = new AccSaleService();
        $this->stockService             = new StockService();
    }










    /*
     |--------------------------------------------------------------------------
     | index METHOD
     |--------------------------------------------------------------------------
    */
    public function index()
    {
        $this->hasAccess("account-sales.index");


        $sales = Sale::latest()->paginate(30);

        return view('sale.sales.index', compact('sales'));
    }










    /*
     |--------------------------------------------------------------------------
     | CREATE METHOD
     |--------------------------------------------------------------------------
    */
    public function create()
    {
        $this->hasAccess("account-sales.create");

        $data['products']   = Product::with('unit')
                            ->withCount(['product_stocks as current_stock'=> fn($q)=> $q->select(DB::raw('SUM(stock)'))])->get();
        $data['companies']  = Company::userCompanies();
        $data['customers']  = Customer::pluck('name', 'id');
        $data['account']    = Account::where('name', 'Cash')->first();

        return view('sale.sales.create', $data);
    }










    /*
     |--------------------------------------------------------------------------
     | STORE/SAVE METHOD
     |--------------------------------------------------------------------------
    */
    public function store(Request $request, int $retryCount = 0)
    {
        // dd($request->all());

        $this->hasAccess("account-sales.create");

        try {

           DB::transaction(function () use($request) {


                $this->saleService->validateData($request);


                $this->saleService->storeSale($request);


                $this->saleService->storeSaleDetails($request);


                $this->saleService->makeTransaction();


                $this->saleService->invoiceNumberService->setNextInvoiceNo($request->company_id, 'Sale', date('Y'));

            });


        } catch (Exception $ex) {

            if ($ex->getCode() == '23000' && $retryCount < 1) {
                $this->saleService->invoiceNumberService->setNextInvoiceNo($request->company_id, 'Sale', date('Y'));

                return $this->store($request, $retryCount + 1);
            }

            return redirect()->back()->withInput()->with('error', $ex->getMessage());
        }


        return redirect()->route('acc-sales.show', $this->saleService->sale->id)->with('message', 'Sale Created Successfully!');

    }










    /*
     |--------------------------------------------------------------------------
     | SHOW/DETAIL METHOD
     |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        $this->hasAccess("account-sales.show");

        $sale = Sale::with('details', 'company')->findOrFail($id);

        return view('sale.sales.invoice', compact('sale'));
    }










    /*
     |--------------------------------------------------------------------------
     | EDIT METHOD
     |--------------------------------------------------------------------------
    */
    public function edit($id)
    {
        $this->hasAccess("account-sales.edit");

        $data['sale']       = Sale::findOrFail($id);
        $data['products']   = Product::select('id', 'name', 'selling_price')->get();
        $data['companies']  = Company::pluck('name', 'id');
        $data['customers']  = Customer::pluck('name', 'id');
        $data['account']    = Account::where('name', 'Cash')->first();

        return view('sale.sales.edit', $data);
    }











    /*
     |--------------------------------------------------------------------------
     | UPDATE METHOD
     |--------------------------------------------------------------------------
    */
    public function update(Request $request, $id)
    {

        $this->hasAccess("account-sales.edit");



        try {


            $this->saleService->validateData($request);

            DB::transaction(function () use($request, $id) {



                $this->saleService->updateSale($request, $id);



                $this->saleService->updateSaleDetails($request);


                $this->saleService->makeTransaction();


            });


        } catch (Exception $ex) {


            return redirect()->back()->withInput()->with('error', $ex->getMessage());
        }


        return redirect()->route('acc-sales.show', $this->saleService->sale->id)->with('message', 'Sale Updated Successfully!');
    }












    /*
     |--------------------------------------------------------------------------
     | DELETE/DESTROY METHOD
     |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        $this->hasAccess("account-sales.delete");

        try {

            DB::transaction(function () use($id) {


                $sale = Sale::find($id);


                $sale->transactions()->delete();


                $saleDetails = SaleDetail::select('id', 'product_id', 'quantity', 'price')
                    ->where('sale_id', $id)
                    ->get();


                foreach ($saleDetails as $detail) {


                    $detail->pos_stocks()->delete();


                    $this->saleService->productStockService->updateStockInHand($detail->product_id, $sale->company_id, $sale->branch_id, date('Y-m-d'));


                    // $this->stockService->deleteStock($detail->product_id, SaleDetail::class, $detail->id, 'Out');

                    // $branchId = null;
                    // $warehouseId = null;

                    // $this->stockService->stockSummary($detail->product_id, $sale->company_id, $branchId, $warehouseId);
                }


                $sale->delete();

            });


            return redirect()->route('acc-sales.index')->with('message', 'Sale Successfully Deleted!');


        } catch (\Exception $ex) {

            return redirect()->back()->withMessage($ex->getMessage());
        }
    }
}
