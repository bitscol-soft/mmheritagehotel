<?php

namespace Module\Account\Controllers;

use Illuminate\Http\Request;
use App\Traits\CheckPermission;
use Module\Account\Models\Unit;
use Illuminate\Support\Facades\DB;
use Module\Account\Models\Product;
use Module\Account\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Artisan;
use Module\Account\Services\ProductStockService;

class ProductController extends Controller
{
    use CheckPermission;


    public $stockService;








    /*
     |--------------------------------------------------------------------------
     | CONSTRUCTOR
     |--------------------------------------------------------------------------
    */
    public function __construct()
    {

        $this->stockService       = new ProductStockService();
    }





    public function index()
    {
        
        $this->hasAccess("account-products.index");

        $products   = Product::userCompanies()->with('category', 'unit')->whereIn('product_type', ['0', 'account_prod'])->userLog()->latest()->get();

        return view('product.products.index', compact('products'));
    }




    public function create()
    {
        $this->hasAccess("account-products.create");

        $categories = Category::orderBy('name')->pluck('name', 'id');
        $units      = Unit::orderBy('name')->pluck('name', 'id');

        return view('product.products.create', compact('categories', 'units'));
    }



    // : RedirectResponse
    public function store(Request $request)
    {
        $this->hasAccess("account-products.create");

        $request->validate([
            'name'          => 'required',
            'category_id'   => 'required',
            'unit_id'       => 'required'
        ]);

        DB::beginTransaction();


        $product = Product::create([

            'name'              => $request->name,
            'category_id'       => $request->category_id,
            'unit_id'           => $request->unit_id,
            'purchase_price'    => $request->purchase_price ?? 0,
            'product_type'      => 'account_prod',
            'selling_price'     => $request->selling_price ?? 0,
            'opening_quantity'  => $request->opening_quantity ?? 0,
            'current_stock'     => 0.00,
            'description'       => $request->description,
        ]);

        $product->update([

            'product_code' => 'prod-' . $product->id . '-' . time()
        ]);



        // $this->stockService->storeRequisitionStock($product->id, ('product-10000' . $product->id), "Account Product Opening", date('Y-m-d'), 0, $product->opening_quantity, $product->id, 0, $product->purchase_price, $request->company_id, $request->factory_id);


        // $this->stockService->updateRmStock($product->id, $request->company_id, $request->factory_id, $request->req_purchase_receive_date);


        DB::commit();

        return redirect()->route('products.index')->with('message', 'Product Create Successful');
    }





    public function edit(Product $product)
    {
        $this->hasAccess("account-products.edit");

        $categories = Category::orderBy('name')->pluck('name', 'id');
        $units      = Unit::orderBy('name')->pluck('name', 'id');

        return view('product.products.edit', compact('product', 'categories', 'units'));
    }





    public function update(Request $request, Product $product): RedirectResponse
    {
        $this->hasAccess("account-products.edit");

        $request->validate([
            'name'          => 'required',
            'category_id'   => 'required',
            'unit_id'       => 'required'
        ]);

        $product->update(
            [
                'name'              => $request->name,
                'category_id'       => $request->category_id,
                'unit_id'           => $request->unit_id,
                'purchase_price'    => $request->purchase_price ?? 0,
                'selling_price'     => $request->selling_price ?? 0,
                'opening_quantity'  => $request->opening_quantity ?? 0,
                'product_type'      => 'account_prod',
                'current_stock'     => 0.00,
                'description'       => $request->description,
            ]
        );

        return redirect()->route('products.index')->with('message', 'Product Update Successful');
    }






    public function destroy($id)
    {
        $this->hasAccess("account-products.delete");

        try {
            Product::destroy($id);

            return redirect()->route('products.index')->with('message', 'Product Successfully Deleted!');
        } catch (\Exception $ex) {
            return redirect()->back()->with('error', $ex->getMessage());
        }
    }
}
