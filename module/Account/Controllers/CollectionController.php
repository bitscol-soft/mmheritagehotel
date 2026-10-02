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

        // TODO: dedicated collection form (module is a stub in this repo)
        return redirect()->route('acc_collections.index')
            ->with('info', 'Collection entry form is not available yet; collections are recorded through the voucher module.');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->hasAccess("acc_collections.create");

        return redirect()->route('acc_collections.index')->with('message', 'Collection Create Successful');
    }

    public function edit(Collection $collection)
    {
        $this->hasAccess("acc_collections.edit");

        // TODO: dedicated collection form (module is a stub in this repo)
        return redirect()->route('acc_collections.index')
            ->with('info', 'Collection edit form is not available yet.');
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
