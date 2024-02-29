<?php

namespace Module\Restaurant\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Module\Restaurant\Models\RstTableManage;

class TableManageController extends Controller
{



    /*
     |--------------------------------------------------------------------------
     | INDEX METHOD
     |--------------------------------------------------------------------------
    */
    public function index()
    {
        $this->hasAccess("restaurant.table-manages.index");

        $data['table_manages'] = RstTableManage::query()->paginate(25);

        return view('rst.tables.index', $data);
    }






    /*
     |--------------------------------------------------------------------------
     | STORE METHOD
     |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $this->hasAccess("restaurant.table-manages.create");

        try {

            $this->storeOrUpdate($request);

        } catch (\Throwable $th) {

            return redirect()->back()->withInput($request->all())->withError($th->getMessage());

        }
        return redirect()->back()->withMessage('Table added success !');
    }






    /*
     |--------------------------------------------------------------------------
     | UPDATE METHOD
     |--------------------------------------------------------------------------
    */
    public function update($id, Request $request)
    {
        $this->hasAccess("restaurant.table-manages.edit");

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
        $this->hasAccess("restaurant.table-manages.delete");

        try {

            RstTableManage::query()->find($id)->delete();

        } catch (\Throwable $th) {

            return redirect()->back()->withError($th->getMessage());
        }

        return redirect()->back()->withMessage('Table deleted success !');
    }






    /*
     |--------------------------------------------------------------------------
     | STORE/UPDATE METHOD
     |--------------------------------------------------------------------------
    */
    public function storeOrUpdate($request, $id = null)
    {
        $this->hasAccess("pharmacy.edit");

        $data = $request->validate([
            'name'      => 'nullable',
            'table_no'  => 'required',
            'status'    => 'nullable',
        ]);

        if (!$id) {
            $data['status'] = 1;
            $data['is_bar'] = 0;
        }

        RstTableManage::updateOrCreate([
            'id'    => $id,
        ], $data);
    }
}
