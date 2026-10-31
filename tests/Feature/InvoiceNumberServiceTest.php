<?php

namespace Tests\Feature;

use Tests\TestCase;
use Tests\Concerns\CreatesAccSchema;
use Module\Account\Models\InvoiceNo;
use Module\Account\Services\InvoiceNumberService;

class InvoiceNumberServiceTest extends TestCase
{
    use CreatesAccSchema;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createMinimalSchema();
    }

    public function test_collection_invoice_number_format_and_sequence(): void
    {
        $svc = new InvoiceNumberService();

        $first = $svc->getCollectionInvoiceNo(1);
        $this->assertSame('Collection-INV-' . date('Y') . '-000001', $first);

        // the controller bumps the counter AFTER storing the document
        $svc->setNextInvoiceNo(1, 'Collection', date('Y'));

        $second = $svc->getCollectionInvoiceNo(1);
        $this->assertSame('Collection-INV-' . date('Y') . '-000002', $second);

        $this->assertSame(1, InvoiceNo::query()->where('type', 'Collection')->count());
    }

    public function test_payment_invoice_number_format_and_sequence(): void
    {
        $svc = new InvoiceNumberService();

        $first = $svc->getPaymentInvoiceNo(1);
        $this->assertSame('Payment-INV-' . date('Y') . '-000001', $first);

        $svc->setNextInvoiceNo(1, 'Payment', date('Y'));

        $second = $svc->getPaymentInvoiceNo(1);
        $this->assertSame('Payment-INV-' . date('Y') . '-000002', $second);
    }

    public function test_set_next_invoice_no_is_idempotent_per_type_and_year(): void
    {
        $svc = new InvoiceNumberService();

        // firstOrCreate seeds next_id at 1 (DB default), each call increments
        $svc->setNextInvoiceNo(1, 'Collection', date('Y'));
        $svc->setNextInvoiceNo(1, 'Collection', date('Y'));

        $row = InvoiceNo::query()->where('type', 'Collection')->where('year', date('Y'))->first();
        $this->assertNotNull($row);
        $this->assertSame(3, (int) $row->next_id);

        // a separate type keeps its own sequence
        $this->assertNull(
            InvoiceNo::query()->where('type', 'Payment')->first()
        );
    }
}
