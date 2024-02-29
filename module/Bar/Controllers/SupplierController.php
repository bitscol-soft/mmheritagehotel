<?php

namespace Module\Bar\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Module\Bar\Models\Supplier;

class SupplierController extends Controller
{

    public function index(Request $request)
    {
        $this->hasAccess("bar.inventories.index");

        $suppliers = Supplier::query()->suppliers();
        // $suppliers = Supplier::suppliers()
        //     ->when($request->filled('search'), function ($q) use ($request) {
        //         $q->where('name', 'LIKE', "%{$request->search}%")
        //             ->orWhere('supplier_code', 'LIKE', "%{$request->search}%")
        //             ->orWhere('phone', 'LIKE', "%{$request->search}%")
        //             ->orWhere('address', 'LIKE', "%{$request->search}%")
        //             ->orWhere('email', 'LIKE', "%{$request->search}%");
        //     })
        //     ->orderBy('name');;
        // if ($request->ajax()) {
        //     return $suppliers->where('name', 'LIKE', "%{$request->name}%")->take(15)->pluck('name', 'id');
        // }

        return view('bar.inventory.supplier.index', ['suppliers' => $suppliers->paginate(30)]);
    }

    public function create()
    {
        $this->hasAccess("bar.inventories.create");

        return view('bar.inventory.supplier.create');
    }


    public function store(Request $request)
    {
        $this->hasAccess("bar.inventories.edit");

        try {
            $data                   = $request->only('email', 'address');
            $data['code']           = Supplier::max('id') + 1;

            Supplier::updateOrCreate([
                'name'      => $request->name,
                'phone'     => $request->phone,
            ], $data);
        } catch (\Exception $ex) {
            return back()->withError($ex->getMessage());
        }

        return redirect()->route('bar.suppliers.index')->withSuccess('Supplier Created Successfully!');
    }






    public function destroy($id)
    {
        $this->hasAccess("bar.inventories.delete");

        $supplier = Supplier::find($id);
        $supplier->delete();

        return redirect()->route('bar.suppliers.index')->withSuccess('Supplier deleted successfully.');
    }
}
