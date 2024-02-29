<?php

namespace Module\GeneralStore\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Module\GeneralStore\Models\ItemUnit;
use App\Traits\CheckPermission;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemUnitController extends Controller
{
    use CheckPermission;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->hasAccess("items.view");   // check permission


        $item_units = ItemUnit::orderByDesc('id')->paginate(30);

        return view('item-units.index', compact('item_units'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->hasAccess("items.create");   // check permission
        $companies = Company::userCompanies();

        return view('item-units.create', compact('companies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $item_units = $this->validate($request, [
            'name'       => 'required|unique:item_units,name',
            'conversion' => 'required',
            'status'     => 'required'
        ]);

        try {
            $created = ItemUnit::create(array_merge($item_units, ['group_id' => 1]));
            return redirect()->route('item-units.index')->with('message', 'Item unit create success!');
        } catch (Exception $ex) {
            return redirect()->back('item-units.index')->with('error', 'Some error please check!');
        }
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ItemUnit  $itemUnit
     * @return \Illuminate\Http\Response
     */
    public function edit(ItemUnit $itemUnit)
    {
        $this->hasAccess("items.edit");   // check permission

        return view('item-units.edit', compact('itemUnit'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ItemUnit  $itemUnit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ItemUnit $itemUnit)
    {
        $item_units = $this->validate($request, [
            'name'       => 'required|required:item_units,name,' . $itemUnit->id,
            'conversion' => 'required',
            'status'     => 'required'
        ]);

        $updated = $itemUnit->update($item_units);

        if ($updated) {
            return redirect()->route('item_units.index')->with('message', 'Item unit update success!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ItemUnit  $itemUnit
     * @return \Illuminate\Http\Response
     */
    public function destroy(ItemUnit $itemUnit)
    {
        $this->hasAccess("items.delete");   // check permission
        $deleted = $itemUnit->delete();
        if ($deleted) {
            return redirect()->back()->with('message', 'Item unit delete success!');
        }
    }

    public function printUnit()
    {
        $item_units = ItemUnit::all();

        return view('print_layouts.item_units', compact('item_units'));
    }
}
