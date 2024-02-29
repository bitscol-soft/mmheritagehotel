<?php

namespace Module\Restaurant\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Module\Restaurant\Models\Product;
use Module\Restaurant\Models\RstTableManage;
use Module\Restaurant\Models\ProductCategory;
use Module\Bar\Services\BarTransectionService;

class InventoryApiController extends Controller
{





    /*
     |--------------------------------------------------------------------------
     | INDEX METHOD
     |--------------------------------------------------------------------------
    */
    public function index()
    {

        try {

            $products   = Product::with('category:id,name', 'unit:id,name', 'pack_unit:id,name', 'supplier:id,name')->likeSearch('name')->searchByField('is_bar')->searchByField('barcode')->searchByField('category_id')->latest()->paginate(25);

            return response()->json([
                'status'    => 1,
                'message'   => 'Success',
                'data'      => $products,
            ]);

        } catch (\Throwable $th) {

            return response()->json([
                'status'    => 0,
                'message'   => 'Error',
                'data'      => $th->getMessage(),
            ]);

        }
    }



    /*
     |--------------------------------------------------------------------------
     | GET TABLE METHOD
     |--------------------------------------------------------------------------
    */
    public function table()
    {


        try {
            $tables   = RstTableManage::pluck('name', 'id');

            return response()->json([
                'status'    => 1,
                'message'   => 'Success',
                'data'      => $tables,
            ]);

        } catch (\Throwable $th) {

            return response()->json([
                'status'    => 0,
                'message'   => 'Error',
                'data'      => $th->getMessage(),
            ]);

        }
    }




    /*
     |--------------------------------------------------------------------------
     | PRODUCT CATEGORY METHOD
     |--------------------------------------------------------------------------
    */
    public function productCategory()
    {

        try {
            $categories   = ProductCategory::with('childCategories')->paginate(25);

            return response()->json([
                'status'    => 1,
                'message'   => 'Success',
                'data'      => $categories,
            ]);

        } catch (\Throwable $th) {

            return response()->json([
                'status'    => 0,
                'message'   => 'Error',
                'data'      => $th->getMessage(),
            ]);

        }
    }



    /*
     |--------------------------------------------------------------------------
     | PRODUCT CATEGORY METHOD
     |--------------------------------------------------------------------------
    */
    public function getInvoice()
    {

        try {
            return response()->json([
                'status'    => 1,
                'message'   => 'Success',
                'data'      => (new BarTransectionService())->getInvoiceNo('Restaurant Sale'),
            ]);

        } catch (\Throwable $th) {

            return response()->json([
                'status'    => 0,
                'message'   => 'Error',
                'data'      => $th->getMessage(),
            ]);

        }
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
             ->where('barcode', $request->search)
             ->orWhere(function($q) use($request) {
                 $q->where("name", "like", "%{$request->search}%")
                     ->orWhere("name", "like", "%{$request->search}")
                     ->orWhere("name", "like", "{$request->search}%");
             })
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
                     'pack_size'     => $item->pack_size,
                     'unit_id'       => $item->unit_id,
                     'pack_unit_id'  => $item->pack_unit_id,
                     'unit'          => optional($item->unit)->name,
                     'pack_unit'     => optional($item->pack_unit)->name,
                     'pack_price'    => $item->is_bar ? number_format($item->sale_price / $item->pack_size, 2, '.', '') : $item->sale_price,
                 ];
             });
    }





    /**
     * ---------------------------------------------------------------------
     * GET PRODUCT DETAILS METHOD
     * ---------------------------------------------------------------------
    */
    public function getProductDetails($productId){

        try {
            $product = Product::where('id', $productId)
                        ->with('category', 'unit', 'pack_unit')
                        // ->sum('stocks', 'total_quantity', 'available_quantity')
                        ->first();

            if ($product != null) {
                return response()->json([
                    'status'    => 0,
                    'message'   => 'Success',
                    'data'      => $product,
                ]);
            } else {
                return response()->json([
                    'status'    => 0,
                    'message'   => 'Product Not Found',
                    'data'      => '',
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status'    => 0,
                'message'   => 'Error',
                'data'      => $th->getMessage(),
            ]);
        }



    }



}
