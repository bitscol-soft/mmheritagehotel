<?php

namespace Module\Bar\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Module\Bar\Models\ProductBatch;

class ProductBatchController extends Controller
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
        $this->hasAccess("bar.inventories.index");

        return view('inventory.batches.index', [
            'batches'   => ProductBatch::latest()->get()
        ]);
    }





    /*
     |--------------------------------------------------------------------------
     | STORE METHOD
     |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $this->hasAccess("bar.inventories.create");

        try {
            $this->storeOrUpdate($request);
        } catch (\Throwable $th) {
            return redirect()->back()->withInput($request->all())->withError($th->getMessage());
        }
        return redirect()->back()->withMessage('Product Batch added success !');
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
            $this->storeOrUpdate($request, $id);
        } catch (\Throwable $th) {
            return redirect()->back()->withInput($request->all())->withError($th->getMessage());
        }
        return redirect()->back()->withMessage('Product Batch update success !');
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
            ProductBatch::find($id)->delete();
        } catch (\Throwable $th) {
            return redirect()->back()->withError($th->getMessage());
        }
        return redirect()->back()->withMessage('Product Batch deleted success !');
    }





    /*
     |--------------------------------------------------------------------------
     | STORE/UPDATE METHOD
     |--------------------------------------------------------------------------
    */
    public function storeOrUpdate($request, $id = null)
    {
        $data = $request->validate([
            'batch_number'      => 'required',
        ]);


        ProductBatch::updateOrCreate([
            'id'    => $id,
        ], $data);
    }
}
