<?php

namespace Module\Account\Controllers;

use App\Models\Company;
use App\Traits\FileSaver;
use App\Traits\CheckPermission;
use App\Traits\Helper;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Module\Account\Models\Customer;
use Module\Account\Models\Supplier;
use Module\Account\Models\Transaction;
use Module\Account\Models\Voucher;
use Module\Account\Services\AccountTransactionService;
use Module\Account\Services\DataService;
use Module\Account\Services\IndexDataService;
use Module\Account\Services\InvoiceNumberService;

class VoucherController extends Controller
{
    use CheckPermission;
    use FileSaver;
    use Helper;

    private $dataService;
    private $indexService;
    private $transactionService;
    private $invoiceNumberService;

    public function __construct()
    {
        $this->dataService = new DataService();
        $this->indexService = new IndexDataService();
        $this->invoiceNumberService = new InvoiceNumberService();
        $this->transactionService = new AccountTransactionService();
    }




    // Journal Vouchers
    public function journalIndex(Request $request)
    {
        $this->hasAccess("vouchers.journal.index");

        if ($request->invoice_no != null) {
            $data['vouchers'] = Voucher::where('invoice_no', $request->invoice_no)->get();
        } elseif ($request->reference != null) {
            $data['vouchers'] = Voucher::where('reference', $request->reference)->get();
        } else {
            $data['vouchers'] = $this->indexService->getVoucherData('Journal');
        }
        $data['invo'] = Voucher::where('voucher_type', 'Journal')->get();
        session()->flashInput($request->input());


        return view('vouchers.journal.index', $data);
    }



    public function journalCreate()
    {
        $this->hasAccess("vouchers.journal.create");

        $data = $this->dataService->getAccountData(['accounts']);
        $company = Company::pluck('name', 'id');

        return view('voucher.journal.create', compact('company'), $data);
    }



    public function journalStore(Request $request): RedirectResponse
    {
        $this->hasAccess("vouchers.journal.create");

        $this->validateData($request);

        try {
            if (array_sum($request->debit) === array_sum($request->credit)) {

                DB::beginTransaction();

                if ($request->draft == 0) {
                    $voucher = Voucher::query()->create([
                        'date' => $request->date,
                        'description' => $request->description,
                        'reference' => $request->reference,
                        'amount' => array_sum($request->debit),
                        'voucher_type' => $request->voucher_type,
                        'is_approved' => 1
                    ]);
                } else {
                    $voucher = Voucher::query()->create([
                        'date' => $request->date,
                        'description' => $request->description,
                        'reference' => $request->reference,
                        'amount' => array_sum($request->debit),
                        'voucher_type' => $request->voucher_type,
                        'is_approved' => 0
                    ]);
                }
                $file = $request->attachment;
                $this->upload_file($file, $voucher, 'attachment', 'payment_vouchers');


                $voucher->update([
                    'invoice_no' => $this->invoiceNumberService->getVoucherInvoiceNo($voucher->company_id, $voucher->voucher_type),
                    'company_id' => $request->company_id,
                ]);

                foreach ($request->account_ids as $key => $account_id) {
                    // return $account_id;
                    $debit = $request->debit[$key];
                    $credit = $request->credit[$key];
                    $type = $debit > $credit ? 'Debit' : 'Credit';

                    $this->storeVoucherDetails($voucher, $account_id, $type == 'Debit' ? $debit : $credit, $type);
                }

                $this->invoiceNumberService->setNextInvoiceNo($voucher->company_id, 'Voucher', date('Y'));

                $this->approveVoucher($voucher);
                DB::commit();
                return redirect()->route('journal.index')->with('message', 'Journal Voucher Create Successful');
            } else {
                return redirect()->back()->with('error', "Debit & Credit Doesn't Match");
            }
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', $e->getMessage());
        }
    }




    public function journalEdit($id)
    {
        $voucher = Voucher::find($id);

        $this->hasAccess("vouchers.journal.edit");
        $data = $this->dataService->getAccountData(['accounts']);

        return view('vouchers.journal.edit', compact('voucher'), $data);
    }




    public function journalShow($id)
    {
        $this->hasAccess("vouchers.journal.show");
        $voucher = Voucher::find($id);

        return view('vouchers.journal.show', compact('voucher'));
    }




    public function showjournalApprove(Voucher $voucher)
    {
        $this->hasAccess("vouchers.journal.approve");

        return view('vouchers.journal.show', compact('voucher'));
    }



    
    public function approvejournalVoucher(Voucher $voucher)
    {
        $this->hasAccess("vouchers.journal.approve");

        $voucher->update([
            'is_approved' => 1
        ]);

        foreach ($voucher->details as $key => $detail) {
            $detail->update([
                'transaction_no' => $this->invoiceNumberService->getVoucherDetailTransactionNo($key, $voucher->invoice_no)
            ]);

            if ($detail->balance_type == 'Debit') {
                $this->transactionService->storeDebitVoucher($detail, $detail->amount, $detail->account_id, $detail->transaction_no, $voucher->date);
            } else {
                $this->transactionService->storeCreditVoucher($detail, $detail->amount, $detail->account_id, $detail->transaction_no, $voucher->date);
            }
        }

        return redirect()->route('journal.index')->with('message', 'Voucher Approved Successfully!');
    }





    private function storeVoucherDetails($voucher, $account_id, $amount, $type)
    {
        $voucher->details()->create([
            'account_id' => $account_id,
            'amount' => $amount,
            'balance_type' => $type,
        ]);
    }
}
