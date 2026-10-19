<?php

namespace Module\Account\Controllers;

use App\Models\Company;
use App\Traits\CheckPermission;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Module\Account\Models\Voucher;
use Module\Account\Services\AccContraVoucherService;
use Module\Account\Services\DataService;
use Module\Account\Services\VoucherLedgerUpdateService;

class ContraVoucherController extends Controller
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

        $this->service      = new AccContraVoucherService();
    }










    /*
     |--------------------------------------------------------------------------
     | INDEX METHOD
     |--------------------------------------------------------------------------
    */
    public function index()
    {
        $this->hasAccess("voucher-contras.view");

        $vouchers = Voucher::contra()->with('company')->searchByField('invoice_no')->searchByField('reference')->filterDate()->paginate(30);

        return view('voucher.contras.index', compact('vouchers'));
    }






    /*
     |--------------------------------------------------------------------------
     | CREATE METHOD
     |--------------------------------------------------------------------------
    */
    public function create()
    {
        $this->hasAccess("voucher-contras.create");

        $data               = $this->dataService->getAccountData(['accounts']);
        $data['company']    = Company::userCompanies();


        return view('voucher.contras.create', $data);
    }














    /*
     |--------------------------------------------------------------------------
     | STORE/SAVE METHOD
     |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $this->hasAccess("voucher-contras.create");

        try {

            DB::transaction(function () use ($request) {


                $this->service->validateData($request);



                $this->service->storeContraVoucher($request);



                $this->service->storeContraVoucherDetails($request);


                if ($request->draft == 0) {

                    $this->service->approveVoucher();

                    $this->service->makeTransaction();
                }

                $this->service->invoiceNumberService->setNextInvoiceNo($request->company_id, 'Contra Voucher', date('Y'));
            });
        } catch (Exception $ex) {


            return redirect()->back()->withInput()->with('error', $ex->getMessage());
        }


        return redirect()->route('voucher-contras.show', $this->service->contra->id)->with('message', 'Contra Voucher Created Successfully!');
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
        $this->hasAccess("voucher-contras.edit");

        $voucher = Voucher::contra()->find($id);

        if (!$voucher) {
            return redirect()->route('voucher-contras.index')->with('error', 'Contra Voucher not found.');
        }

        if ($voucher->is_approved) {
            return redirect()->route('voucher-contras.show', $voucher->id)
                ->with('warning', 'This voucher is approved and can no longer be edited. Delete it to reverse the entries, then post a corrected one.');
        }

        $data               = $this->dataService->getAccountData(['accounts']);
        $data['company']    = Company::userCompanies();
        $data['voucher']    = $voucher->load('details');

        return view('voucher.contras.edit', $data);
    }


    /*
     |--------------------------------------------------------------------------
     | UPDATE METHOD (BUGS #49)
     |--------------------------------------------------------------------------
    */
    public function update(Request $request, $id)
    {
        $this->hasAccess("voucher-contras.edit");

        $voucher = Voucher::contra()->find($id);

        if (!$voucher) {
            return redirect()->route('voucher-contras.index')->with('error', 'Contra Voucher not found.');
        }

        if ($voucher->is_approved) {
            return redirect()->route('voucher-contras.show', $voucher->id)
                ->with('warning', 'This voucher is approved and can no longer be edited. Delete it to reverse the entries, then post a corrected one.');
        }

        try {
            DB::transaction(function () use ($request, $voucher) {
                (new VoucherLedgerUpdateService($voucher, [
                    'debit'  => 'Contra In',
                    'credit' => 'Contra Out',
                ]))->update($request);
            });
        } catch (Exception $ex) {
            return redirect()->back()->withInput()->with('error', $ex->getMessage());
        }

        return redirect()->route('voucher-contras.show', $voucher->id)
            ->with('message', 'Contra Voucher Updated Successfully!');
    }


    public function show($id)
    {
        $this->hasAccess("voucher-contras.view");

        $voucher = Voucher::with('details', 'company')->findOrFail($id);

        return view('voucher.contras.invoice', compact('voucher'));
    }










    /*
     |--------------------------------------------------------------------------
     | SHOW/DETAIL METHOD
     |--------------------------------------------------------------------------
    */
    public function approveContraVoucher(Voucher $contra)
    {



        if ($contra->is_approved == 1) {

            return redirect()->back()->withInput()->with('error', 'This Voucher Already Approved');
        }

        try {

            DB::transaction(function () use ($contra) {


                $this->service->contra = $contra;


                $this->service->approveVoucher();


                $this->service->makeTransaction();
            });
        } catch (Exception $ex) {


            return redirect()->back()->withInput()->with('error', $ex->getMessage());
        }


        return redirect()->route('voucher-contras.show', $this->service->contra->id)->with('message', 'Approved Successfully!');
    }












    /*
     |--------------------------------------------------------------------------
     | DELETE/DESTROY METHOD
     |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        $this->hasAccess("voucher-contras.delete");

        try {

            DB::transaction(function () use ($id) {


                $contra = Voucher::find($id);


                foreach ($contra->details as $key => $detail) {

                    $detail->transactions()->delete();

                    $detail->delete();
                }


                $contra->delete();
            });


            return redirect()->route('voucher-contras.index')->with('message', 'Voucher Successfully Deleted!');
        } catch (\Exception $ex) {

            return redirect()->back()->withMessage($ex->getMessage());
        }
    }
}
