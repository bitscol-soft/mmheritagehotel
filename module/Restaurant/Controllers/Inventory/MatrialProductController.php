<?php

namespace Module\Restaurant\Controllers\Inventory;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Module\Restaurant\Models\Stock;
use App\Http\Controllers\Controller;
use Module\Restaurant\Models\Product;
use Module\Restaurant\Models\Supplier;
use Module\Restaurant\Models\ProductUnit;
use Module\Restaurant\Models\ProductBatch;
use Module\Restaurant\Models\ProductCategory;
use Module\Restaurant\Services\PurchaseService;

class MatrialProductController extends Controller
{
    private $service;
    private $product;


    /*
     |--------------------------------------------------------------------------
     | CONSTRUCTOR
     |--------------------------------------------------------------------------
    */
    public function __construct(PurchaseService $purchaseService)
    {
        $this->service = $purchaseService;
    }












    /*
     |--------------------------------------------------------------------------
     | INDEX METHOD
     |--------------------------------------------------------------------------
    */
    public function index()
    {
        $this->hasAccess("resturant.inventories.index");

        $data['products']   = Product::query()->Material()
                                        ->with('category', 'unit', 'pack_unit', 'supplier')
                                        ->likeSearch('name')
                                        ->searchByField('barcode')
                                        ->searchByField('category_id')
                                        ->where('is_bar', 0)
                                        ->latest()
                                        ->paginate(25);
        $data['categories'] = ProductCategory::query()->select('id', 'name')->get();

        return view('inventory.mat_product.index', $data);
    }













    /*
     |--------------------------------------------------------------------------
     | CREATE METHOD
     |--------------------------------------------------------------------------
    */
    public function create()
    {
        $this->hasAccess("resturant.inventories.create");

        $data = [
            'categories'        => ProductCategory::query()->active()->whereNull('parent_id')->get(),
            'units'             => ProductUnit::query()->active()->pluck('name', 'id'),
            'suppliers'         => Supplier::query()->active()->pluck('name', 'id'),
        ];

        return view('inventory.mat_product.create', $data);
    }













    /*
     |--------------------------------------------------------------------------
     | STORE METHOD
     |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $this->hasAccess("resturant.inventories.create");

        // try {
        DB::transaction(function () use ($request) {
            $this->storeOrUpdate($request);


            $this->manageStock($request);
        });
        // } catch (\Throwable $th) {
        //     return $th->getMessage();
        //     return redirect()->back()->withInput($request->all())->withError($th->getMessage());
        // }
        return redirect()->route('rst.mat-products.index')->withMessage('Product created success !');
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
        $this->hasAccess("resturant.inventories.edit");

        $data = [
            'categories'        => ProductCategory::query()->active()->whereNull('parent_id')->get(),
            'units'             => ProductUnit::query()->active()->pluck('name', 'id'),
            'suppliers'         => Supplier::query()->active()->pluck('name', 'id'),
            'product'           => Product::find($id),
        ];

        return view('inventory.mat_product.edit', $data);
    }













    /*
     |--------------------------------------------------------------------------
     | UPDATE METHOD
     |--------------------------------------------------------------------------
    */
    public function update($id, Request $request)
    {
        $this->hasAccess("resturant.inventories.edit");

        try {

            DB::transaction(function () use ($request, $id) {
                $this->storeOrUpdate($request, $id);

                $this->manageStock($request);
            });
        } catch (\Throwable $th) {
            throw $th;

            return redirect()->back()->withError($th->getMessage());
        }
        return redirect()->route('rst.mat-products.index')->withMessage('Product edit success !');
    }












    /*
     |--------------------------------------------------------------------------
     | DELETE/DESTORY METHOD
     |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        $this->hasAccess("resturant.inventories.delete");

        try {
            Product::find($id)->delete();
        } catch (\Throwable $th) {
            return redirect()->back()->withError($th->getMessage());
        }
        return redirect()->back()->withMessage('Product deleted success !');
    }




    /*
     |--------------------------------------------------------------------------
     | STORE/UPDATE METHOD
     |--------------------------------------------------------------------------
    */
    public function storeOrUpdate($request, $id = null)
    {
        $this->hasAccess("resturant.inventories.edit");

        $data = $request->validate([
            'name'                  => 'required',
            'category_id'           => 'required',
            'unit_id'               => 'required',
            'supplier_id'           => 'nullable',
            // 'unit_cost'             => 'required',
            // 'sale_price'            => 'required',
            'status'                => 'nullable',
            'is_matrial'            => 'nullable',
        ]);

        $data['unit_cost']          = $request->unit_cost ?? 0;
        $data['sale_price']         = $request->sale_price ?? 0;

        $data['stock_limit']        = $request->stock_limitation ?? 0;
        $data['barcode']            = $request->barcode;
        $data['vat_amount']         = $request->vat_amount;

        if ($id == null) {
            $data['available_quantity'] = $request->opening_quantity ?? 0;
            $data['opening_quantity']   = $request->opening_quantity ?? 0;
        }

        $this->product = Product::updateOrCreate([
            'id'        => $id,
        ], $data);
    }








    /**
     * ---------------------------------------------------------------------
     * AJAX METHOD
     * ---------------------------------------------------------------------
     */

    public function getProduct(Request $request)
    {

        return Product::when(!setting('restaurant_can_sell_bar_product'),fn($q) => $q->where('is_bar', 0))
            ->querySum('stocks', 'total_quantity', 'available_quantity')
            // ->querySum('stock', 'toal_quantity', 'available_quantity')
            ->where('barcode', $request->search)
            ->orWhere(function($q) use($request) {
                $q->where("name", "like", "%{$request->search}%")
                    ->orWhere("name", "like", "%{$request->search}")
                    ->orWhere("name", "like", "{$request->search}%");
            })
            ->where('is_bar', 0)
            ->get()
            ->map(function ($item) {
                return [
                    'id'            => $item->id,
                    'name'          => $item->name,
                    'barcode'       => $item->barcode,
                    'bar'           => $item->is_bar,
                    'total_qty'     => $item->total_quantity,
                    'vat_amount'    => (int)$item->vat_amount,
                    'vat_percent'   => getPercentOfXAmount($item->sale_price, $item->vat_amount),
                    'sale_price'    => $item->sale_price,
                    'unit_id'       => $item->unit_id,
                    'pack_unit_id'  => $item->pack_unit_id,
                    'unit'          => optional($item->unit)->name,
                    'pack_unit'     => optional($item->pack_unit)->name,
                    'pack_price'    => $item->is_bar ? $item->sale_price / $item->pack_size : $item->sale_price,
                ];
            });
    }



    // public function getProduct(Request $request)
    // {
    //     return Product::querySum('stocks', 'total_quantity', 'available_quantity')
    //         ->where('barcode', $request->search)
    //         ->orWhere(function($q) use($request) {
    //             $q->where("name", "like", "%{$request->search}%")
    //                 ->orWhere("name", "like", "%{$request->search}")
    //                 ->orWhere("name", "like", "{$request->search}%");
    //         })
    //         ->when(request()->filled('bar'), fn($q) => $q->where('is_bar', 1))
    //         ->get()
    //         ->map(function ($item) {
    //             return [
    //                 'id'            => $item->id,
    //                 'name'          => $item->name,
    //                 'barcode'       => $item->barcode,
    //                 'bar'           => $item->is_bar,
    //                 'total_qty'     => $item->total_quantity ?? 0,
    //                 'vat_amount'    => (int)$item->vat_amount,
    //                 'vat_percent'   => getPercentOfXAmount($item->sale_price, $item->vat_amount),
    //                 'unit_price'    => $item->unit_cost,
    //                 'sale_price'    => $item->sale_price,
    //                 'unit_id'       => $item->unit_id,
    //                 'pack_unit_id'  => $item->pack_unit_id,
    //                 'pack_size'     => $item->pack_size,
    //                 'unit'          => optional($item->unit)->name,
    //                 'unit_type'     => optional($item->unit)->type,
    //                 'pack_unit'     => optional($item->pack_unit)->name,
    //                 'pack_price'    => ($item->is_bar && $item->pack_size != 0) ? $item->sale_price / $item->pack_size : $item->sale_price,
    //             ];
    //         });

    // }






    /**
     * ---------------------------------------------------------------------
     * AJAX GET PRODUCT BATCH
     * ---------------------------------------------------------------------
     */
    public function getProductBatch(Request $request)
    {
        return ProductBatch::where("batch_number", "like", "%{$request->number}%")->get();
    }









    /**
     * ---------------------------------------------------------------------
     * MANAGE STOCK
     * ---------------------------------------------------------------------
     */
    public function manageStock($request)
    {
        $productStock   = Stock::query()->where('company_id', auth()->user()->company_id)->where('product_id', $this->product->id)->first();


        if ($productStock) {
            $this->stockUpdate($productStock, $request);
        } else {
            $this->stockCreate($request);
        }
    }








    /**
     * ---------------------------------------------------------------------
     * UPDATE STOCK
     * ---------------------------------------------------------------------
     */
    public function stockUpdate($productStock, $request)
    {
        $productStock->update([
            // 'opening_quantity'      => $request->opening_quantity,
            // 'available_quantity'    => ($productStock->available_quantity - $productStock->opening_quantity) + $request->opening_quantity,
        ]);
    }







    /**
     * ---------------------------------------------------------------------
     * CREATE STOCK
     * ---------------------------------------------------------------------
     */
    public function stockCreate($request)
    {
        $stock = Stock::create([
            'product_id'            => $this->product->id,
            'opening_quantity'      => $this->product->opening_quantity ?? 0,
            'purchased_quantity'    => 0,
            'sold_quantity'         => 0,
        ]);
    }




    /*
     |--------------------------------------------------------------------------
     | getMenu METHOD
     |--------------------------------------------------------------------------
    */
    public function getMenu()
    {
        // available_quantity

        $data['products']   = Product::query()->notPackage()
                                        ->with('category', 'unit', 'pack_unit', 'supplier')
                                        ->whereHas('stock', function($q){
                                            $q->where('available_quantity', '>', 0);
                                        })
                                        ->likeSearch('name')
                                        ->searchByField('barcode')
                                        ->searchByField('category_id')
                                        ->where('is_bar', 0)
                                        ->latest()
                                        ->paginate(25);
        $data['categories'] = ProductCategory::query()
                                              ->where('is_bar', 0)
                                              ->select('id', 'name')->get();

        return view('frontend.food-menu', $data);
    }

}
