<?php

namespace Module\Account\Controllers;

use App\Traits\CheckPermission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Module\Account\Models\Collection ;

class CollectionController extends Controller
{
    use CheckPermission;


    public function index()
    {
        $this->hasAccess("acc_collections.index");

        return view('sale.collections.index');
    }

    public function create()
    {
        $this->hasAccess("acc_collections.create");

        return view('purchase.collections.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->hasAccess("acc_collections.create");

        return redirect()->route('acc_collections.index')->with('message', 'Collection Create Successful');
    }

    public function edit(Collection $collection)
    {
        $this->hasAccess("acc_collections.edit");


        return view('purchase.collections.edit');
    }

    public function update(Request $request, Collection $collection): RedirectResponse
    {
        $this->hasAccess("acc_collections.edit");


        return redirect()->route('acc_collections.index')->with('message', 'Collection Update Successful');
    }


    public function destroy($id)
    {
        $this->hasAccess("acc_collections.delete");

        try {
            Collection ::destroy($id);

            return redirect()->route('acc_collections.index')->with('message', 'Collection Successfully Deleted!');
        } catch (\Exception $ex) {
            return redirect()->back()->withMessage($ex->getMessage());
        }
    }
}
