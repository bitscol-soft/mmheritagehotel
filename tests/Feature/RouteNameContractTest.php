<?php

namespace Tests\Feature;

use Tests\TestCase;
use Tests\Concerns\CreatesAccSchema;

/**
 * Locks the route names and URLs the frontend JS, Blade views and the
 * preservation suites rely on (docs/FRONTEND-DEV-PLAN.md contracts).
 */
class RouteNameContractTest extends TestCase
{
    use CreatesAccSchema;

    protected function setUp(): void
    {
        parent::setUp();
        $this->loadModuleRoutes();
    }

    protected function named(string $name)
    {
        return app('router')->getRoutes()->getByName($name);
    }

    public function test_cart_endpoints_keep_their_names_and_urls(): void
    {
        $cart = $this->named('booking.cart');
        $this->assertNotNull($cart);
        $this->assertSame('booking-cart', $cart->uri());

        $add = $this->named('add.to.cart');
        $this->assertNotNull($add);
        $this->assertSame('add-to-cart', $add->uri());

        $remove = $this->named('remove.to.cart');
        $this->assertNotNull($remove);
        $this->assertSame('remove-to-cart', $remove->uri());
    }

    public function test_booking_flow_route_names_stay_registered(): void
    {
        foreach (['booking.ui', 'booking.available', 'booking.next.step'] as $name) {
            $this->assertNotNull($this->named($name), $name);
        }
    }

    public function test_acc_payments_resource_names_are_hyphenated_and_skip_show(): void
    {
        foreach (['index', 'create', 'store', 'edit', 'update', 'destroy'] as $action) {
            $this->assertNotNull($this->named('acc-payments.' . $action), 'acc-payments.' . $action);
        }
        $this->assertNull($this->named('acc-payments.show'));
    }

    public function test_crm_route_names_and_urls(): void
    {
        $this->assertSame('crm/customers', $this->named('crm.customers.index')->uri());
        $this->assertSame('crm/customers/create', $this->named('crm.customers.create')->uri());
        $this->assertSame('crm/customers/{customer}', $this->named('crm.customers.update')->uri());

        $this->assertSame('crm/templates', $this->named('crm.templates.index')->uri());
        $this->assertSame('crm/templates/create', $this->named('crm.templates.create')->uri());
        $this->assertSame('crm/templates/{template}', $this->named('crm.templates.update')->uri());

        // neither resource exposes a show screen (round 3 convention)
        $this->assertNull($this->named('crm.customers.show'));
        $this->assertNull($this->named('crm.templates.show'));
    }

    public function test_acc_collections_resource_names_are_underscored_and_skip_show(): void
    {
        foreach (['index', 'create', 'store', 'edit', 'update', 'destroy'] as $action) {
            $this->assertNotNull($this->named('acc_collections.' . $action), 'acc_collections.' . $action);
        }
        $this->assertNull($this->named('acc_collections.show'));
    }
}
