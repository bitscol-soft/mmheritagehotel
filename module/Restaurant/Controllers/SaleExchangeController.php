<?php

namespace Module\Restaurant\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Sale Exchange (Restaurant module).
 *
 * The module shipped views (sales/exchange/*) and a route registration
 * ("sale-exchanges" => SaleExchangeController::class) but the controller and
 * its table (rst_sale_exchanges) were never committed to this repository.
 * Until the feature is restored from the vendor build, every entry point
 * degrades to a flash redirect on the Restaurant sales grid instead of the
 * previous "Target class ... does not exist" HTTP 500.
 */
class SaleExchangeController extends Controller
{
    private function notInstalled()
    {
        return redirect()->route('rst.sales.index')
            ->with('warning', 'The Sale Exchange feature is not installed in this build (module files/table missing).');
    }

    public function index()
    {
        return $this->notInstalled();
    }

    public function create()
    {
        return $this->notInstalled();
    }

    public function store(Request $request)
    {
        return $this->notInstalled();
    }

    public function show($id)
    {
        return $this->notInstalled();
    }

    public function edit($id)
    {
        return $this->notInstalled();
    }

    public function update(Request $request, $id)
    {
        return $this->notInstalled();
    }

    public function destroy($id)
    {
        return $this->notInstalled();
    }
}
