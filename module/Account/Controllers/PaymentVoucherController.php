<?php

namespace Module\Account\Controllers;

use App\Models\Company;
use App\Traits\CheckPermission;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Module\Account\Models\Voucher;
use Module\Account\Models\VoucherDetail;
use Module\Account\Services\AccPaymentVoucherService;
use Module\Account\Services\DataService;

class PaymentVoucherController extends Controller
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

        $this->service      = new AccPaymentVoucherService();
    }










    /*
     |--------------------------------------------------------------------------
     | INDEX METHOD
     |--------------------------------------------------------------------------
    */
    public function index()
    {
        $this->hasAccess("voucher-payments.view");

        if(request()->type == 'description') {
        
            $voucherDetails = VoucherDetail::with('voucher:description,id')->whereHas('transaction', function($q) { 
                $q->whereNull('description');
            })->with('transaction')->get();

            foreach ($voucherDetails as $key => $detail) {
                
                $detail->transaction()->update([
                    'description' => optional($detail->voucher)->description
                ]);
            }

        }
        $vouchers = Voucher::payment()->with('company')->searchByField('invoice_no')->searchByField('reference')->filterDate()->paginate(30);

        return view('voucher.payments.index', compact('vouchers'));
    }






    /*
     |--------------------------------------------------------------------------
     | CREATE METHOD
     |--------------------------------------------------------------------------
    */
    public function create()
    {
        $this->hasAccess("voucher-payments.create");


        $data               = $this->dataService->getAccountData(['accounts']);
        $data['companies']  = Company::userCompanies();


        return view('voucher.payments.create', $data);
    }














    /*
     |--------------------------------------------------------------------------
     | STORE/SAVE METHOD
     |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {

        try {

            DB::transaction(function () use($request) {


                $this->service->validateData($request);



                $this->service->storePaymentVoucher($request);



                $this->service->storePaymentVoucherDetails($request);


                if ($request->draft == 0) {

                    $this->service->approveVoucher();

                    $this->service->makeTransaction();

                }

                $this->service->invoiceNumberService->setNextInvoiceNo($request->company_id, 'Payment Voucher', date('Y'));

            });


        } catch (Exception $ex) {


            return redirect()->back()->withInput()->with('error', $ex->getMessage());
        }


        return redirect()->route('voucher-payments.show', $this->service->payment->id)->with('message', 'Payment Voucher Created Successfully!');
    }










    /*
     |--------------------------------------------------------------------------
     | SHOW/DETAIL METHOD
     |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        $this->hasAccess("voucher-payments.view");

        $voucher = Voucher::with('details', 'company')->find($id);

        return view('voucher.payments.invoice', compact('voucher'));
    }










    /*
     |--------------------------------------------------------------------------
     | SHOW/DETAIL METHOD
     |--------------------------------------------------------------------------
    */
    public function approvePaymentVoucher(Voucher $payment)
    {


        if ($payment->is_approved == 1) {

            return redirect()->back()->withInput()->with('error', 'This Vocuher Already Approved');
        }

        try {

            DB::transaction(function () use($payment) {


                $this->service->payment = $payment;


                $this->service->approveVoucher();


                $this->service->makeTransaction();

            });


        } catch (Exception $ex) {


            return redirect()->back()->withInput()->with('error', $ex->getMessage());
        }


        return redirect()->route('voucher-payments.show', $this->service->payment->id)->with('message', 'Approved Successfully!');
    }












    /*
     |--------------------------------------------------------------------------
     | DELETE/DESTROY METHOD
     |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        $this->hasAccess("voucher-payments.delete");

        try {

            DB::transaction(function () use($id) {


                $payment = Voucher::find($id);


                foreach ($payment->details as $key => $detail) {

                    $detail->transactions()->delete();

                    $detail->delete();
                }


                $payment->delete();

            });


            return redirect()->route('voucher-payments.index')->with('message', 'Voucher Successfully Deleted!');


        } catch (\Exception $ex) {

            return redirect()->back()->withMessage($ex->getMessage());
        }
    }
}
