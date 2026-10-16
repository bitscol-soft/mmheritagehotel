<?php

namespace Tests\Concerns;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

/**
 * Minimal in-memory schema for the service/contract tests.
 *
 * The authoritative MySQL schema is database/mmheritagehotel_db.sql; the
 * tables below carry just the columns the code under test reads or writes,
 * so the suite runs on sqlite :memory: without the 172-table dump.
 */
trait CreatesAccSchema
{
    /**
     * The RouteServiceProvider guards module routes behind the `modules`
     * table (Module::active()); under sqlite :memory: that lookup fails and
     * the whole module route set is silently skipped. Tests that assert route
     * names load the module route files through the same wrapper the provider
     * uses.
     */
    protected function loadModuleRoutes(): void
    {
        foreach ([
            'module/Account/routes/web_account.php',
            'module/Hotel/routes/web_hotel.php',
            'module/Hotel/routes/web_hotel_ajax.php',
            'module/CRM/routes/web_crm.php',
            'module/Restaurant/routes/web_restaurant.php',
            'module/Bar/routes/web_bar.php',
        ] as $file) {
            Route::group(['middleware' => ['web', 'auth']], function () use ($file) {
                Route::namespace('\\')->group(base_path($file));
            });
        }

        // fluent ->name() lookups registered outside the provider pipeline can
        // leave the name list stale; rebuild it so getByName() sees them
        app('router')->getRoutes()->refreshNameLookups();
    }

    protected function createMinimalSchema(): void
    {
        Schema::create('accounts', function (Blueprint $t) {
            $t->id();
            $t->string('name')->nullable();
            $t->unsignedBigInteger('account_group_id')->nullable();
            $t->unsignedBigInteger('account_control_id')->nullable();
            $t->unsignedBigInteger('account_subsidiary_id')->nullable();
            $t->unsignedBigInteger('company_id')->nullable();
            $t->string('balance_type')->nullable();
            $t->double('opening_balance')->nullable();
            $t->text('remarks')->nullable();
            $t->tinyInteger('status')->nullable();
            $t->tinyInteger('is_deletable')->nullable();
            $t->unsignedBigInteger('created_by')->nullable();
            $t->unsignedBigInteger('updated_by')->nullable();
            $t->timestamps();
        });

        Schema::create('acc_customers', function (Blueprint $t) {
            $t->id();
            $t->string('name')->nullable();
            $t->string('mobile')->nullable();
            $t->string('email')->nullable();
            $t->text('address')->nullable();
            $t->double('opening_balance')->nullable();
            $t->double('current_balance')->nullable();
            $t->unsignedBigInteger('account_id')->nullable();
            $t->unsignedBigInteger('company_id')->nullable();
            $t->unsignedBigInteger('created_by')->nullable();
            $t->unsignedBigInteger('updated_by')->nullable();
            $t->double('previous_due')->nullable();
            $t->timestamps();
        });

        Schema::create('acc_suppliers', function (Blueprint $t) {
            $t->id();
            $t->string('name')->nullable();
            $t->string('mobile')->nullable();
            $t->string('email')->nullable();
            $t->text('address')->nullable();
            $t->double('opening_balance')->nullable();
            $t->double('current_balance')->nullable();
            $t->unsignedBigInteger('account_id')->nullable();
            $t->unsignedBigInteger('company_id')->nullable();
            $t->unsignedBigInteger('created_by')->nullable();
            $t->unsignedBigInteger('updated_by')->nullable();
            $t->double('previous_due')->nullable();
            $t->timestamps();
        });

        Schema::create('acc_collections', function (Blueprint $t) {
            $t->id();
            $t->unsignedBigInteger('customer_id')->nullable();
            $t->string('date')->nullable();
            $t->string('invoice_no')->nullable();
            $t->string('transaction_no')->nullable();
            $t->string('remarks')->nullable();
            $t->double('amount')->nullable();
            $t->unsignedBigInteger('company_id')->nullable();
            $t->unsignedBigInteger('created_by')->nullable();
            $t->unsignedBigInteger('updated_by')->nullable();
            $t->timestamps();
        });

        Schema::create('acc_payments', function (Blueprint $t) {
            $t->id();
            $t->unsignedBigInteger('supplier_id')->nullable();
            $t->string('date')->nullable();
            $t->string('invoice_no')->nullable();
            $t->string('transaction_no')->nullable();
            $t->string('remarks')->nullable();
            $t->double('amount')->nullable();
            $t->unsignedBigInteger('company_id')->nullable();
            $t->unsignedBigInteger('created_by')->nullable();
            $t->unsignedBigInteger('updated_by')->nullable();
            $t->timestamps();
        });

        Schema::create('transactions', function (Blueprint $t) {
            $t->id();
            $t->string('transactionable_type')->nullable();
            $t->unsignedBigInteger('transactionable_id')->nullable();
            $t->string('invoice_no')->nullable();
            $t->string('date')->nullable();
            $t->string('redirect_path')->nullable();
            $t->string('balance_type')->nullable();
            $t->unsignedBigInteger('account_id')->nullable();
            $t->double('amount')->nullable();
            $t->unsignedBigInteger('created_by')->nullable();
            $t->unsignedBigInteger('company_id')->nullable();
            $t->unsignedBigInteger('updated_by')->nullable();
            $t->text('description')->nullable();
            $t->string('transaction_item_type')->nullable();
            $t->string('batch_id')->nullable();
            $t->double('debit_amount')->nullable();
            $t->double('credit_amount')->nullable();
            $t->timestamps();
        });

        Schema::create('invoice_nos', function (Blueprint $t) {
            $t->id();
            $t->string('type');
            $t->string('year');
            $t->integer('next_id')->default(1);
            $t->unsignedBigInteger('company_id')->nullable();
            $t->timestamps();
        });

        Schema::create('users', function (Blueprint $t) {
            $t->id();
            $t->unsignedBigInteger('company_id')->nullable();
            $t->string('name')->nullable();
            $t->string('email')->nullable();
            $t->string('password')->nullable();
            $t->tinyInteger('status')->nullable();
            $t->string('remember_token')->nullable();
            $t->timestamps();
        });

        Schema::create('booking_carts', function (Blueprint $t) {
            $t->id();
            $t->unsignedBigInteger('room_id');
            $t->unsignedBigInteger('room_category_id')->nullable();
            $t->integer('nights')->default(1);
            $t->integer('guest')->default(1);
            $t->integer('infant')->default(0);
            $t->decimal('category_price', 8, 2);
            $t->string('check_in_date');
            $t->string('check_out_date')->nullable();
            $t->tinyInteger('status')->default(1);
            $t->unsignedBigInteger('user_id')->nullable();
            $t->timestamps();
        });

        Schema::create('rooms', function (Blueprint $t) {
            $t->id();
            $t->string('name')->nullable();
            $t->unsignedBigInteger('room_category')->nullable();
            $t->string('room_number')->nullable();
            $t->tinyInteger('status')->nullable();
            $t->unsignedBigInteger('created_by')->nullable();
            $t->unsignedBigInteger('updated_by')->nullable();
            $t->timestamps();
        });

        Schema::create('room_categories', function (Blueprint $t) {
            $t->id();
            $t->string('name')->nullable();
            $t->decimal('price', 12, 2)->nullable();
            $t->integer('guest_capacity')->nullable();
            $t->tinyInteger('status')->nullable();
            $t->string('url_slug')->nullable();
            $t->unsignedBigInteger('created_by')->nullable();
            $t->unsignedBigInteger('updated_by')->nullable();
            $t->timestamps();
        });

        Schema::create('room_prices', function (Blueprint $t) {
            $t->id();
            $t->unsignedBigInteger('room_category_id')->nullable();
            $t->integer('capacity')->nullable();
            $t->decimal('price', 12, 2)->nullable();
            $t->unsignedBigInteger('created_by')->nullable();
            $t->unsignedBigInteger('updated_by')->nullable();
            $t->timestamps();
        });

        Schema::create('room_photos', function (Blueprint $t) {
            $t->id();
            $t->string('name')->nullable();
            $t->unsignedBigInteger('category_id')->nullable();
            $t->string('relative_path')->nullable();
            $t->unsignedBigInteger('created_by')->nullable();
            $t->unsignedBigInteger('updated_by')->nullable();
            $t->timestamps();
        });

        Schema::create('c_r_m_customers', function (Blueprint $t) {
            $t->id();
            $t->string('org_name')->nullable();
            $t->string('org_phone')->nullable();
            $t->string('org_email')->nullable();
            $t->string('c_name')->nullable();
            $t->string('c_phone')->nullable();
            $t->string('c_email')->nullable();
            $t->integer('tax_id')->nullable();
            $t->string('street')->nullable();
            $t->string('city')->nullable();
            $t->string('address')->nullable();
            $t->integer('zip_code')->nullable();
            $t->unsignedBigInteger('country_id')->nullable();
            $t->boolean('leads')->nullable();
            $t->string('currency')->nullable();
            $t->string('lead_status')->nullable();
            $t->string('lead_source')->nullable();
            $t->string('assigned')->nullable();
            $t->string('contact_date')->nullable();
            $t->unsignedBigInteger('created_by')->nullable();
            $t->unsignedBigInteger('updated_by')->nullable();
            $t->timestamps();
        });

        Schema::create('mail_templates', function (Blueprint $t) {
            $t->id();
            $t->string('subject')->nullable();
            $t->string('body')->nullable();
            $t->tinyInteger('status')->default(1);
            $t->unsignedBigInteger('created_by')->nullable();
            $t->unsignedBigInteger('updated_by')->nullable();
            $t->timestamps();
        });

        Schema::create('companies', function (Blueprint $t) {
            $t->id();
            $t->unsignedBigInteger('group_id')->nullable();
            $t->unsignedBigInteger('business_type_id')->nullable();
            $t->string('name')->nullable();
            $t->string('code')->nullable();
            $t->string('short_name')->nullable();
            $t->string('head_office')->nullable();
            $t->string('factory')->nullable();
            $t->string('contact_name')->nullable();
            $t->string('position')->nullable();
            $t->string('phone_number')->nullable();
            $t->string('fax')->nullable();
            $t->string('email')->nullable();
            $t->string('day_off')->nullable();
            $t->string('country')->nullable();
            $t->text('top_text')->nullable();
            $t->string('logo')->nullable();
            $t->timestamps();
        });

        Schema::create('company_user', function (Blueprint $t) {
            $t->id();
            $t->unsignedBigInteger('company_id');
            $t->unsignedBigInteger('user_id');
            $t->timestamps();
        });

        Schema::create('groups', function (Blueprint $t) {
            $t->id();
            $t->string('name')->nullable();
            $t->string('email')->nullable();
            $t->string('phone')->nullable();
            $t->string('address')->nullable();
            $t->string('logo')->nullable();
            $t->string('fav_icon')->nullable();
            $t->string('login_background_image')->nullable();
            $t->timestamps();
        });

        Schema::create('system_settings', function (Blueprint $t) {
            $t->id();
            $t->string('key')->nullable();
            $t->text('value')->nullable();
            $t->timestamps();
        });

        Schema::create('modules', function (Blueprint $t) {
            $t->id();
            $t->string('name')->nullable();
            $t->tinyInteger('status')->default(1);
            $t->tinyInteger('is_migrate')->default(0);
            $t->integer('rank')->nullable();
            $t->timestamps();
        });

        Schema::create('permissions', function (Blueprint $t) {
            $t->id();
            $t->string('name')->nullable();
            $t->string('slug')->nullable();
            $t->text('description')->nullable();
            $t->unsignedBigInteger('created_by')->nullable();
            $t->unsignedBigInteger('updated_by')->nullable();
            $t->unsignedBigInteger('parent_permission_id')->nullable();
            $t->tinyInteger('status')->default(1);
            $t->timestamps();
        });

        Schema::create('permission_user', function (Blueprint $t) {
            $t->id();
            $t->unsignedBigInteger('permission_id');
            $t->unsignedBigInteger('user_id');
            $t->timestamps();
        });

        Schema::create('countries', function (Blueprint $t) {
            $t->id();
            $t->string('name')->nullable();
            $t->timestamps();
        });

        Schema::create('currencies', function (Blueprint $t) {
            $t->id();
            $t->string('name')->nullable();
            $t->timestamps();
        });

        Schema::create('booking', function (Blueprint $t) {
            $t->id();
            $t->unsignedBigInteger('company_id')->nullable();
            $t->timestamps();
        });

        Schema::create('night_audit_summaries', function (Blueprint $t) {
            $t->id();
            $t->string('date')->nullable();
            $t->tinyInteger('is_rest')->nullable();
            $t->unsignedBigInteger('created_by')->nullable();
            $t->unsignedBigInteger('updated_by')->nullable();
            $t->timestamps();
        });
    }
}
