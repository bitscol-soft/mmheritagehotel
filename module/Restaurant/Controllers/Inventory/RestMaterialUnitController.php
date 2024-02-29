<?php

namespace Module\Restaurant\Controllers\Inventory;

use Exception;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Traits\CheckPermission;
use App\Http\Controllers\Controller;
use Module\Restaurant\Models\RestMaterialUnit;


class RestMaterialUnitController extends Controller
{
    use CheckPermission;


    public function index()
    {
        $this->hasAccess("items.view");   // check permission


        $item_units = RestMaterialUnit::orderByDesc('id')->paginate(30);

        return view('inventory.production.item-units.index', compact('item_units'));
    }


    public function create()
    {
        $this->hasAccess("items.create");   // check permission
        $companies = Company::userCompanies();

        return view('inventory.production.item-units.create', compact('companies'));
    }


    public function store(Request $request)
    {
        $item_units = $this->validate($request, [
            'name'       => 'required|unique:item_units,name',
            'conversion' => 'required',
            'status'     => 'required'
        ]);
        // $item_units = $this->validate($request, [
        //     'name'       => 'required',
        //     'conversion' => 'required',
        //     'group_id'   => 'nullable',
        //     'created_by' => 'nullable',
        //     'updated_by' => 'nullable',
        //     'status'     => 'required'
        // ]);

        try {
            $created = RestMaterialUnit::create(array_merge($item_units, ['group_id' => 1]));

            // $created = RestMaterialUnit::create([
            //     'name'       => $request->name,
            //     'conversion' => $request->conversion,
            //     'group_id'   => $request->group_id,
            //     'created_by' => auth()->user()->id,
            //     'updated_by' => auth()->user()->id,
            //     'status'     => $request->status
            // ]);
            return redirect()->route('rst.material-unit.index')->with('message', 'Rest Material Unit create success!');
        } catch (Exception $ex) {
            // return $ex;
            return redirect()->back()->withInput()->with('error', $ex->getMessage());
        }
    }




    public function edit($id)
    {
        $this->hasAccess("items.edit");   // check permission
        $itemUnit = RestMaterialUnit::find($id);

        return view('inventory.production.item-units.edit', compact('itemUnit'));
    }


    public function update(Request $request)
    {
        // return ;
        $item_units = $this->validate($request, [
            'name'       => 'required|required:item_units,name',
            'conversion' => 'required',
            'status'     => 'required'
        ]);

        $restMaterialUnit = RestMaterialUnit::find($request->id);
        $updated = $restMaterialUnit->update($item_units);

        if ($updated) {
            return redirect()->route('rst.material-unit.index')->with('message', 'Item unit update success!');
        }
    }


    public function destroy($id)
    {
        $this->hasAccess("items.delete");   // check permission
        $itemUnit = RestMaterialUnit::find($id);
        $deleted = $itemUnit->delete();
        if ($deleted) {
            return redirect()->back()->with('message', 'Item unit delete success!');
        }
    }

    // public function printUnit()
    // {
    //     $item_units = RestMaterialUnit::all();

    //     return view('print_layouts.item_units', compact('item_units'));
    // }
}
