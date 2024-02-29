<?php

namespace Module\Bar\Controllers\Inventory;

use Illuminate\Http\Request;
use Module\Bar\Models\Stock;
use Module\Bar\Models\Product;
use Module\Bar\Models\Supplier;
use Illuminate\Support\Facades\DB;
use Module\Bar\Models\ProductUnit;
use App\Http\Controllers\Controller;
use Module\Bar\Models\ProductPackage;
use Module\Bar\Models\ProductCategory;
use Module\Bar\Services\PurchaseService;

class ProductPackageController extends Controller
{
    private $product;
    private $package;
    /*
     |--------------------------------------------------------------------------
     | CONSTRUCTOR
     |--------------------------------------------------------------------------
    */
    public function __construct()
    {
    }












    /*
     |--------------------------------------------------------------------------
     | INDEX METHOD
     |--------------------------------------------------------------------------
    */
    public function index()
    {
        $this->hasAccess("bar.inventories.index");

        $data['packages']   = ProductPackage::paginate(25);
        // return 'ok';
        return view('bar.inventory.product.package.index', $data);
    }













    /*
     |--------------------------------------------------------------------------
     | CREATE METHOD
     |--------------------------------------------------------------------------
    */
    public function create()
    {
        $this->hasAccess("bar.inventories.create");

        $data = [
            'categories'        => ProductCategory::query()->active()->whereNull('parent_id')->get(),
            'units'             => ProductUnit::query()->active()->where('type', 'package')->get(),
            'suppliers'         => Supplier::query()->active()->pluck('name', 'id'),
            'products'          => Product::query()->active()->pluck('name', 'id'),
        ];

        return view('bar.inventory.product.package.create', $data);
    }













    /*
     |--------------------------------------------------------------------------
     | STORE METHOD
     |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        // return $request->all();
        $this->hasAccess("bar.inventories.create");


        try {
            DB::transaction(function () use ($request) {
                $this->storeOrUpdate($request);
                $this->stockCreate();

            });
        } catch (\Throwable $th) {
            return $th->getMessage();
            return redirect()->back()->withInput($request->all())->withError($th->getMessage());
        }
        return redirect()->route('bar.packages.index')->withMessage('Package created success !');
    }










    /*
     |--------------------------------------------------------------------------
     | DELETE/DESTORY METHOD
     |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        $this->hasAccess("bar.inventories.delete");

        try {
            DB::transaction(function () use($id){

                $product_package = ProductPackage::with('product_package_details', 'package_product')->where('id', $id)->first();

                if($product_package->product_package_details){
                    $product_package->product_package_details()->delete();
                }

                if($product_package->package_product){
                    Stock::where('product_id', optional($product_package->package_product)->id)->delete();
                    $product_package->package_product()->delete();
                }

                $product_package->destroy($id);

            });

        } catch (\Throwable $th) {
            return redirect()->back()->withError($th->getMessage());
        }
        return redirect()->back()->withMessage('Package deleted success !');
    }















    public function storeOrUpdate($request, $id = null)
    {
        $this->hasAccess("bar.inventories.edit");

        $this->package = ProductPackage::create([
            'name'          => $request->name,
            'price'         => $request->sale_price ?? 0,
        ]);

        $this->product = Product::updateOrCreate([
            'id'                    => $id,
        ],[
            'name'                  => $request->name,
            'category_id'           => $request->category_id,
            'supplier_id'           => $request->supplier_id,
            'unit_id'               => $request->unit_id,
            'unit_cost'             => $request->unit_cost,
            'sale_price'            => $request->sale_price,
            'stock_limit'           => $request->stock_limitation,
            'opening_quantity'      => $request->opening_quantity,
            'vat_amount'            => $request->vat_amount ?? 0,
            'package_id'            => $this->package->id,
            'barcode'               => $request->barcode,
            'is_bar'                => $request->bar_or_restaurant,
        ]);

        foreach ($request->product_ids as $key => $product_id) {
            $this->package->product_package_details()->create([
                'product_id'    =>  $product_id,
                'quantity'      => $request->quantity[$key],
            ]);
        }

    }



    public function stockCreate()
    {
        Stock::query()->create([
            'product_id'            => $this->product->id,
            'opening_quantity'      => $this->product->opening_quantity  ?? 0,
            'purchased_quantity'    => 0,
            'sold_quantity'         => 0,
            'is_bar'                => 1
        ]);
    }




}
