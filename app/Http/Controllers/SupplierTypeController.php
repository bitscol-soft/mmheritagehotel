<?php

namespace App\Http\Controllers;


use App\Traits\CheckPermission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SupplierType;

class SupplierTypeController extends Controller
{
    use CheckPermission;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->hasAccess('	supplier.types.index');

        $supplierTypes = SupplierType::query();

        if ($request->filled('name')){
            $supplierTypes->where('name', 'like', '%'.$request->name.'%');
        }

        $supplierTypes = $supplierTypes->paginate(15);

        return view('global.supplier-types.index', compact(['supplierTypes']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->hasAccess('	supplier.types.create');

        $request->validate([
            'name.*' => 'required|unique:supplier_types,name'
        ]);

        foreach ($request->name as $key => $name){
            SupplierType::create([
                'name' => $name,
            ]);
        }

        return redirect()->back()->with('message', 'Supplier Type Created Successful.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->hasAccess('	supplier.types.edit');

        $request->validate([
            'name' => 'required|unique:supplier_types,name,'.$id
        ]);

        SupplierType::where('id', $id)->update([
            'name' => $request->name,
        ]);


        return redirect()->back()->with('message', 'Supplier Types Updated Successful.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->hasAccess('	supplier.types.delete');

        SupplierType::where('id', $id)->delete();

        return redirect()->back()->with('message', 'Supplier Type Delete Successful.');
    }
}
