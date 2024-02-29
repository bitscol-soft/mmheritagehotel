<?php

namespace Module\Bar\Controllers\Inventory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Module\Bar\Models\MedicineGeneric;
use Module\Bar\Models\ProductUnit;

class ProductUnitController extends Controller
{


    /*
     |--------------------------------------------------------------------------
     | INDEX METHOD
     |--------------------------------------------------------------------------
    */
    public function index()
    {
        $this->hasAccess("pharmacy.index");

        $data['units'] = ProductUnit::query()->paginate(25);

        return view('bar.inventory.units.index', $data);
    }







    /*
     |--------------------------------------------------------------------------
     | STORE METHOD
     |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $this->hasAccess("pharmacy.create");

        try {
            $this->storeOrUpdate($request);
        } catch (\Throwable $th) {
            return redirect()->back()->withInput($request->all())->withError($th->getMessage());
        }
        return redirect()->back()->withMessage('Unit added success !');
    }





    /*
     |--------------------------------------------------------------------------
     | UPDATE METHOD
     |--------------------------------------------------------------------------
    */
    public function update($id, Request $request)
    {
        $this->hasAccess("pharmacy.edit");

        try {
            $this->storeOrUpdate($request, $id);
        } catch (\Throwable $th) {
            return redirect()->back()->withInput($request->all())->withError($th->getMessage());
        }
        return redirect()->back()->withMessage('Unit update success !');
    }






    /*
     |--------------------------------------------------------------------------
     | DELETE/DESTORY METHOD
     |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        $this->hasAccess("pharmacy.delete");

        try {
            ProductUnit::find($id)->delete();
        } catch (\Throwable $th) {
            return redirect()->back()->withError($th->getMessage());
        }
        return redirect()->back()->withMessage('Unit deleted success !');
    }






    /*
     |--------------------------------------------------------------------------
     | STORE/UPDATE METHOD
     |--------------------------------------------------------------------------
    */
    public function storeOrUpdate($request, $id = null)
    {
        $data = $request->validate([
            'name'      => 'required',
            'status'    => 'nullable',
            'type'      => 'nullable',
        ]);

        if (!$id) {
            $data['status'] = 1;
        }

        ProductUnit::updateOrCreate([
            'id'    => $id,
        ], $data);
    }
}
