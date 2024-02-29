<?php

namespace Module\Bar\Controllers\Inventory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Module\Bar\Models\Product;
use Module\Bar\Models\Stock;

class InventoryController extends Controller
{
    private $service;


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
        $this->hasAccess("bar.inventories.view");


        $data['products']   = Product::searchByField('id')->searchByField('is_bar')->with('category:id,name')
            ->querySum('stocks', 'available_quantity',  'available_quantity')
            ->querySum('stocks', 'sold_quantity',       'sold_quantity')
            ->querySum('stocks', 'return_quantity',     'return_quantity')
            ->querySum('stocks', 'purchased_quantity',  'purchased_quantity')
            ->querySum('stocks', 'opening_quantity',    'opening_quantity')
            ->paginate(25);


        return view('bar.inventory.inventory-report', $data);
    }













    /*
     |--------------------------------------------------------------------------
     | CREATE METHOD
     |--------------------------------------------------------------------------
    */
    public function create()
    {
        // $data = [
        //     'categories'        => ProductCategory::active()->pluck('name', 'id'),
        //     'units'             => ProductUnit::active()->get(['name', 'id', 'type']),
        //     'manufacturers'     => Manufacturer::active()->pluck('name', 'id'),
        //     'medicine_types'    => MedicineType::active()->pluck('name', 'id'),
        //     'generics'          => MedicineGeneric::active()->pluck('name', 'id'),
        // ];

        // return view('bar.inventory.product.create', $data);
    }













    /*
     |--------------------------------------------------------------------------
     | STORE METHOD
     |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {

        // try {
        //     $this->storeOrUpdate($request);
        // } catch (\Throwable $th) {
        //     return $th->getMessage();
        //     return redirect()->back()->withInput($request->all())->withError($th->getMessage());
        // }
        // return redirect()->route('bar.products.index')->withMessage('Product created success !');
    }













    /*
     |--------------------------------------------------------------------------
     | SHOW METHOD
     |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        # code...
    }













    /*
     |--------------------------------------------------------------------------
     | EDIT METHOD
     |--------------------------------------------------------------------------
    */
    public function edit($id)
    {
        // $data = [
        //     'categories'        => ProductCategory::active()->pluck('name', 'id'),
        //     'units'             => ProductUnit::active()->get(['name', 'id', 'type']),
        //     'manufacturers'     => Manufacturer::active()->pluck('name', 'id'),
        //     'medicine_types'    => MedicineType::active()->pluck('name', 'id'),
        //     'generics'          => MedicineGeneric::active()->pluck('name', 'id'),
        //     'product'           => Product::find($id),
        // ];

        // return view('bar.inventory.product.edit', $data);
    }













    /*
     |--------------------------------------------------------------------------
     | UPDATE METHOD
     |--------------------------------------------------------------------------
    */
    public function update($id, Request $request)
    {
        // try {
        //     $this->storeOrUpdate($request, $id);
        // } catch (\Throwable $th) {
        //     return redirect()->back()->withError($th->getMessage());
        // }
        // return redirect()->route('bar.products.index')->withMessage('Product edit success !');
    }












    /*
     |--------------------------------------------------------------------------
     | DELETE/DESTORY METHOD
     |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        // try {
        //     Product::find($id)->delete();
        // } catch (\Throwable $th) {
        //     return redirect()->back()->withError($th->getMessage());
        // }
        // return redirect()->back()->withMessage('Product deleted success !');
    }




    /*
     |--------------------------------------------------------------------------
     | STORE/UPDATE METHOD
     |--------------------------------------------------------------------------
    */
    public function storeOrUpdate($request, $id = null)
    {
        // $data = $request->validate([
        //     'name'                  => 'required',
        //     'category_id'           => 'required',
        //     'unit_id'               => 'required',
        //     'generic_id'            => 'required',
        //     'wholesale_unit_id'     => 'required',
        //     'medicine_type_id'      => 'required',
        //     'manufacturer_id'       => 'required',
        //     'unit_cost'             => 'required',
        //     'sale_price'            => 'required',
        //     'retail_quantity'       => 'nullable|numeric',
        //     'retail_unit_tp'        => 'required',
        //     'retail_sales_price'    => 'required',
        //     'pack_size'             => 'required',
        //     'status'                => 'nullable',
        // ]);

        // $data['stock_limitation']   = $request->stock_limitation;

        // Product::updateOrCreate([
        //     'id'    => $id,
        // ], $data);
    }








    /**
     * ---------------------------------------------------------------------
     * AJAX METHODS - GET DRUGS
     * ---------------------------------------------------------------------
     **/

    public function getDrug(Request $request)
    {
        return Stock::query()->with('category', 'unit', 'brand', 'medicineType')
            ->whereCompanyId(company_id())
            ->where('name', 'LIKE', "%{$request->query('name')}%")
            ->take(15)
            ->get();
    }







    /**
     * ---------------------------------------------------------------------
     * AJAX METHODS - GET PURCHASABLE PRODUCTS
     * ---------------------------------------------------------------------
     **/

    public function getPurchasableProduct($id)
    {
        $data['products'] = Stock::companies()->whereBrandId($id)->get();

        return view('purchase.ajax.products', $data);
    }
}
