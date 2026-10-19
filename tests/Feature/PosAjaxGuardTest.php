<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Tests\Concerns\BuildsShippedSchema;

/**
 * docs/BUGS.md #70 - eight groups of POS/AJAX 500s, each one a null dereference
 * or a missing view rather than a logic error.
 *
 * Every one of them is a route with a parameter or a query string, which is why
 * neither guard notices when they break: PageCrawlAuditTest only GETs
 * PARAMETERLESS routes, and the blade check never runs a controller. The people
 * who hit them are the ones typing in the POS screens.
 *
 * The contract these endpoints share is "answer with something the JS can
 * consume", not "answer with data": an empty body for the sale lookup (the
 * response is injected straight into #product-details), a zeroed object for a
 * detail popover, an empty list for an unshipped table. A 500 is the bug.
 */
class PosAjaxGuardTest extends TestCase
{
    use BuildsShippedSchema;

    protected function setUp(): void
    {
        parent::setUp();
        $this->buildSchemaFromShippedDump();
        $this->seedRuntimeConfig();
        $this->loadAllModuleRoutes();

        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

        // system admin bypasses every hasPermission()/hasAccess() gate, so this
        // measures what the endpoints do, not who may call them
        $this->actingAs(User::find(1));
    }

    public function test_every_endpoint_answers_for_an_unknown_id_or_missing_row(): void
    {
        $cases = [
            // [url, query, what the guard is for]
            ['/bar/get-saleable-products',                   ['invoice_no' => 'NO-SUCH-INVOICE'], 'sale exchange: no matching sale'],
            ['/restaurant/get-saleable-products',            ['invoice_no' => 'NO-SUCH-INVOICE'], 'sale exchange: no matching sale'],
            ['/restaurant/inventory/get-matrial-details',    ['id' => 999999],                    'material detail for unknown id'],
            ['/restaurant/inventory/get-item-details',       ['id' => 999999],                    'item detail for unknown id'],
            ['/bar/inventory/get-product-batches',           ['number' => 'NOPE'],                'batch table may not be shipped'],
            ['/restaurant/inventory/get-product-batches',    ['number' => 'NOPE'],                'batch table may not be shipped'],
            ['/generalstore/ajax/item/get-item-details',         ['id' => 999999],                'requisition detail for unknown id'],
            ['/generalstore/ajax/item/get-item-details/purchase',['id' => 999999],                'purchase detail for unknown id'],
            ['/generalstore/ajax/item/get-item-details/approve', ['id' => 999999],                'approve detail for unknown id'],
            ['/hotel/get-available-room',                    ['room_id' => 999999, 'check_in_date' => '2026-10-20'], 'no check-out date'],
            ['/bar/inventory/inventory-report',              [],                                  'report filter partial'],
            ['/bar/reports/inventory',                       [],                                  'report filter partial'],
            ['/bar/inventory/suppliers/create',              [],                                  'the view that was never written'],
            ['/restaurant/inventory/suppliers/create',       [],                                  'the view that was never written'],
        ];

        $failed = [];

        foreach ($cases as [$url, $query, $why]) {
            $status = $this->get($url . ($query ? '?' . http_build_query($query) : ''))->getStatusCode();

            if ($status !== 200) {
                $failed[] = $url . '=' . $status;
            }
        }

        // Compare a single string, not an array: a truncated CI log keeps only the
        // last line of a failure, and the "actual" line of a string diff is the
        // one that names every endpoint that broke.
        $this->assertSame('ok', empty($failed) ? 'ok' : implode('  ', $failed));
    }

    public function test_the_sale_detail_lookup_answers_with_an_empty_body(): void
    {
        // the JS writes the response straight into #product-details, so an empty
        // body is the contract - not `{}`, not a JSON envelope
        $response = $this->get('/bar/get-saleable-products?invoice_no=NO-SUCH-INVOICE');

        $response->assertOk();
        $this->assertSame('', trim($response->getContent()));
    }

    public function test_the_material_detail_answers_with_zeroed_numbers(): void
    {
        // the popover prefills its inputs from this payload; nulls render as "null"
        $response = $this->get('/restaurant/inventory/get-matrial-details?id=999999');

        $response->assertOk();
        $data = $response->json();

        $this->assertSame(0.0, (float) $data['mat_current_stock']);
        $this->assertSame('', $data['mat_name']);
        $this->assertSame(0.0, (float) $data['mat_price']);
    }
}
