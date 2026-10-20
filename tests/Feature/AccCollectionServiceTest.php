<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\Request;
use Tests\Concerns\CreatesAccSchema;
use Module\Account\Models\Account;
use Module\Account\Models\Customer;
use Module\Account\Models\Transaction;
use Module\Account\Services\AccCollectionService;

class AccCollectionServiceTest extends TestCase
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
            'customer_id'   => 1,
            'date'          => '2026-10-05',
            'transaction_no' => 'RCPT-4',
            'remarks'       => 'Cash',
            'amount'        => 500,
            'company_id'    => 1,
        ], $over));
    }

    public function test_store_creates_collection_with_generated_invoice_number(): void
    {
        Account::create(['id' => 55, 'name' => 'Cash']);
        $customerAccount = Account::create(['id' => 7, 'name' => 'Rahim A/C']);
        Customer::create(['id' => 1, 'name' => 'Rahim Stores', 'account_id' => $customerAccount->id]);

        $svc = new AccCollectionService();
        $collection = $svc->store($this->makeRequest());

        $this->assertSame('Collection-INV-' . date('Y') . '-000001', $collection->invoice_no);
        $this->assertSame(1, (int) $collection->customer_id);
        $this->assertSame('2026-10-05', $collection->date);
        $this->assertSame(500.0, (float) $collection->amount);
        $this->assertSame('RCPT-4', $collection->transaction_no);
        $this->assertSame('Cash', $collection->remarks);
    }

    public function test_make_transaction_posts_debit_cash_and_credit_customer(): void
    {
        Account::create(['id' => 55, 'name' => 'Cash']);                 // config('account.cash')
        $customerAccount = Account::create(['id' => 7, 'name' => 'Rahim A/C']);
        Customer::create(['id' => 1, 'name' => 'Rahim Stores', 'account_id' => $customerAccount->id]);

        $svc = new AccCollectionService();
        $collection = $svc->store($this->makeRequest());
        $collection->update(['company_id' => 1]);

        $svc->makeTransaction();

        $debit = Transaction::query()->where('balance_type', 'debit')->first();
        $this->assertNotNull($debit);
        $this->assertSame('Cash', $debit->transaction_item_type);
        $this->assertSame(55, (int) $debit->account_id);
        $this->assertSame(500.0, (float) $debit->debit_amount);
        $this->assertSame(0.0, (float) $debit->credit_amount);
        $this->assertSame($collection->invoice_no, $debit->invoice_no);
        $this->assertSame('Collection', $debit->transactionable_type);
        $this->assertSame((string) 'Collection-' . $collection->id, $debit->batch_id);
        $this->assertStringContainsString('Collection from Rahim Stores', $debit->description);

        $credit = Transaction::query()->where('balance_type', 'credit')->first();
        $this->assertNotNull($credit);
        $this->assertSame('Receive', $credit->transaction_item_type);
        $this->assertSame(7, (int) $credit->account_id);
        $this->assertSame(0.0, (float) $credit->debit_amount);
        $this->assertSame(500.0, (float) $credit->credit_amount);

        $this->assertSame(2, Transaction::query()->count());
    }

    public function test_update_reposts_the_ledger(): void
    {
        Account::create(['id' => 55, 'name' => 'Cash']);
        $customerAccount = Account::create(['id' => 7, 'name' => 'Rahim A/C']);
        Customer::create(['id' => 1, 'name' => 'Rahim Stores', 'account_id' => $customerAccount->id]);

        $svc = new AccCollectionService();
        $collection = $svc->store($this->makeRequest());
        $collection->update(['company_id' => 1]);
        $svc->makeTransaction();

        $before = Transaction::query()->pluck('id')->all();

        $svc->update($this->makeRequest(['amount' => 750]), $collection);
        $svc->makeTransaction();

        $this->assertSame(2, Transaction::query()->count());
        // update() deletes the doc's transactions first, then re-posts: fresh rows
        foreach ($before as $oldId) {
            $this->assertNull(Transaction::find($oldId));
        }
        $this->assertSame(750.0, (float) Transaction::query()->where('balance_type', 'debit')->first()->debit_amount);
        $this->assertSame(750.0, (float) Transaction::query()->where('balance_type', 'credit')->first()->credit_amount);
        $this->assertSame(750.0, (float) $collection->fresh()->amount);
    }

    public function test_zero_amount_repost_removes_the_ledger_rows(): void
    {
        Account::create(['id' => 55, 'name' => 'Cash']);
        $customerAccount = Account::create(['id' => 7, 'name' => 'Rahim A/C']);
        Customer::create(['id' => 1, 'name' => 'Rahim Stores', 'account_id' => $customerAccount->id]);

        $svc = new AccCollectionService();
        $collection = $svc->store($this->makeRequest());
        $collection->update(['company_id' => 1]);
        $svc->makeTransaction();
        $this->assertSame(2, Transaction::query()->count());

        $svc->update($this->makeRequest(['amount' => 0]), $collection);
        $svc->makeTransaction();

        $this->assertSame(0, Transaction::query()->count());
    }
}
