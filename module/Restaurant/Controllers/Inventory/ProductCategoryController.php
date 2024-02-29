<?php

namespace Module\Restaurant\Controllers\Inventory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Module\Restaurant\Models\ProductCategory;

class ProductCategoryController extends Controller
{



    /*
     |--------------------------------------------------------------------------
     | INDEX METHOD
     |--------------------------------------------------------------------------
    */
    public function index()
    {
        $this->hasAccess("resturant.inventories.index");

        $data['categories']         = ProductCategory::query()->paginate(25);
        $data['parent_categories']  = ProductCategory::query()->whereNull('parent_id')->get();

        return view('inventory.categories.index', $data);
    }






    /*
     |--------------------------------------------------------------------------
     | STORE METHOD
     |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $this->hasAccess("resturant.inventories.create");

        try {

            $this->storeOrUpdate($request);
        } catch (\Throwable $th) {
            return redirect()->back()->withInput($request->all())->withError($th->getMessage());
        }
        return redirect()->back()->withMessage('Category added success !');
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
            $this->storeOrUpdate($request, $id);
        } catch (\Throwable $th) {
            return redirect()->back()->withInput($request->all())->withError($th->getMessage());
        }
        return redirect()->back()->withMessage('Category update success !');
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
            ProductCategory::query()->find($id)->delete();
        } catch (\Throwable $th) {
            return redirect()->back()->withError($th->getMessage());
        }
        return redirect()->back()->withMessage('Category deleted success !');
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
            'name'      => 'required',
            'parent_id' => 'nullable',
            'status'    => 'nullable',
        ]);

        if (!$id) {
            $data['status'] = 1;
            $data['type']   = 1;
        }

        ProductCategory::updateOrCreate([
            'id'    => $id,
        ], $data);
    }
}
