<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Tests\Concerns\CreatesAccSchema;
use Module\CRM\Models\MailTemplate;
use Module\CRM\Models\CRMCustomer;
use Illuminate\Support\Facades\DB;

/**
 * CRM revival (BUGS #27): the screens behind the c_r_m_customers master used
 * by the booking/banquet/POS company pickers, plus the mail templates the
 * scheduled crm:send-invoice command mails out.
 */
class CrmScreensTest extends TestCase
{
    use CreatesAccSchema;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createMinimalSchema();
        $this->loadModuleRoutes();

        // the master layout reads the hotel group row (fav icon, dashboard setting)
        DB::table('groups')->insert(['name' => 'MM Heritage', 'created_at' => now(), 'updated_at' => now()]);

        // php-wasm reports PHP_SAPI != 'cli', so the framework's unit-test CSRF
        // bypass does not engage here; disable the token check explicitly
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

        // user id 1 bypasses hasAccess() (system admin) - the CRM family is
        // seeded for Super admin/admin only, never the front desk
        $this->actingAs(User::forceCreate(['id' => 1, 'name' => 'Mr. Admin', 'email' => 'admin@test', 'password' => 'x']));
    }

    public function test_customer_index_lists_rows_and_legacy_empty_state(): void
    {
        $html = $this->get('/crm/customers')->assertOk()->getContent();
        $this->assertStringContainsString('No Records Founds Yet!', $html);

        CRMCustomer::create(['org_name' => 'Acme <i>Traders</i>', 'city' => 'Dhaka']);

        $html = $this->get('/crm/customers')->assertOk()->getContent();
        $this->assertStringContainsString('Acme &lt;i&gt;Traders&lt;/i&gt;', $html);
        $this->assertStringNotContainsString('Acme <i>Traders</i>', $html);
    }

    public function test_customer_store_persists_and_validates(): void
    {
        $this->post('/crm/customers', [
            'org_name'     => 'Acme Traders',
            'org_phone'    => '+880 1700',
            'org_email'    => 'ops@acme.test',
            'c_name'       => 'Karim',
            'city'         => 'Dhaka',
            'leads'        => '1',
            'contact_date' => '2026-10-01',
        ])->assertRedirect(route('crm.customers.index'));

        $row = CRMCustomer::query()->first();
        $this->assertNotNull($row);
        $this->assertSame('Acme Traders', $row->org_name);
        $this->assertSame('Karim', $row->c_name);
        $this->assertSame(1, (int) $row->leads);

        // org_name is required; a bad email is rejected
        $this->post('/crm/customers', ['org_name' => ''])->assertSessionHasErrors('org_name');
        $this->post('/crm/customers', ['org_name' => 'X', 'org_email' => 'not-an-email'])->assertSessionHasErrors('org_email');
        $this->assertSame(1, CRMCustomer::query()->count());
    }

    public function test_customer_update_and_destroy(): void
    {
        $customer = CRMCustomer::create(['org_name' => 'Acme Traders', 'leads' => 1]);

        $this->put('/crm/customers/' . $customer->id, [
            'org_name' => 'Acme Traders Ltd',
            'leads'    => '0',
        ])->assertRedirect(route('crm.customers.index'));

        $customer->refresh();
        $this->assertSame('Acme Traders Ltd', $customer->org_name);
        $this->assertSame(0, (int) $customer->leads);

        $this->delete('/crm/customers/' . $customer->id)
            ->assertRedirect(route('crm.customers.index'));
        $this->assertSame(0, CRMCustomer::query()->count());
    }

    public function test_customer_with_bookings_cannot_be_deleted(): void
    {
        $customer = CRMCustomer::create(['org_name' => 'Acme Traders']);
        DB::table('booking')->insert(['company_id' => $customer->id]);

        $this->from('/crm/customers')
            ->delete('/crm/customers/' . $customer->id)
            ->assertRedirect(route('crm.customers.index'))
            ->assertSessionHas('error', 'Customer has bookings and cannot be deleted.');

        $this->assertSame(1, CRMCustomer::query()->count());
    }

    public function test_mail_template_crud(): void
    {
        $this->post('/crm/templates', [
            'subject' => 'Invoice due',
            'body'    => 'Dear customer, your invoice is due.',
            'status'  => '1',
        ])->assertRedirect(route('crm.templates.index'));

        $template = MailTemplate::query()->first();
        $this->assertNotNull($template);
        $this->assertSame(1, (int) $template->status);

        $this->put('/crm/templates/' . $template->id, [
            'subject' => 'Invoice due (friendly)',
            'body'    => 'Friendly reminder.',
            'status'  => '0',
        ])->assertRedirect(route('crm.templates.index'));

        $template->refresh();
        $this->assertSame(0, (int) $template->status);

        // the scheduled command selects the active template: only one may be on
        $this->assertNull(MailTemplate::where('status', 1)->first());

        $this->delete('/crm/templates/' . $template->id)
            ->assertRedirect(route('crm.templates.index'));
        $this->assertSame(0, MailTemplate::query()->count());
    }

    public function test_mail_template_validation(): void
    {
        $this->post('/crm/templates', ['subject' => ''])
            ->assertSessionHasErrors('subject');
        $this->post('/crm/templates', ['subject' => 'X', 'body' => 'Y', 'status' => '9'])
            ->assertSessionHasErrors('status');
        $this->assertSame(0, MailTemplate::query()->count());
    }
}
