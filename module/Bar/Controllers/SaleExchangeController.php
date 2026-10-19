<?php

namespace Module\Bar\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Module\Bar\Models\SaleExchange;
use Module\Bar\Services\SaleExchangeService;

/**
 * Sale Exchange (Bar) - BUGS #48.
 *
 * Returns goods from an invoice and hands out replacements in one document,
 * settling only the difference. Previously every action redirected to the sales
 * grid with "feature is not installed": the views and the route registration
 * shipped, but the controller, the models and the two tables did not.
 *
 * An exchange is a financial document, so it is created and voided - never
 * edited in place. `edit`/`update` say so instead of rendering a form whose
 * numbers could no longer match the stock and ledger movement already posted.
 */
class SaleExchangeController extends Controller
{
    /*
     |--------------------------------------------------------------------------
     | INDEX
     |--------------------------------------------------------------------------
    */
    public function index()
    {
        $this->hasAccess('bar.sales.index');

        $query = SaleExchange::with(['guest', 'user'])
            ->where('is_bar', 1)
            ->when(request('invoice_no'), function ($q, $invoice) {
                return $q->where('invoice_no', 'like', '%' . $invoice . '%');
            });

        return view('bar.sales.exchange.index', [
            'sales' => $query->latest('date')->latest('id')->paginate(25)->appends(request()->query()),
        ]);
    }

    /*
     |--------------------------------------------------------------------------
     | CREATE
     |--------------------------------------------------------------------------
    */
    public function create()
    {
        $this->hasAccess('bar.sales.create');

        return view('bar.sales.exchange.create', [
            'invoice_id' => SaleExchange::where('is_bar', 1)->latest('id')->value('invoice_no') ?: 1000,
        ]);
    }

    /*
     |--------------------------------------------------------------------------
     | STORE
     |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $this->hasAccess('bar.sales.create');

        $request->validate([
            'date'           => 'required',
            'product_ids'    => 'required|array|min:1',
            'return_quantity' => 'required|array|min:1',
        ]);

        try {
            return DB::transaction(function () use ($request) {
                $service = new SaleExchangeService();
                $exchange = $service->store();

                $service->storeItem();
                $service->settle($request->account_type_id);

                return redirect()
                    ->route('bar.sale-exchanges.show', $exchange->id)
                    ->with('message', 'Sale Exchange created successfully.');
            });
        } catch (\Throwable $th) {
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }

    /*
     |--------------------------------------------------------------------------
     | SHOW
     |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        $this->hasAccess('bar.sales.view');

        $exchange = SaleExchange::with(['items.product', 'guest', 'user'])->findOrFail($id);

        return view('bar.sales.exchange.show', [
            'sale' => $exchange,
        ]);
    }

    /*
     |--------------------------------------------------------------------------
     | EDIT / UPDATE - not allowed on a posted exchange
     |--------------------------------------------------------------------------
    */
    public function edit($id)
    {
        $this->hasAccess('bar.sales.view');

        return redirect()
            ->route('bar.sale-exchanges.show', $id)
            ->with('warning', 'An exchange cannot be edited: its stock and settlement are already posted. Void it and create a new one.');
    }

    public function update(Request $request, $id)
    {
        return $this->edit($id);
    }

    /*
     |--------------------------------------------------------------------------
     | DESTROY - void
     |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        $this->hasAccess('bar.sales.delete');

        $exchange = SaleExchange::findOrFail($id);

        try {
            DB::transaction(function () use ($exchange) {
                // Items first (the model's deleting hook would do it too, but the
                // explicit call keeps the table clean even if the hook is skipped).
                $exchange->items()->delete();
                $exchange->transactions()->delete();
                $exchange->delete();
            });

            return redirect()
                ->route('bar.sale-exchanges.index')
                ->with('message', 'Sale Exchange voided.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
