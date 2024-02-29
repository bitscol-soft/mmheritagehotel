<?php

namespace Module\Restaurant\Controllers\Inventory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Module\Restaurant\Models\Manufacturer;

class ManufacturerController extends Controller
{


    /*
     |--------------------------------------------------------------------------
     | INDEX METHOD
     |--------------------------------------------------------------------------
    */
    public function index()
    {
        $this->hasAccess("resturant.inventories.index");

        $data['manufacturers'] = Manufacturer::query()->paginate(25);

        return view('inventory.manufacturers.index', $data);
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
        return redirect()->back()->withMessage('Manufacturer added success !');
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
        return redirect()->back()->withMessage('Manufacturer update success !');
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
            Manufacturer::find($id)->delete();
        } catch (\Throwable $th) {
            return redirect()->back()->withError($th->getMessage());
        }
        return redirect()->back()->withMessage('Manufacturer deleted success !');
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
            'status'    => 'nullable',
        ]);


        Manufacturer::updateOrCreate([
            'id'    => $id,
        ], $data);
    }
}
