<?php

namespace Module\Account\Controllers;

use App\Traits\CheckPermission;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Module\Account\Models\FundTransfer;
use Module\Account\Models\Transaction;
use Module\Account\Services\AccountTransactionService;
use Module\Account\Services\DataService;
use Module\Account\Services\IndexDataService;
use Module\Account\Services\InvoiceNumberService;

class FundTransferController extends Controller
{
    use CheckPermission;

    private $dataService;
    private $indexService;
    private $invoiceNumberService;
    private $transactionService;

    public function __construct()
    {
        $this->dataService = new DataService();
        $this->indexService = new IndexDataService();
        $this->invoiceNumberService = new InvoiceNumberService();
        $this->transactionService = new AccountTransactionService();
    }

    public function index()
    {
        $this->hasAccess("fund.transfers.index");

        $data['transfers'] = $this->indexService->getFundTransferData();

        return view('fund-transfers.index', $data);
    }

    public function create()
    {
        $this->hasAccess("fund.transfers.create");

        $data = $this->dataService->getAccountData(['accounts']);

        return view('fund-transfers.create', $data);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->hasAccess("fund.transfers.create");

        $this->validateData($request);

        try {
            DB::beginTransaction();

            $transfer = FundTransfer::query()->create($request->all());

            $transfer->update([
                'invoice_no' => $this->invoiceNumberService->getFundTransferInvoiceNo($transfer->company_id),
            ]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('fund-transfers.index')->with('message', 'Fund Transferred Successfully');
    }

    public function approveFundTransfer(FundTransfer $fundTransfer)
    {
        $this->hasAccess("fund.transfers.approve");

        $fundTransfer->update([
            'is_approved' => 1
        ]);

        $this->transactionService->storeFundTransfer($fundTransfer);

        $this->invoiceNumberService->setNextInvoiceNo($fundTransfer->company_id, 'Fund Transfer', date('Y'));

        return redirect()->route('fund-transfers.index')->with('message', 'Fund Transfer Approved Successfully!');
    }

    public function edit(FundTransfer $fundTransfer)
    {
        $this->hasAccess("fund.transfers.edit");

        $data = $this->dataService->getAccountData(['accounts']);

        return view('fund-transfers.edit', compact('fundTransfer'), $data);
    }

    public function update(Request $request, FundTransfer $fundTransfer): RedirectResponse
    {
        $this->hasAccess("fund.transfers.edit");

        $this->validateData($request);

        try {
            DB::beginTransaction();

//            $from_account_id = $fundTransfer->from_account_id;
//            $to_account_id = $fundTransfer->to_account_id;

            $fundTransfer->where('id', $fundTransfer->id)->update($request->except(['_token', '_method']));
//            $fundTransfer->refresh();
//
//            Transaction::query()->where('invoice_no', $fundTransfer->invoice_no)->where('account_id', $from_account_id)
//                ->update([
//                    'account_id' => $fundTransfer->from_account_id,
//                    'amount' => $fundTransfer->amount,
//                    'balance_type' => $fundTransfer->fromAccount->balance_type
//                ]);
//
//            Transaction::query()->where('invoice_no', $fundTransfer->invoice_no)->where('account_id', $to_account_id)
//                ->update([
//                    'account_id' => $fundTransfer->to_account_id,
//                    'amount' => $fundTransfer->amount,
//                    'balance_type' => $fundTransfer->toAccount->balance_type
//                ]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('fund-transfers.index')->with('message', 'Fund Transferred Successfully');
    }

    public function destroy(FundTransfer $fundTransfer): RedirectResponse
    {
        $this->hasAccess("fund.transfers.delete");

        try {
            Transaction::query()->where('invoice_no', $fundTransfer->invoice_no)->delete();
            $fundTransfer->delete();

            return back()->with('message', 'Fund Transfer Successfully Deleted!');
        } catch (Exception $ex) {
            return back()->with('error', $ex->getMessage());
        }
    }

    private function validateData(Request $request)
    {
        $request->validate([
            'date' => 'required',
            'description' => 'required',
            'from_account_id' => 'required',
            'to_account_id' => 'required|different:from_account_id',
            'amount' => 'required',
        ]);
    }
}
