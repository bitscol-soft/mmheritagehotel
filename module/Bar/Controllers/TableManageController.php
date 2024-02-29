<?php

namespace Module\Bar\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Module\Bar\Models\ProductCategory;
use Module\Bar\Models\RstTableManage;

class TableManageController extends Controller
{



    /*
     |--------------------------------------------------------------------------
     | INDEX METHOD
     |--------------------------------------------------------------------------
    */
    public function index()
    {
        $this->hasAccess("bar.table-manages.index");

        $data['table_manages'] = RstTableManage::query()->paginate(25);

        return view('bar.tables.index', $data);
    }






    /*
     |--------------------------------------------------------------------------
     | STORE METHOD
     |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $this->hasAccess("bar.table-manages.create");

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
        $this->hasAccess("bar.table-manages.edit");

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
        $this->hasAccess("bar.table-manages.delete");

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
        $this->hasAccess("bar.table-manages.edit");

        $data = $request->validate([
            'name'      => 'nullable',
            'table_no'  => 'required',
            'status'    => 'nullable',
        ]);

        if (!$id) {
            $data['status'] = 1;
        }

        RstTableManage::updateOrCreate([
            'id'    => $id,
        ], $data);
    }
}
