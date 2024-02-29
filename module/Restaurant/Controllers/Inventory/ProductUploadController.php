<?php

namespace Module\Restaurant\Controllers\Inventory;

use Exception;
use Illuminate\Http\Request;
use Module\Restaurant\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Module\Restaurant\Models\Supplier;
use Module\Restaurant\Models\ProductUnit;
use Module\Restaurant\Models\MedicineType;
use Module\Restaurant\Models\ProductUpload;
use Module\Restaurant\Models\MedicineGeneric;
use Module\Restaurant\Models\ProductCategory;
use Module\Restaurant\Imports\ProductUploadCSV;
use Module\Restaurant\Models\ProductBatch;

class ProductUploadController extends Controller
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
        $data['products'] = ProductUpload::query()->latest()->paginate(50);


        return view('inventory.product.uploads.index', $data);
    }













    /*
     |--------------------------------------------------------------------------
     | CREATE METHOD
     |--------------------------------------------------------------------------
    */
    public function create()
    {
        # code...
    }













    /*
     |--------------------------------------------------------------------------
     | STORE METHOD
     |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {

        if ($request->file('csv_file')) {

            try {
                $data = Excel::import(new ProductUploadCSV(), $request->file('csv_file'));
            } catch (Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }

            return redirect()->back()->with('message', 'File Uploaded Successful');
        } else {

            return redirect()->back()->with('error', 'No file selected.');
        }
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
        $this->hasAccess("pharmacy.edit");

        $data = [
            'product'           => ProductUpload::find($id),
        ];

        return view('inventory.product.uploads.edit', $data);
    }













    /*
     |--------------------------------------------------------------------------
     | UPDATE METHOD
     |--------------------------------------------------------------------------
    */
    public function update($id, Request $request)
    {
        try {
            ProductUpload::find($id)->update($request->all());
        } catch (\Throwable $th) {
            // return $th->getMessage();
            return redirect()->back()->with('error', $th->getMessage());
        }

        return redirect()->route('rst.product-uploads.index')->with('message', 'Product Edited Successful');
    }







    /*
     |--------------------------------------------------------------------------
     | DELETE/DESTORY METHOD
     |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        try {
            ProductUpload::find($id)->delete();

            return redirect()->back()->with('message', 'Product Upload delete successfull.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }









    /*
     |--------------------------------------------------------------------------
     | DELETE/DESTORY METHOD
     |--------------------------------------------------------------------------
    */
    public function deleteAll()
    {
        try {
            ProductUpload::query()->truncate();

            return redirect()->back()->with('message', 'Product Upload Table is empty.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }





    /*
     |--------------------------------------------------------------------------
     | CONTFIRM LIST METHOD
     |--------------------------------------------------------------------------
    */
    public function addToConfirm()
    {
        $getProducts = ProductUpload::take(50)->get();

        $total = $getProducts->count();


        foreach ($getProducts as $product) {

            DB::transaction(function ()  use ($product) {

                $data['name']                   = $product->name;
                $data['barcode']                = $product->barcode;
                $data['category_id']            = $product->category_id;
                $data['package_id']             = $product->package_id;
                $data['pack_unit_id']           = $product->pack_unit_id;
                $data['unit_id']                = $product->unit_id;
                $data['supplier_id']            = $product->supplier_id;
                $data['unit_cost']              = $product->unit_cost;
                $data['sale_price']             = $product->sale_price;
                $data['pack_size']              = $product->pack_size;
                $data['pack_quantity']          = $product->pack_quantity;
                $data['opening_quantity']       = $product->opening_quantity;
                $data['stock_limit']            = $product->stock_limit;
                $data['vat_amount']             = $product->vat_amount;
                $data['available_quantity']     = $product->available_quantity;
                $data['is_matrial']             = $product->is_matrial;
                $data['is_bar']                 = $product->is_bar;

                // $data['category_id']            = $this->category($product->category);



                Product::create($data);


                ProductUpload::where('id', $product->id)->delete();
            });
        }

        // return redirect()->back()->with('message', 'First $total records Add in confirm list');
        return redirect()->back()->with('message', 'First ' . $total . ' records Add in confirm list');
    }



    /*
     |--------------------------------------------------------------------------
     | CATEGORY
     |--------------------------------------------------------------------------
    */
    private function category($name)
    {
        return ProductCategory::firstOrCreate([
            'name'      => $name
        ], [
            'type'      => 1,
            'status'    => 1,
        ])->id;
    }



    /*
     |--------------------------------------------------------------------------
     | GENERIC
     |--------------------------------------------------------------------------
    */
    private function generic($name)
    {
        if ($name) {
            return MedicineGeneric::firstOrCreate([
                'name'      => $name
            ], [
                'status'    => 1,
            ])->id;
        }
    }


    /*
     |--------------------------------------------------------------------------
     | GENERIC
     |--------------------------------------------------------------------------
    */
    private function medicineType($name)
    {
        if ($name) {
            return MedicineType::firstOrCreate([
                'name'      => $name
            ], [
                'status'    => 1,
            ])->id;
        }
    }


    /*
     |--------------------------------------------------------------------------
     | UNIT
     |--------------------------------------------------------------------------
    */
    private function unit($name, $type)
    {
        if ($name) {
            return ProductUnit::firstOrCreate([
                'name'      => $name
            ], [
                'status'    => 1,
                'type'      => $type,
            ])->id;
        }
    }

    /*
     |--------------------------------------------------------------------------
     | SUPPLIER
     |--------------------------------------------------------------------------
    */
    private function supplier($name)
    {
        if ($name) {
            return Supplier::firstOrCreate([
                'name'          => $name
            ], [
                'code'          => Supplier::max('id') + 1,
                'status'        => 1,
            ])->id;
        }
    }


    /*
     |--------------------------------------------------------------------------
     | SUPPLIER
     |--------------------------------------------------------------------------
    */
    private function batch($name)
    {
        if ($name) {

            return ProductBatch::firstOrCreate([
                'batch_number'          => $name
            ])->id;
        }
    }
}
