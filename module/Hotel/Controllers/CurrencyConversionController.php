<?php

namespace Module\Hotel\Controllers;

use Exception;
use Illuminate\Http\Request;
use Module\Hotel\Models\Rooms;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\CurrencyConversion;
use Module\Hotel\Models\RoomCategory;
use Module\Hotel\Services\BookingService;
use Illuminate\Validation\Rule;

class CurrencyConversionController extends Controller
{






    /**
     * ------------------------------------------------------------------
     * INDEX METHOD
     * ------------------------------------------------------------------
     */
    public function index()
    {
        $this->hasAccess('currency-conversions.index');

        $currencies             = Currency::roomName();
        $currencyConversions    = CurrencyConversion::searchByField('rate')
                                                    ->searchByField('effected_date')
                                                    ->searchFromRelation('currency', 'currency_id')
                                                    ->get();

        return view('currency-conversions.index', compact('currencies', 'currencyConversions'));
    }








    /**
     * ------------------------------------------------------------------
     * CREATE METHOD
     * ------------------------------------------------------------------
     */
    public function create()
    {
        $this->hasAccess('currency-conversions.create');

        $currencies    = Currency::roomName();

        return view('currency-conversions.create', compact('currencies'))->render();
    }







    /**
     * ------------------------------------------------------------------
     * EDIT METHOD
     * ------------------------------------------------------------------
     */
    public function edit($id)
    {
        $this->hasAccess('currency-conversions.create');

        $currencies             = Currency::roomName();
        $currencyConversions    = CurrencyConversion::get();
        $currencyConversion     = CurrencyConversion::find($id);

        return view('currency-conversions.edit', compact('currencies', 'currencyConversions', 'currencyConversion'))->render();
    }










    /**
     * ------------------------------------------------------------------
     * STORE METHOD
     * ------------------------------------------------------------------
     */
    public function store(Request $request)
    {
        // return $request->all();
        $this->hasAccess('currency-conversions.create');

        $request->validate([
            'currency_id'       => 'required',
            'rate'              => 'required',
            'effected_date'     => 'required',
        ]);

        try {
            $room = CurrencyConversion::create([
                'currency_id'   => $request->currency_id,
                'rate'          => $request->rate,
                'effected_date' => $request->effected_date,
            ]);
        } catch (\Throwable $e) {

            return redirect()->back()->with('error', $e->getMessage());
        }
        return redirect()->route('currency-conversions.index')->with('message', 'Currency Conversion Created.');
    }












    /**
     * ------------------------------------------------------------------
     * UPDATE METHOD
     * ------------------------------------------------------------------
     */
    public function update(Request $request, $id)
    {
        // return $request->all();
        $this->hasAccess('currency-conversions.create');

        $request->validate([
            'currency_id'       => 'required',
            'rate'              => 'required',
            'effected_date'     => 'required',
        ]);

        try {
            DB::transaction(function() use ($request, $id){

                $data = CurrencyConversion::find($id);
                $data->update([
                    'currency_id'   => $request->currency_id,
                    'rate'          => $request->rate,
                    'effected_date' => $request->effected_date,
                ]);

            });
        } catch (\Throwable $e) {

            return redirect()->back()->with('error', $e->getMessage());
        }
        return redirect()->route('currency-conversions.index')->with('message', 'Currency Conversion updated.');
    }











    /**
     * ------------------------------------------------------------------
     * DESTROY METHOD
     * ------------------------------------------------------------------
     */
    public function destroy($id)
    {


        $this->hasAccess('currency-conversions.delete');

        try {
            $currencyConversion = CurrencyConversion::with('hotelTransaction')->find($id);

            if ($currencyConversion->hotel_transaction == null) {
                $currencyConversion->delete();

                return redirect()->back()->with('message', 'Currency Conversion Deleted.');
            }
            else {
                return redirect()->back()->with('error', "You can't delete this item, Have one or more Booking Transaction under this conversion");
            }

        } catch (Exception $ex) {

            return redirect()->back()->with('error', 'Some error, please check');
        }
    }






}
