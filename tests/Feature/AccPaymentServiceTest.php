<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\Request;
use Tests\Concerns\CreatesAccSchema;
use Module\Account\Models\Account;
use Module\Account\Models\Supplier;
use Module\Account\Models\Transaction;
use Module\Account\Services\AccPaymentService;

class AccPaymentServiceTest extends TestCase
{
    use CreatesAccSchema;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createMinimalSchema();
    }

    protected function makeRequest(array $over = []): Request
    {
        return new Request(array_merge([
            'supplier_id'   => 1,
            'date'          => '2026-10-05',
            'transaction_no' => 'CHQ-9',
            'remarks'       => 'Cash',
            'amount'        => 250,
            'company_id'    => 1,
        ], $over));
    }

    public function test_store_creates_payment_with_generated_invoice_number(): void
    {
        Account::create(['id' => 55, 'name' => 'Cash']);
        $supplierAccount = Account::create(['id' => 9, 'name' => 'Karim A/C']);
        Supplier::create(['id' => 1, 'name' => 'Karim Traders', 'account_id' => $supplierAccount->id]);

        $svc = new AccPaymentService();
        $payment = $svc->store($this->makeRequest());

        $this->assertSame('Payment-INV-' . date('Y') . '-000001', $payment->invoice_no);
        $this->assertSame(1, (int) $payment->supplier_id);
        $this->assertSame('2026-10-05', $payment->date);
        $this->assertSame(250.0, (float) $payment->amount);
        $this->assertSame('CHQ-9', $payment->transaction_no);
    }

    public function test_make_transaction_debits_supplier_and_credits_cash(): void
    {
        Account::create(['id' => 55, 'name' => 'Cash']);
        $supplierAccount = Account::create(['id' => 9, 'name' => 'Karim A/C']);
        Supplier::create(['id' => 1, 'name' => 'Karim Traders', 'account_id' => $supplierAccount->id]);

        $svc = new AccPaymentService();
        $payment = $svc->store($this->makeRequest());
        $payment->update(['company_id' => 1]);

        $svc->makeTransaction();

        $debit = Transaction::query()->where('balance_type', 'debit')->first();
        $this->assertNotNull($debit);
        $this->assertSame('Payment', $debit->transaction_item_type);
        $this->assertSame(9, (int) $debit->account_id);
        $this->assertSame(250.0, (float) $debit->debit_amount);
        $this->assertSame(0.0, (float) $debit->credit_amount);
        $this->assertSame('Payment', $debit->transactionable_type);
        $this->assertSame('Payment-' . $payment->id, $debit->batch_id);
        $this->assertStringContainsString('Payment to Karim Traders', $debit->description);

        $credit = Transaction::query()->where('balance_type', 'credit')->first();
        $this->assertNotNull($credit);
        $this->assertSame('Cash', $credit->transaction_item_type);
        $this->assertSame(55, (int) $credit->account_id);
        $this->assertSame(0.0, (float) $credit->debit_amount);
        $this->assertSame(250.0, (float) $credit->credit_amount);

        $this->assertSame(2, Transaction::query()->count());
    }

    public function test_update_reposts_and_zero_amount_clears(): void
    {
        Account::create(['id' => 55, 'name' => 'Cash']);
        $supplierAccount = Account::create(['id' => 9, 'name' => 'Karim A/C']);
        Supplier::create(['id' => 1, 'name' => 'Karim Traders', 'account_id' => $supplierAccount->id]);

        $svc = new AccPaymentService();
        $payment = $svc->store($this->makeRequest());
        $payment->update(['company_id' => 1]);
        $svc->makeTransaction();

        $svc->update($this->makeRequest(['amount' => 400]), $payment);
        $svc->makeTransaction();

        $this->assertSame(2, Transaction::query()->count());
        $this->assertSame(400.0, (float) Transaction::query()->where('balance_type', 'debit')->first()->debit_amount);

        $svc->update($this->makeRequest(['amount' => 0]), $payment);
        $svc->makeTransaction();

        $this->assertSame(0, Transaction::query()->count());
    }
}
