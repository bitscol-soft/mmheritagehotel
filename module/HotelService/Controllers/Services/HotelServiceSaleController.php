<?php

namespace Module\HotelService\Controllers\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Module\Hotel\Models\AccountType;
use Module\Hotel\Models\HotelTransection;
use Module\HotelService\Models\Service\HotelService;
use Module\HotelService\Models\Service\HotelServiceSale;
use Module\HotelService\Services\HotelServiceSaleService;


class HotelServiceSaleController extends Controller
{
    private $dir = 'services.sales.';

    /**
     * ----------------------------------------------------------------------
     * Get All Medical Service Sale data
     * ----------------------------------------------------------------------
     */


    public function index(Request $request)
    {
        $this->hasAccess("sales.index");


        $data['services']       = HotelServiceSale::latest()->paginate(25);

        $data['account_types']  = AccountType::where('status', 1)->pluck('name', 'id');


        return view($this->dir . 'index', $data);
    }


    /**
     * ----------------------------------------------------------------------
     * Create Medical Service Sale data
     * ----------------------------------------------------------------------
     */


    public function create()
    {
        $this->hasAccess("sales.create");

        $data['invoice_id']         = HotelServiceSale::whereCompanyId(company_id())->first() == null ? 1000 : HotelServiceSale::orderByDesc('id')->first()->invoice_id + 1;

        return view($this->dir . 'create', $data);
    }

    /**
     * ----------------------------------------------------------------------
     * Store Medical Service Sale data
     * ----------------------------------------------------------------------
     */


    public function store(Request $request)
    {
        $this->hasAccess("sales.create");

        // return $request->all();

        return DB::transaction(function () use ($request) {
            $service = new HotelServiceSaleService();

            $service->store(); // store medical service sale data

            $service->storeItem(); // item store here with details

            $service->makePayment();

            // (new InvoiceNumberService)->setNextInvoiceNo(defaultAccount()->id, 'Medical Service', fdate($request->date, 'Y'));


            return redirect()->route('hotelservice.service-sales.show', $service->sale->id)->withSuccess('Hotek Service Created Successfully!');
        });
    }



    /**
     * ----------------------------------------------------------------------
     * Show Medical Service Sale
     * ----------------------------------------------------------------------
     */

    public function show($id)
    {
        $this->hasAccess("sales.view");

        $medicalServiceSale = HotelServiceSale::find($id);
        return view($this->dir . 'show', [
            'invoice' => $medicalServiceSale->load('hotel_guest', 'saleItems.service', 'user', 'company.company_details')
        ]);
    }



    public function edit(HotelServiceSale $hospitalServicesSale)
    {
    }


    public function update(Request $request, $serviceSaleId)
    {
    }


    /**
     * ----------------------------------------------------------------------
     * Delete Medical Service Sale data with Items,  Ledger, Transaction
     * ----------------------------------------------------------------------
     */
    public function destroy($id)
    {
        $this->hasAccess("sales.delete");

        try {

            DB::transaction(function () use($id) {

                $sale                = HotelServiceSale::find($id);

                $hotelTransaction = HotelTransection::where([
                    'source_id'         => $sale->id,
                    'source_type'       => 'Hotel Service Sale',
                ])->first();

                // DELETE HOTEL TRANSACTION
                if ($hotelTransaction != null) {

                    if (count($hotelTransaction->night_audits) > 0) {
                        return redirect()->back()->with('error', 'This Sale has been generated in Night Audit');
                    }

                    $hotelTransaction->transaction_ledgers()->delete();
                    $hotelTransaction->delete();
                }

                // DELETE ACC ACCOUNT TRANSACTION
                if ($sale->transactions != null) {
                    $sale->transactions()->delete();
                }


                // SALE DELETE
                $sale->delete();

            });

            return redirect()->back()->withMessage('Data deleted success');

        }
        catch (\Throwable $th) {
            return redirect()->back()->withError($th->getMessage());
        }

    }



    /**
     * ----------------------------------------------------------------------
     * Collect Due from patient
     * ----------------------------------------------------------------------
     */

    public function dueReceive(Request $request, $id)
    {
        $this->hasAccess("sales.edit");

        try {

            DB::transaction(function () use ($id) {


                $service = new HotelServiceSaleService();


                $medicalService = HotelServiceSale::find($id);


                // $service->duePayment($medicalService);

            });

            return back()->withSuccess('Payment Success !');

        } catch (\Throwable $th) {

            return redirect()->back()->withError($th->getMessage());
        }
    }





    public function getService(Request $request)
    {
        $queryParam = '%' . $request->query('name') . '%';
        $services = HotelService::query()->companies()
            ->whereRaw("(name like ?)", [$queryParam, $queryParam])->get();
        return $services;
    }
}
