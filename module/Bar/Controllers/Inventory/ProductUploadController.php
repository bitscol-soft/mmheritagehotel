<?php

namespace Module\Bar\Controllers\Inventory;

use Exception;
use Illuminate\Http\Request;
use Module\Bar\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Module\Bar\Models\Supplier;
use Module\Bar\Models\ProductUnit;
use Module\Bar\Models\MedicineType;
use Module\Bar\Models\ProductUpload;
use Module\Bar\Models\MedicineGeneric;
use Module\Bar\Models\ProductCategory;
use Module\Bar\Imports\ProductUploadCSV;
use Module\Bar\Models\ProductBatch;

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
        $data['products'] = ProductUpload::latest()->paginate(50);


        return view('bar.inventory.product.uploads.index', $data);
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
                return $e->getMessage();
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

        return view('bar.inventory.product.uploads.edit', $data);
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
            return $th->getMessage();
            return redirect()->back()->with('error', $th->getMessage());
        }

        return redirect()->route('bar.product-uploads.index')->with('message', 'Product Edited Successful');
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

        foreach ($getProducts as $product) {

            DB::transaction(function ()  use ($product) {

                $data['name']                   = $product->name;
                $data['category_id']            = $this->category($product->category);
                $data['generic_id']             = $this->generic($product->generic);
                $data['batch_id']               = $this->batch($product->batch_number);
                $data['category_id']            = $this->category($product->category);
                $data['medicine_type_id']       = $this->medicineType($product->medicine_type);
                $data['unit_id']                = $this->unit($product->small_unit, 'retail');
                $data['wholesale_unit_id']      = $this->unit($product->big_unit, 'wholesale');
                $data['supplier_id']            = $product->supplier != '' ? $this->supplier($product->supplier) : null;
                $data['purchase_price']         = $product->purchase_price;
                $data['retail_purchase_price']  = $product->retail_purchase_price;
                $data['sale_price']             = $product->sale_price;
                $data['retail_sale_price']      = $product->retail_sale_price;
                $data['pack_size']              = $product->pack_size;
                $data['small_quantity']         = $product->small_quantity;
                $data['big_quantity']           = $product->big_quantity;

                Product::create($data);


                ProductUpload::where('id', $product->id)->delete();
            });
        }

        return redirect()->back()->with('message', 'First 50 records Add in confirm list');
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
