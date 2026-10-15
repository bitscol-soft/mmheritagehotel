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
