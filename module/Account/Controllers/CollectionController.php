<?php

namespace Module\Account\Controllers;

use App\Traits\CheckPermission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Module\Account\Models\Collection;
use Module\Account\Services\AccCollectionService;

class CollectionController extends Controller
{
    use CheckPermission;

    public $service;

    public function __construct()
    {
        $this->service = new AccCollectionService();
    }


    public function index()
    {
        $this->hasAccess("acc_collections.index");

        $collections = Collection::query()
            ->with('customer')
            ->latest('date')
            ->latest('id')
            ->get();

        return view('sale.collections.index', compact('collections'));
    }

    public function create()
    {
        $this->hasAccess("acc_collections.create");

        $customers = \Module\Account\Models\Customer::orderBy('name')->pluck('name', 'id');

        return view('sale.collections.create', compact('customers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->hasAccess("acc_collections.create");

        $request->validate([
            'customer_id'    => 'required|integer|exists:acc_customers,id',
            'date'           => 'required|date',
            'amount'         => 'required|numeric|min:0.01',
            'transaction_no' => 'nullable|string|max:100',
            'remarks'        => 'nullable|string|max:500',
        ]);

        try {
            $collection = $this->service->store($request);
            $this->service->invoiceNumberService->setNextInvoiceNo($collection->company_id, 'Collection', date('Y'));
            $this->service->makeTransaction();

            return redirect()->route('acc_collections.index')->with('message', 'Collection Create Successful');
        } catch (\Exception $ex) {

            return redirect()->back()->withInput($request->all())->with('error', $ex->getMessage());
        }
    }

    public function edit(Collection $collection)
    {
        $this->hasAccess("acc_collections.edit");

        $customers = \Module\Account\Models\Customer::orderBy('name')->pluck('name', 'id');

        return view('sale.collections.edit', compact('collection', 'customers'));
    }

    public function update(Request $request, Collection $collection): RedirectResponse
    {
        $this->hasAccess("acc_collections.edit");

        $request->validate([
            'customer_id'    => 'required|integer|exists:acc_customers,id',
            'date'           => 'required|date',
            'amount'         => 'required|numeric|min:0.01',
            'transaction_no' => 'nullable|string|max:100',
            'remarks'        => 'nullable|string|max:500',
        ]);

        try {
            $this->service->update($request, $collection);
            $this->service->makeTransaction();

            return redirect()->route('acc_collections.index')->with('message', 'Collection Update Successful');
        } catch (\Exception $ex) {

            return redirect()->back()->withInput($request->all())->with('error', $ex->getMessage());
        }
    }


    public function destroy($id)
    {
        $this->hasAccess("acc_collections.delete");

        try {
            $collection = Collection::find($id);

            if ($collection) {
                $collection->transactions()->delete();
                $collection->delete();
            }

            return redirect()->route('acc_collections.index')->with('message', 'Collection Successfully Deleted!');
        } catch (\Exception $ex) {
            return redirect()->back()->withMessage($ex->getMessage());
        }
    }
}
