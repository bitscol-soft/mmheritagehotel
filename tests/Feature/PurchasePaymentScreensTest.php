<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Tests\Concerns\CreatesAccSchema;
use Illuminate\Support\Facades\DB;
use Module\Account\Models\Account;
use Module\Account\Models\Supplier;
use Module\Account\Models\Transaction;

/**
 * docs/BUGS.md #62 - the purchase/payments entry screen could never persist.
 *
 * `Module\Account\Models\Payment` had no `$table`, so every write went to a
 * non-existent `payments` table. AccPaymentServiceTest pinned the service; this
 * pins the HTTP layer, because the controller binds `Payment $payment` and the
 * resource route generates `{acc_payment}`. Those two only line up when the
 * resource declares the parameter explicitly, and when they do not the action
 * runs on an empty model - `refresh()` returns null and makeTransaction()
 * dereferences it.
 */
class PurchasePaymentScreensTest extends TestCase
{
    use CreatesAccSchema;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createMinimalSchema();
        $this->loadModuleRoutes();

        // the master layout reads the hotel group row
        DB::table('groups')->insert(['name' => 'MM Heritage', 'created_at' => now(), 'updated_at' => now()]);

        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
        $this->actingAs(User::forceCreate(['id' => 1, 'name' => 'Mr. Admin', 'email' => 'admin@test', 'password' => 'x']));

        Account::create(['id' => 55, 'name' => 'Cash']);          // config('account.cash')
        Account::create(['id' => 9, 'name' => 'Karim A/C']);
        Supplier::create(['id' => 1, 'name' => 'Karim Traders', 'account_id' => 9]);
    }

    private function payload(array $over = []): array
    {
        return array_merge([
            'supplier_id'    => 1,
            'date'           => '2026-10-05',
            'amount'         => 250,
            'transaction_no' => 'CHQ-9',
            'remarks'        => 'Cash',
            'company_id'     => 1,
            'account_id'     => 55,
        ], $over);
    }

    public function test_store_persists_the_payment_and_its_two_ledger_rows(): void
    {
        $response = $this->post('/purchase/acc-payments', $this->payload());

        $response->assertRedirect(route('acc-payments.index'));
        $response->assertSessionMissing('error');

        $this->assertSame(1, DB::table('acc_payments')->count(), 'the payment must land in acc_payments');

        $debit = Transaction::query()->where('balance_type', 'debit')->first();
        $this->assertNotNull($debit, 'supplier debit row missing');
        $this->assertSame(9, (int) $debit->account_id);
        $this->assertSame(250.0, (float) $debit->debit_amount);

        $credit = Transaction::query()->where('balance_type', 'credit')->first();
        $this->assertNotNull($credit, 'cash credit row missing');
        $this->assertSame(55, (int) $credit->account_id);
    }

    public function test_update_rebinds_the_payment_and_reposts_the_ledger(): void
    {
        $this->post('/purchase/acc-payments', $this->payload());
        $id = (int) DB::table('acc_payments')->value('id');
        $this->assertGreaterThan(0, $id, 'nothing was stored, so there is nothing to update');

        $response = $this->put('/purchase/acc-payments/' . $id, $this->payload(['amount' => 400]));

        $response->assertRedirect(route('acc-payments.index'));
        $response->assertSessionMissing('error');

        $this->assertSame(400.0, (float) DB::table('acc_payments')->where('id', $id)->value('amount'));

        // a re-post updates the same two rows - it never adds two more
        $this->assertSame(2, Transaction::query()->count());
        $this->assertSame(400.0, (float) Transaction::query()->where('balance_type', 'debit')->first()->debit_amount);
    }
}
