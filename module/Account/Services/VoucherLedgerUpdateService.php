<?php

namespace Module\Account\Services;

use Module\Account\Models\Account;
use Module\Account\Models\Voucher;
use Module\Account\Models\VoucherDetail;

/**
 * Re-entry of an unapproved voucher (BUGS #49).
 *
 * Why this is safe to do as "delete the old lines, write the new ones":
 *
 *   - Every account balance in this application is DERIVED, not stored. The
 *     trial balance and the ledger reports all aggregate `transactions`
 *     (TransactionLedgerReportService does `SUM(debit_amount)`), and there is no
 *     `balance` column on `accounts` to keep in step.
 *   - `AccountTransactionService::storeTransaction()` already keys a
 *     transaction on (model, invoice_no, balance_type, transaction_item_type,
 *     account_id) and *updates* rather than inserting a second row, so
 *     regenerating the lines cannot duplicate the ledger.
 *   - Only an UNAPPROVED voucher may be edited. Once approved the voucher is
 *     locked; the caller is told to delete (which reverses the entries) and
 *     post a new one.
 *
 * The debit/credit split follows the four voucher services exactly: a line with
 * debit > credit is a Debit line, everything else is a Credit line, and the
 * amount is their sum because one side is always zero.
 */
class VoucherLedgerUpdateService
{
    /** @var Voucher */
    private $voucher;

    private $transactionService;

    private $invoiceNumberService;

    /** transaction_item_type prefixes, same strings the store services use */
    private $itemTypes;

    public function __construct(Voucher $voucher, array $itemTypes)
    {
        $this->voucher = $voucher;
        $this->itemTypes = $itemTypes;
        $this->transactionService = new AccountTransactionService();
        $this->invoiceNumberService = new InvoiceNumberService();
    }

    public static function canEdit(Voucher $voucher): bool
    {
        return !$voucher->is_approved;
    }

    /**
     * Replace the voucher's lines with the submitted ones.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Voucher
     */
    public function update($request)
    {
        $this->voucher->update([
            'date'        => $request->date,
            'reference'   => $request->reference,
            'description' => $request->description,
            'amount'      => array_sum((array) $request->debit),
        ]);

        // Old lines: remove their ledger rows first (the morph target must exist
        // when the transaction row is deleted, otherwise the delete is orphaned).
        foreach ($this->voucher->details as $detail) {
            $detail->transactions()->delete();
            $detail->delete();
        }

        $this->voucher->load('details');
        $this->writeDetails($request);

        // A draft stays a draft; an approved voucher keeps its number and is
        // re-posted with the corrected lines.
        if ($this->voucher->is_approved) {
            $this->postTransactions();
        }

        return $this->voucher->fresh('details');
    }

    private function writeDetails($request)
    {
        foreach ((array) $request->account_ids as $key => $account_id) {
            if (!$account_id) {
                continue;
            }

            $debit  = (float) ($request->debit[$key] ?? 0);
            $credit = (float) ($request->credit[$key] ?? 0);

            if ($debit == 0.0 && $credit == 0.0) {
                continue;
            }

            // Keep the old detail row (and its transaction_no) when the form
            // sends one back for this position; otherwise it is a new line.
            $existing = isset($request->detail_ids[$key])
                ? $this->voucher->details->firstWhere('id', $request->detail_ids[$key])
                : null;

            $attributes = [
                'account_id'   => $account_id,
                'amount'       => $debit + $credit,
                'balance_type' => $debit > $credit ? 'Debit' : 'Credit',
            ];

            if ($existing && !$existing->transaction_no) {
                $existing->update($attributes);
            } else {
                $this->voucher->details()->create($attributes);
            }
        }
    }

    private function postTransactions()
    {
        foreach ($this->voucher->details as $key => $detail) {
            $detail->update([
                'transaction_no' => $this->invoiceNumberService
                    ->getVoucherDetailTransactionNo($key, $this->voucher->invoice_no),
            ]);

            $isDebit = $detail->balance_type === 'Debit';

            $account = Account::find($detail->account_id);

            if (!$account) {
                continue;
            }

            $this->transactionService->storeTransaction(
                $this->voucher->company_id,
                $detail,
                $this->voucher->invoice_no,
                $account,
                $isDebit ? $detail->amount : 0,
                $isDebit ? 0 : $detail->amount,
                $this->voucher->date,
                $detail->balance_type,
                $this->itemType($isDebit),
                $this->voucher->description
            );
        }
    }

    /**
     * The store services distinguish voucher types by this string ('Cash',
     * 'Receive', 'Contra In', ...). An update must reuse the SAME string or the
     * upsert would create a parallel ledger row instead of updating the old one.
     */
    private function itemType($isDebit)
    {
        return $isDebit ? $this->itemTypes['debit'] : $this->itemTypes['credit'];
    }
}
