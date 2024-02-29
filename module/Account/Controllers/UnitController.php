<?php

namespace Module\Account\Controllers;

use App\Traits\CheckPermission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Module\Account\Models\Unit;

class UnitController extends Controller
{
    use CheckPermission;


    public function index()
    {
        $this->hasAccess("account-units.index");
        $units = Unit::paginate(30);

        return view('product.units.index', compact('units'));
    }

    public function create()
    {
        $this->hasAccess("account-units.create");

        return view('product.units.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->hasAccess("account-units.create");

        $request->validate([ 'name' => 'required']);

        Unit::create($request->all());

        return redirect()->route('units.index')->with('message', 'Unit Create Successful');
    }

    public function edit(Unit $unit)
    {
        $this->hasAccess("account-units.edit");


        return view('product.units.edit', compact('unit'));
    }

    public function update(Request $request, Unit $unit): RedirectResponse
    {
        $this->hasAccess("account-units.edit");

        $request->validate([ 'name' => 'required']);

        $unit->update(['name' => $request->name]);

        return redirect()->route('units.index')->with('message', 'Unit Update Successful');
    }


    public function destroy($id)
    {
        $this->hasAccess("account-units.delete");

        try {
            Unit::destroy($id);

            return redirect()->route('units.index')->with('message', 'Unit Successfully Deleted!');
        } catch (\Exception $ex) {
            return redirect()->back()->withMessage($ex->getMessage());
        }
    }
}
