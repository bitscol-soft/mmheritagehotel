<?php

namespace Module\Bar\Controllers\Inventory;

use Module\Bar\Models\Stock;
use Illuminate\Http\Request;
use Module\Bar\Models\Product;
use Module\Bar\Models\Supplier;
use Module\Bar\Models\ProductUnit;
use Illuminate\Support\Facades\DB;
use Module\Bar\Models\ProductBatch;
use App\Http\Controllers\Controller;
use Module\Bar\Models\ProductCategory;
use Module\Bar\Services\PurchaseService;

class ProductController extends Controller
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
        $this->hasAccess("bar.inventories.index");

        $data['products']   = Product::query()->notPackage()
                                        ->with('category', 'unit', 'pack_unit', 'supplier')
                                        ->likeSearch('name')
                                        ->searchByField('barcode')
                                        ->searchByField('category_id')
                                        ->where('is_bar', 1)
                                        ->latest()
                                        ->paginate(25);
        $data['categories'] = ProductCategory::query()->select('id', 'name')->get();

        return view('bar.inventory.product.index', $data);
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
            'units'             => ProductUnit::query()->active()->get(),
            'suppliers'         => Supplier::query()->active()->pluck('name', 'id'),
        ];

        return view('bar.inventory.product.create', $data);
    }













    /*
     |--------------------------------------------------------------------------
     | STORE METHOD
     |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $this->hasAccess("bar.inventories.create");


        // try {
        DB::transaction(function () use ($request) {
            $this->storeOrUpdate($request);


            $this->manageStock($request);
        });
        // } catch (\Throwable $th) {
        //     return $th->getMessage();
        //     return redirect()->back()->withInput($request->all())->withError($th->getMessage());
        // }
        return redirect()->route('bar.products.index')->withMessage('Product created success !');
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
        $this->hasAccess("bar.inventories.edit");

        $data = [
            'categories'        => ProductCategory::query()->active()->whereNull('parent_id')->get(),
            'units'             => ProductUnit::query()->active()->get(),
            'suppliers'         => Supplier::query()->active()->pluck('name', 'id'),
            'product'           => Product::query()->find($id),
        ];

        return view('bar.inventory.product.edit', $data);
    }













    /*
     |--------------------------------------------------------------------------
     | UPDATE METHOD
     |--------------------------------------------------------------------------
    */
    public function update($id, Request $request)
    {
        $this->hasAccess("bar.inventories.edit");

        try {

            DB::transaction(function () use ($request, $id) {
                $this->storeOrUpdate($request, $id);

                $this->manageStock($request);
            });
        } catch (\Throwable $th) {
            throw $th;
            return redirect()->back()->withError($th->getMessage());
        }
        return redirect()->route('bar.products.index')->withMessage('Product edit success !');
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
        $this->hasAccess("bar.inventories.edit");

        $data = $request->validate([
            'name'                  => 'required',
            'category_id'           => 'required',
            'unit_id'               => 'required',
            'supplier_id'           => 'nullable',
            'unit_cost'             => 'required',
            'sale_price'             => 'required',
            'status'                => 'nullable',
            'opening_quantity'      => 'nullable|numeric',
            'pack_size'             => 'nullable|numeric',
            'pack_unit_id'          => 'nullable',
        ]);


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
        return Product::querySum('stocks', 'total_quantity', 'available_quantity')
            ->where('barcode', $request->search)
            ->orWhere(function($q) use($request) {
                $q->where("name", "like", "%{$request->search}%")
                    ->orWhere("name", "like", "%{$request->search}")
                    ->orWhere("name", "like", "{$request->search}%");
            })
            ->when(request()->filled('bar'), fn($q) => $q->where('is_bar', 1))
            // ->where('is_bar', 1)
            ->where('is_matrial', null)
            ->get()
            ->map(function ($item) {
                return [
                    'id'            => $item->id,
                    'name'          => $item->name,
                    'barcode'       => $item->barcode,
                    'bar'           => $item->is_bar,
                    'total_qty'     => $item->total_quantity ?? 0,
                    'available'     => $item->stock[0]->available_quantity ?? 0,
                    'vat_amount'    => (int)$item->vat_amount,
                    'vat_percent'   => getPercentOfXAmount($item->sale_price, $item->vat_amount),
                    'unit_price'    => $item->unit_cost,
                    'sale_price'    => $item->sale_price,
                    'unit_id'       => $item->unit_id,
                    'pack_unit_id'  => $item->pack_unit_id,
                    'pack_size'     => $item->pack_size,
                    'unit'          => optional($item->unit)->name,
                    'unit_type'     => optional($item->unit)->type,
                    'pack_unit'     => optional($item->pack_unit)->name,
                    'pack_price'    => ($item->is_bar && $item->pack_size != 0) ? $item->sale_price / $item->pack_size : $item->sale_price,
                ];
            });

    }



    /**
     * ---------------------------------------------------------------------
     * AJAX METHOD
     * ---------------------------------------------------------------------
     */

    public function getProductAll(Request $request)
    {
        return Product::querySum('stocks', 'total_quantity', 'available_quantity')
            ->where('barcode', $request->search)
            ->orWhere(function($q) use($request) {
                $q->where("name", "like", "%{$request->search}%")
                    ->orWhere("name", "like", "%{$request->search}")
                    ->orWhere("name", "like", "{$request->search}%");
            })
            // ->where('is_bar', 1)
            ->where('is_matrial', null)
            ->get()
            ->map(function ($item) {
                return [
                    'id'            => $item->id,
                    'name'          => $item->name,
                    'barcode'       => $item->barcode,
                    'bar'           => $item->is_bar,
                    'total_qty'     => $item->total_quantity ?? 0,
                    'available'     => $item->stock[0]->available_quantity ?? 0,
                    'vat_amount'    => (int)$item->vat_amount,
                    'vat_percent'   => getPercentOfXAmount($item->sale_price, $item->vat_amount),
                    'unit_price'    => $item->unit_cost,
                    'sale_price'    => $item->sale_price,
                    'unit_id'       => $item->unit_id,
                    'pack_unit_id'  => $item->pack_unit_id,
                    'pack_size'     => $item->pack_size,
                    'unit'          => optional($item->unit)->name,
                    'unit_type'     => optional($item->unit)->type,
                    'pack_unit'     => optional($item->pack_unit)->name,
                    'pack_price'    => ($item->is_bar && $item->pack_size != 0) ? $item->sale_price / $item->pack_size : $item->sale_price,
                ];
            });

    }










    /**
     * ---------------------------------------------------------------------
     * AJAX GET PRODUCT BATCH
     * ---------------------------------------------------------------------
     */
    public function getProductBatch(Request $request)
    {
        return ProductBatch::query()->where("batch_number", "like", "%{$request->number}%")->get();
    }









    /**
     * ---------------------------------------------------------------------
     * MANAGE STOCK
     * ---------------------------------------------------------------------
     */
    public function manageStock($request)
    {
        $productStock   = Stock::query()->where('product_id', $this->product->id)->where('is_bar', 1)->first();


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
        // dd(($productStock->available_quantity - $productStock->opening_quantity) + $request->opening_quantity ?? 0);

        $productStock->update([
            // 'opening_quantity'      => $request->opening_quantity,
            // 'available_quantity'    => ($productStock->available_quantity - $productStock->opening_quantity) + $request->opening_quantity ?? 0,
        ]);
    }







    /**
     * ---------------------------------------------------------------------
     * CREATE STOCK
     * ---------------------------------------------------------------------
     */
    public function stockCreate($request)
    {
        $stock = Stock::query()->create([
            'product_id'            => $this->product->id,
            'opening_quantity'      => $this->product->opening_quantity  ?? 0,
            'purchased_quantity'    => 0,
            'sold_quantity'         => 0,
            'is_bar'                => 1
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
                                        ->where('is_bar', 1)
                                        ->latest()
                                        ->paginate(25);

        $data['categories'] = ProductCategory::query()
                                        ->where('is_bar', 1)
                                        ->select('id', 'name')->get();

        return view('frontend.bar-menu', $data);
    }
}
