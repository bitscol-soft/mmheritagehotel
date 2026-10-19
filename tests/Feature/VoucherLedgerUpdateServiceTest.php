<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Module\Account\Models\Account;
use Module\Account\Models\Voucher;
use Module\Account\Models\VoucherDetail;
use Module\Account\Models\Transaction;
use Module\Account\Services\VoucherLedgerUpdateService;

/**
 * Voucher re-entry (BUGS #49).
 *
 * The reason this feature is safe is that nothing stores an account balance:
 * every report sums `transactions`. So the test that matters is not "the fields
 * changed" but "editing a posted voucher leaves exactly one ledger row per
 * account", and "an approved voucher cannot be edited at all".
 */
class VoucherLedgerUpdateServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('vouchers', function (Blueprint $t) {
            $t->id();
            $t->unsignedBigInteger('company_id')->nullable();
            $t->string('invoice_no')->nullable();
            $t->date('date')->nullable();
            $t->string('reference')->nullable();
            $t->text('description')->nullable();
            $t->double('amount')->default(0);
            $t->string('voucher_type')->nullable();
            $t->tinyInteger('is_approved')->default(0);
            $t->string('attachment')->nullable();
            // required by the AutoCreatedUpdated trait Voucher uses
            $t->unsignedBigInteger('created_by')->nullable();
            $t->unsignedBigInteger('updated_by')->nullable();
            $t->timestamps();
        });

        Schema::create('voucher_details', function (Blueprint $t) {
            $t->id();
            $t->unsignedBigInteger('voucher_id');
            $t->unsignedBigInteger('account_id')->nullable();
            $t->double('amount')->default(0);
            $t->string('balance_type')->nullable();
            $t->string('transaction_no')->nullable();
            $t->timestamps();
        });

        Schema::create('transactions', function (Blueprint $t) {
            $t->id();
            $t->string('invoice_no')->nullable();
            $t->unsignedBigInteger('company_id')->nullable();
            $t->unsignedBigInteger('account_id')->nullable();
            $t->double('debit_amount')->default(0);
            $t->double('credit_amount')->default(0);
            $t->date('date')->nullable();
            $t->string('balance_type')->nullable();
            $t->string('transaction_item_type')->nullable();
            $t->text('description')->nullable();
            $t->string('transactionable_type')->nullable();
            $t->unsignedBigInteger('transactionable_id')->nullable();
            $t->string('batch_id')->nullable();
            $t->timestamps();
        });

        Schema::create('accounts', function (Blueprint $t) {
            $t->id();
            $t->string('name')->nullable();
            $t->timestamps();
        });

        Account::insert([
            ['id' => 1, 'name' => 'Cash'],
            ['id' => 2, 'name' => 'Bank'],
            ['id' => 3, 'name' => 'Sales'],
        ]);
    }

    private function voucher(array $over = [])
    {
        $voucher = Voucher::create(array_merge([
            'company_id'   => 1,
            'invoice_no'   => 'RV-2026-0001',
            'date'         => '2026-10-20',
            'reference'    => 'REF-1',
            'description'  => 'Opening cash',
            'amount'       => 500,
            'voucher_type' => 'Receive',
            'is_approved'  => 0,
        ], $over));

        $voucher->details()->create(['account_id' => 1, 'amount' => 500, 'balance_type' => 'Debit']);
        $voucher->details()->create(['account_id' => 3, 'amount' => 500, 'balance_type' => 'Credit']);

        return $voucher->fresh('details');
    }

    /** The request the edit form actually posts. */
    private function payload(Voucher $voucher, array $accounts, array $debits, array $credits, array $over = [])
    {
        return new \Illuminate\Http\Request(array_merge([
            'date'         => '2026-10-21',
            'reference'    => 'REF-2',
            'description'  => 'Corrected amount',
            'detail_ids'   => $voucher->details->pluck('id')->all(),
            'account_ids'  => $accounts,
            'debit'        => $debits,
            'credit'       => $credits,
        ], $over));
    }

    public function test_an_unapproved_voucher_is_editable(): void
    {
        $this->assertTrue(VoucherLedgerUpdateService::canEdit($this->voucher()));
    }

    public function test_an_approved_voucher_is_locked(): void
    {
        $this->assertFalse(VoucherLedgerUpdateService::canEdit($this->voucher(['is_approved' => 1])));
    }

    public function test_update_rewrites_the_header_and_the_lines(): void
    {
        $voucher = $this->voucher();

        $service = new VoucherLedgerUpdateService($voucher, ['debit' => 'Cash', 'credit' => 'Receive']);
        $updated = $service->update($this->payload($voucher, [1, 3], [800, ''], ['', 800]));

        $this->assertEquals('2026-10-21', substr((string) $updated->date, 0, 10));
        $this->assertEquals('REF-2', $updated->reference);
        $this->assertEquals(800, (float) $updated->amount, 'the header total must follow the lines');
        $this->assertCount(2, $updated->details);
        $this->assertEquals(800, (float) $updated->details->firstWhere('account_id', 1)->amount);
    }

    public function test_a_side_that_becomes_zero_reverses_the_balance_type(): void
    {
        $voucher = $this->voucher(['is_approved' => 1]);

        $service = new VoucherLedgerUpdateService($voucher, ['debit' => 'Cash', 'credit' => 'Receive']);
        $updated = $service->update($this->payload($voucher, [1], [300], ['']));

        $detail = $updated->details->first();

        $this->assertSame('Debit', $detail->balance_type);
        $this->assertEquals(300, (float) $detail->amount);
    }

    public function test_editing_a_posted_voucher_leaves_one_ledger_row_per_line(): void
    {
        $voucher = $this->voucher();

        // first posting: simulate approve + post
        $voucher->update(['is_approved' => 1]);
        $service = new VoucherLedgerUpdateService($voucher->fresh(), ['debit' => 'Cash', 'credit' => 'Receive']);
        $service->update($this->payload($voucher, [1, 3], [500, ''], ['', 500]));

        $this->assertEquals(2, Transaction::count(), 'two lines, two ledger rows');

        // now edit again with the same two accounts at new amounts
        $voucher = $voucher->fresh('details');
        $service = new VoucherLedgerUpdateService($voucher, ['debit' => 'Cash', 'credit' => 'Receive']);
        $service->update($this->payload($voucher, [1, 3], [900, ''], ['', 900]));

        $this->assertEquals(2, Transaction::count(), 'a re-post must UPDATE, never duplicate');

        $cash = Transaction::where('account_id', 1)->first();
        $this->assertEquals(900, (float) $cash->debit_amount);
        $this->assertEquals(0, (float) $cash->credit_amount);
    }

    public function test_removing_a_line_removes_its_ledger_row(): void
    {
        $voucher = $this->voucher();
        $voucher->update(['is_approved' => 1]);

        $service = new VoucherLedgerUpdateService($voucher->fresh(), ['debit' => 'Cash', 'credit' => 'Receive']);
        $service->update($this->payload($voucher, [1, 3], [500, ''], ['', 500]));
        $this->assertEquals(2, Transaction::count());

        $voucher = $voucher->fresh('details');
        $service = new VoucherLedgerUpdateService($voucher, ['debit' => 'Cash', 'credit' => 'Receive']);
        $service->update($this->payload($voucher, [1], [500], ['']));

        $this->assertEquals(1, Transaction::count());
        $this->assertEquals(1, $voucher->fresh()->details()->count());
    }

    public function test_a_draft_edit_stays_a_draft_and_posts_nothing(): void
    {
        $voucher = $this->voucher();

        $service = new VoucherLedgerUpdateService($voucher, ['debit' => 'Cash', 'credit' => 'Receive']);
        $updated = $service->update($this->payload($voucher, [1, 3], [700, ''], ['', 700]));

        $this->assertEquals(0, (int) $updated->is_approved);
        $this->assertEquals(0, Transaction::count(), 'a draft must not touch the ledger');
    }

    public function test_blank_lines_are_dropped_rather_than_saved_as_zero(): void
    {
        $voucher = $this->voucher();

        $service = new VoucherLedgerUpdateService($voucher, ['debit' => 'Cash', 'credit' => 'Receive']);
        $updated = $service->update($this->payload($voucher, [1, 2], [250, ''], ['', '']));

        $this->assertCount(1, $updated->details);
    }
}
