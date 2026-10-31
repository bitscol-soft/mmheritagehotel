<?php

namespace Module\Account\Controllers;

use App\Models\Company;
use App\Traits\CheckPermission;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Module\Account\Models\Voucher;
use Module\Account\Services\AccReceiveVoucherService;
use Module\Account\Services\DataService;
use Module\Account\Services\VoucherLedgerUpdateService;

class ReceiveVoucherController extends Controller
{

    use CheckPermission;


    private $dataService;
    private $service;










    /*
     |--------------------------------------------------------------------------
     | CONSTRUCTOR
     |--------------------------------------------------------------------------
    */
    public function __construct()
    {
        $this->dataService  = new DataService();

        $this->service      = new AccReceiveVoucherService();
    }










    /*
     |--------------------------------------------------------------------------
     | INDEX METHOD
     |--------------------------------------------------------------------------
    */
    public function index()
    {
        $this->hasAccess("voucher-receives.view");

        $vouchers = Voucher::receive()->with('company')->searchByField('invoice_no')->searchByField('reference')->filterDate()->paginate(30);

        return view('voucher.receives.index', compact('vouchers'));
    }






    /*
     |--------------------------------------------------------------------------
     | CREATE METHOD
     |--------------------------------------------------------------------------
    */
    public function create()
    {
        $this->hasAccess("voucher-receives.create");


        $data               = $this->dataService->getAccountData(['accounts']);
        $data['companies']  = Company::userCompanies();


        return view('voucher.receives.create', $data);
    }














    /*
     |--------------------------------------------------------------------------
     | STORE/SAVE METHOD
     |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {


        $this->hasAccess("voucher-receives.create");



        try {

            DB::transaction(function () use($request) {


                $this->service->validateData($request);



                $this->service->storeReceiveVoucher($request);



                $this->service->storeReceiveVoucherDetails($request);


                if ($request->draft == 0) {

                    $this->service->approveVoucher();

                    $this->service->makeTransaction();

                }

                $this->service->invoiceNumberService->setNextInvoiceNo($request->company_id, 'Receive Voucher', date('Y'));

            });


        } catch (Exception $ex) {


            return redirect()->back()->withInput()->with('error', $ex->getMessage());
        }


        return redirect()->route('voucher-receives.show', $this->service->receive->id)->with('message', 'Receive Voucher Created Successfully!');
    }










    /*
     |--------------------------------------------------------------------------
     | SHOW/DETAIL METHOD
     |--------------------------------------------------------------------------
    */
    /*
     |--------------------------------------------------------------------------
     | EDIT METHOD (BUGS #49)
     |--------------------------------------------------------------------------
     | Only an unapproved voucher is editable: once approved the entries are a
     | posting. The services are constructed with no name filter here, so the
     | controller passes the account list the same way create() does.
    */
    public function edit($id)
    {
        $this->hasAccess("voucher-receives.edit");

        $voucher = Voucher::receive()->find($id);

        if (!$voucher) {
            return redirect()->route('voucher-receives.index')->with('error', 'Receive Voucher not found.');
        }

        if ($voucher->is_approved) {
            return redirect()->route('voucher-receives.show', $voucher->id)
                ->with('warning', 'This voucher is approved and can no longer be edited. Delete it to reverse the entries, then post a corrected one.');
        }

        $data               = $this->dataService->getAccountData(['accounts']);
        $data['companies']  = Company::userCompanies();
        $data['voucher']    = $voucher->load('details');

        return view('voucher.receives.edit', $data);
    }


    /*
     |--------------------------------------------------------------------------
     | UPDATE METHOD (BUGS #49)
     |--------------------------------------------------------------------------
    */
    public function update(Request $request, $id)
    {
        $this->hasAccess("voucher-receives.edit");

        $voucher = Voucher::receive()->find($id);

        if (!$voucher) {
            return redirect()->route('voucher-receives.index')->with('error', 'Receive Voucher not found.');
        }

        if ($voucher->is_approved) {
            return redirect()->route('voucher-receives.show', $voucher->id)
                ->with('warning', 'This voucher is approved and can no longer be edited. Delete it to reverse the entries, then post a corrected one.');
        }

        try {
            DB::transaction(function () use ($request, $voucher) {
                (new VoucherLedgerUpdateService($voucher, [
                    'debit'  => 'Cash',
                    'credit' => 'Receive',
                ]))->update($request);
            });
        } catch (Exception $ex) {
            return redirect()->back()->withInput()->with('error', $ex->getMessage());
        }

        return redirect()->route('voucher-receives.show', $voucher->id)
            ->with('message', 'Receive Voucher Updated Successfully!');
    }


    public function show($id)
    {
        $this->hasAccess("voucher-receives.view");

        $voucher = Voucher::with('details', 'company')->findOrFail($id);

        return view('voucher.receives.invoice', compact('voucher'));
    }










    /*
     |--------------------------------------------------------------------------
     | SHOW/DETAIL METHOD
     |--------------------------------------------------------------------------
    */
    public function approveReceiveVoucher(Voucher $receive)
    {



        if ($receive->is_approved == 1) {

            return redirect()->back()->withInput()->with('error', 'This Voucher Already Approved');
        }

        try {

            DB::transaction(function () use($receive) {


                $this->service->receive = $receive;


                $this->service->approveVoucher();


                $this->service->makeTransaction();

            });


        } catch (Exception $ex) {


            return redirect()->back()->withInput()->with('error', $ex->getMessage());
        }


        return redirect()->route('voucher-receives.show', $this->service->receive->id)->with('message', 'Approved Successfully!');
    }












    /*
     |--------------------------------------------------------------------------
     | DELETE/DESTROY METHOD
     |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        $this->hasAccess("voucher-receives.delete");

        try {

            DB::transaction(function () use($id) {


                $receive = Voucher::find($id);


                foreach ($receive->details as $key => $detail) {

                    $detail->transactions()->delete();

                    $detail->delete();
                }


                $receive->delete();

            });


            return redirect()->route('voucher-receives.index')->with('message', 'Voucher Successfully Deleted!');


        } catch (\Exception $ex) {

            return redirect()->back()->withMessage($ex->getMessage());
        }
    }
}
