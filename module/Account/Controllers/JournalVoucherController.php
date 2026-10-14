<?php

namespace Module\Account\Controllers;

use App\Models\Company;
use App\Traits\CheckPermission;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Module\Account\Models\Voucher;
use Module\Account\Services\AccJournalVoucherService;
use Module\Account\Services\DataService;

class JournalVoucherController extends Controller
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

        $this->service      = new AccJournalVoucherService();
    }










    /*
     |--------------------------------------------------------------------------
     | INDEX METHOD
     |--------------------------------------------------------------------------
    */
    public function index()
    {
        $this->hasAccess("voucher-journals.view");

        $vouchers = Voucher::journal()->with('company')->searchByField('invoice_no')->searchByField('reference')->filterDate()->paginate(30);

        return view('voucher.journals.index', compact('vouchers'));
    }






    /*
     |--------------------------------------------------------------------------
     | CREATE METHOD
     |--------------------------------------------------------------------------
    */
    public function create()
    {
        $this->hasAccess("voucher-journals.create");


        $data               = $this->dataService->getAccountData(['accounts']);
        $data['companies']  = Company::userCompanies();


        return view('voucher.journals.create', $data);
    }














    /*
     |--------------------------------------------------------------------------
     | STORE/SAVE METHOD
     |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {


        try {

            DB::transaction(function () use ($request) {


                $this->service->validateData($request);



                $this->service->storeJournalVoucher($request);



                $this->service->storeJournalVoucherDetails($request);


                if ($request->draft == 0) {

                    $this->service->approveVoucher();

                    $this->service->makeTransaction();
                }

                $this->service->invoiceNumberService->setNextInvoiceNo($request->company_id, 'Journal Voucher', date('Y'));
            });

        } catch (Exception $ex) {


            return redirect()->back()->withInput()->with('error', $ex->getMessage());
        }


        return redirect()->route('voucher-journals.show', $this->service->journal->id)->with('message', 'Journal Voucher Created Successfully!');
    }










    /*
     |--------------------------------------------------------------------------
     | SHOW/DETAIL METHOD
     |--------------------------------------------------------------------------
    */
    /*
     |--------------------------------------------------------------------------
     | EDIT (unsupported flow)
     |--------------------------------------------------------------------------
     | The shipped edit view posts to a `vouchers.update` route that has never
     | existed in this codebase (and no update service for journals exists
     | either), so it could not complete a save even when the files were
     | present. Render the approved voucher instead of the previous 500.
    */
    public function edit($id)
    {
        $this->hasAccess("voucher-journals.edit");

        $voucher = Voucher::find($id);
        if (!$voucher) {
            return redirect()->route('voucher-journals.index')->with('error', 'Journal voucher not found.');
        }

        return redirect()->route('voucher-journals.show', $voucher->id)
            ->with('warning', 'Direct editing of journal vouchers is not supported in this build. Review the voucher and create a new one to make corrections.');
    }


    public function show($id)
    {
        $this->hasAccess("voucher-journals.view");

        $voucher = Voucher::with('details', 'company')->findOrFail($id);

        return view('voucher.journals.invoice', compact('voucher'));
    }










    /*
     |--------------------------------------------------------------------------
     | SHOW/DETAIL METHOD
     |--------------------------------------------------------------------------
    */
    public function approveJournalVoucher(Voucher $journal)
    {



        if ($journal->is_approved == 1) {

            return redirect()->back()->withInput()->with('error', 'This Voucher Already Approved');
        }

        try {

            DB::transaction(function () use ($journal) {


                $this->service->journal = $journal;


                $this->service->approveVoucher();


                $this->service->makeTransaction();
            });

        } catch (Exception $ex) {


            return redirect()->back()->withInput()->with('error', $ex->getMessage());
        }


        return redirect()->route('voucher-journals.show', $this->service->journal->id)->with('message', 'Approved Successfully!');
    }












    /*
     |--------------------------------------------------------------------------
     | DELETE/DESTROY METHOD
     |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        $this->hasAccess("voucher-journals.delete");

        try {

            DB::transaction(function () use ($id) {


                $journal = Voucher::find($id);


                foreach ($journal->details as $key => $detail) {

                    $detail->transactions()->delete();

                    $detail->delete();
                }


                $journal->delete();
            });


            return redirect()->route('voucher-journals.index')->with('message', 'Voucher Successfully Deleted!');
            
        } catch (\Exception $ex) {

            return redirect()->back()->withMessage($ex->getMessage());
        }
    }
}
