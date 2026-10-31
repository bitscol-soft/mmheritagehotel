<?php

namespace Module\Account\Services;

use Module\Account\Models\Payment;

class AccPaymentService
{
    public $invoiceNumberService;

    public $transactionService;

    public $payment;

    public function __construct()
    {
        $this->invoiceNumberService = new InvoiceNumberService();

        $this->transactionService   = new AccountTransactionService();
    }


    public function store($request)
    {
        $invoice_no = $this->invoiceNumberService->getPaymentInvoiceNo($request->company_id);

        $this->payment = Payment::create([
            'supplier_id'   => $request->supplier_id,
            'date'          => $request->date,
            'invoice_no'    => $invoice_no,
            'transaction_no'=> $request->transaction_no,
            'remarks'       => $request->remarks,
            'amount'        => $request->amount,
        ]);

        return $this->payment;
    }


    public function update($request, Payment $payment)
    {
        $payment->transactions()->delete();

        $payment->update([
            'supplier_id'   => $request->supplier_id,
            'date'          => $request->date,
            'transaction_no'=> $request->transaction_no,
            'remarks'       => $request->remarks,
            'amount'        => $request->amount,
        ]);

        $this->payment = $payment;

        return $this->payment;
    }


    public function makeTransaction()
    {
        $cash_account       = $this->transactionService->getCashAccount();                 // credit
        $supplier_account   = optional(optional($this->payment)->supplier)->account;       // debit

        $payment            = $this->payment->refresh();
        $invoice_no         = $payment->invoice_no;
        $date               = $payment->date;

        $description        = 'Payment to ' . (optional($payment->supplier)->name ?? 'Mr. Supplier');

        $this->transactionService->storeTransaction($payment->company_id, $payment, $invoice_no, $supplier_account, $payment->amount, 0,                 $date, 'debit',  'Payment', $description);   //  Payable Cleared
        $this->transactionService->storeTransaction($payment->company_id, $payment, $invoice_no, $cash_account,     0,                 $payment->amount, $date, 'credit', 'Cash',    $description);   //  Money Out
    }
}
