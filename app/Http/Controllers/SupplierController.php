<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Group;
use Module\GeneralStore\Models\Supplier;
use App\Models\SupplierType;
use App\Traits\CheckPermission;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    use CheckPermission;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->hasAccess("suppliers.view");     // check permission
        $suppliers = Supplier::with('supplier_type')->orderByDesc('id')->get();

        return view('global.suppliers.index', compact('suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->hasAccess("suppliers.create");     // check permission
        $supplier_types = SupplierType::pluck('name', 'id');
        $countries = Country::pluck('name', 'id');

        return view('global.suppliers.create', compact('supplier_types', 'countries'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name'              => 'unique_with:suppliers, name, supplier_type_id',
            'country_id'        => 'required',
            'supplier_type_id'  => 'required'
        ]);

        $data = array_merge($request->all(), ['group_id' => Group::first()->id]);
        unset($data['attention']);

        $created = Supplier::create($data);

        if ($created) {
            return redirect()->route('suppliers.index')->with('message', 'Supplier create success!');
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Supplier  $Supplier
     * @return \Illuminate\Http\Response
     */
    public function edit(Supplier $Supplier)
    {
        $this->hasAccess("suppliers.edit");     // check permission
        $supplier_types = SupplierType::pluck('name', 'id');
        $countries = Country::pluck('name', 'id');

        return view('global.suppliers.edit', compact(['Supplier', 'supplier_types', 'countries']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Supplier  $Supplier
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Supplier $Supplier)
    {
        $suppliers = $this->validate($request, [
            'name'              => 'unique_with:suppliers, name, supplier_type_id,' . $Supplier->id,
            'country_id'        => 'required',
            'supplier_type_id'  => 'required'
        ]);

        $updated = $Supplier::find($Supplier->id)->update(array_merge($suppliers, $request->only([
            'phone', 'email', 'website', 'fax', 'address', 'attention', 'head_office', 'factory_1', 'factory_2'
        ])));

        if ($updated) {
            return redirect()->route('suppliers.index')->with('message', 'Supplier update success!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Supplier  $Supplier
     * @return \Illuminate\Http\Response
     */
    public function destroy(Supplier $Supplier)
    {
        $this->hasAccess("suppliers.delete");     // check permission
        $deleted = $Supplier->delete();

        if ($deleted) {
            return redirect()->back()->with('message', 'Supplier delete success!');
        }
    }

    public function printSuppliers()
    {
        $suppliers = Supplier::with(['group', 'country', 'supplier_type'])->get();
        return view('print_layouts.suppliers', compact('suppliers'));
    }
}
