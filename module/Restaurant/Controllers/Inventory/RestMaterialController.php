<?php

namespace Module\Restaurant\Controllers\Inventory;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Traits\CheckPermission;
use App\Http\Controllers\Controller;
use Module\Restaurant\Models\RestMaterial;
use App\Traits\AutoCreatedUpdatedWithCompany;
use Module\Restaurant\Models\RestMaterialUnit;

class RestMaterialController extends Controller
{
    use CheckPermission;
     /**
     * --------------------------------------------
     * INDEX METHOD
     * --------------------------------------------
     */
    public function index(Request $request)
    {
        $this->hasAccess("items.view");   // check permission
        $data['companies'] = Company::userCompanies();
        $data['item_ids']  = RestMaterial::whereIn('company_id', $data['companies']->keys())->pluck('name', 'id');
        $data['items']     = RestMaterial::with('company', 'item_unit', 'created_user', 'updated_user')
                            // ->withCount(['purchase_detail', 'goods_requisition'])
                            ->orderByDesc('id');


        if ($request->filled('company_id')) {
            $data['items']->where('company_id', $request->company_id);
        }

        if ($request->filled('item_id')) {
            $data['items']->where('id', $request->item_id);
        }

        if ($request->filled('from_date')) {
            $data['items']->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $data['items']->whereDate('created_at', '<=', $request->to_date);
        }

        $data['items'] = $data['items']->paginate(30);
        return view('inventory.production.items.index', $data);
    }

    /**
     * --------------------------------------------
     * SHOW METHOD
     * --------------------------------------------
     */
    public function show(RestMaterial $restMaterial)
    {
        $this->hasAccess("items.view");   // check permission
        $data['companies'] = Company::userCompanies();
        $data['item_ids']  = RestMaterial::whereIn('company_id', $data['companies']->keys())->pluck('name', 'id');
        $data['items']     = RestMaterial::where('id', $restMaterial->id)->with('company', 'item_unit', 'created_user', 'updated_user')
            // ->withCount(['purchase_detail', 'goods_requisition'])
            ->orderByDesc('id');

        $data['items'] = $data['items']->paginate(30);
        return view('inventory.production.items.index', $data);
    }


    /**
     * --------------------------------------------
     * CREATE METHOD
     * --------------------------------------------
     */
    public function create()
    {
        $this->hasAccess("items.create");   // check permission
        $data['companies']  = Company::userCompanies();
        $data['item_units'] = RestMaterialUnit::pluck('name', 'id');
        return view('inventory.production.items.create', $data);
    }




    /**
     * --------------------------------------------
     * STORE METHOD
     * --------------------------------------------
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'company_id'   => 'required|not_in:0',
            'item_unit_id' => 'required',
            'name'         => 'unique_with:rest_materials, name, company_id',
        ]);

        //  store data
        $created = RestMaterial::create([
            'company_id'         => $request->company_id,
            'units_id'           => $request->item_unit_id,
            'name'               => $request->name,
            'opening_balance'    => (int)$request->opening_balance,
            'rate'               => $request->rate,
            'remaining_quantity' => (int)$request->opening_balance,
            'current_stock'      => (int)$request->opening_balance,
            'average_rate'       => $request->rate
        ]);

        if ($created) {
            return redirect()->route('rst.material.index')->with('message', 'Rest Material create successfully!');
        }
    }


    /**
     * --------------------------------------------
     * EDIt METHOD
     * --------------------------------------------
     */
    public function edit($id)
    {
        $this->hasAccess("items.edit");   // check permission
        $data['item']       = RestMaterial::where('id', $id)
                            // ->withCount(['purchase_detail', 'goods_requisition'])
                            ->first();
        $data['companies']  = Company::userCompanies();
        $data['item_units'] = RestMaterialUnit::orderBy('name')->pluck('name', 'id');
        return view('inventory.production.items.edit', $data);
    }


    /**
     * --------------------------------------------
     * UPDATE METHOD
     * --------------------------------------------
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'item_unit_id' => 'required',
            'name'         => 'required',
        ]);
        $item = RestMaterial::find($id);

        $item->update([
            'company_id'         => $request->company_id,
            'units_id'           => $request->item_unit_id,
            'name'               => $request->name,
            'opening_balance'    => (int)$request->opening_balance,
            'current_stock'      => $item->opening_balance != (int)$request->opening_balance ? (int)$request->opening_balance : $item->current_stock,
            'remaining_quantity' => $item->opening_balance != (int)$request->opening_balance ? (int)$request->opening_balance : $item->remaining_quantity,
            'rate'               => $request->rate,
            'average_rate'       => $request->rate
        ]);

        return redirect()->route('rst.material.index')->with('message', 'Material Item update successfully!');
    }


    /**
     * --------------------------------------------
     * DELETE ITEM FORM THE ITEM TABLE
     * --------------------------------------------
     */

    public function destroy($id)
    {
        $this->hasAccess("items.delete");   // check permission
        $restmaterial = RestMaterial::find($id);
        $deleted = $restmaterial->delete();
        if ($deleted) {
            return redirect()->back()->with('message', 'Material Item delete success!');
        }
    }


}
