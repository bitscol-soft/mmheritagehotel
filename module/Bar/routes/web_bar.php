<?php


use Illuminate\Support\Facades\Route;
use Module\Bar\Controllers\{
    AjaxController,                         BarNightAuditController,                SaleController,
    SaleV2Controller,                       PurchaseController,                     SupplierController,
    SaleReturnController,                   TableManageController,                  SaleExchangeController,
};
use Module\Bar\Controllers\Report\{
    ReportController,                       CashFlowController,                     SaleReportController,
};
use Module\Bar\Controllers\Inventory\{
    ProductController,                      InventoryController,                    ProductUnitController,
    ProductUploadController,                ProductCategoryController,              ProductPackageController,
};


/**
 *
 * ---------------------------------------------------------------
 * FOOD MENU ROUTE
 * ---------------------------------------------------------------
 *
 */
Route::get('drink-menu-bar',          [ProductController::class, 'getMenu'])->name('getFoodMenu');

Route::group(['prefix'  => 'bar', 'as' => 'bar.'], function () {


    Route::prefix('inventory')->group(function () {
        Route::resources([
            'products'              => ProductController::class,
            'inventory-report'      => InventoryController::class,
            'product-uploads'       => ProductUploadController::class,
        ]);
                // round 3 (docs/BUGS.md): ProductCategoryController does not implement create/edit/show; the routes would 500
                Route::resource('product-categories', ProductCategoryController::class)->except(['create', 'edit', 'show']);
                // round 3 (docs/BUGS.md): ProductUnitController does not implement create/edit/show; the routes would 500
                Route::resource('product-units', ProductUnitController::class)->except(['create', 'edit', 'show']);
                // round 3 (docs/BUGS.md): SupplierController does not implement edit/show/update; the routes would 500
                Route::resource('suppliers', SupplierController::class)->except(['edit', 'show', 'update']);
                // round 3 (docs/BUGS.md): ProductPackageController does not implement edit/show/update; the routes would 500
                Route::resource('packages', ProductPackageController::class)->except(['edit', 'show', 'update']);


        /**
         *
         * ---------------------------------------------------------------
         * UPLOAD ROUTE
         * ---------------------------------------------------------------
         *
         */
        Route::delete('upload-upload-all-delete', [ProductUploadController::class, 'deleteAll'])->name('product.upload-list.delete');

        Route::post('product-add-confirm-list', [ProductUploadController::class, 'addToConfirm'])->name('product.add-confirm-list');







        /**
         *
         * ---------------------------------------------------------------
         * AJAX ROUTE
         * ---------------------------------------------------------------
         *
         */

        Route::get('get-products',          [ProductController::class, 'getProduct']);
        Route::get('get-products-all',      [ProductController::class, 'getProductAll']);
        Route::get('get-product-batches',   [ProductController::class, 'getProductBatch'])->name('get.batch');

        Route::get('get-saleable-data',     [AjaxController::class, 'getSale'])->name('get-sale-data');


    });


    Route::resources([
        'sales'                     => SaleController::class,
        'sales-v2'                  => SaleV2Controller::class,
        'purchases'                 => PurchaseController::class,
        'sale-returns'              => SaleReturnController::class,
        'sale-exchanges'            => SaleExchangeController::class,
    ]);
            // round 3 (docs/BUGS.md): TableManageController does not implement create/edit/show; the routes would 500
            Route::resource('table-manages', TableManageController::class)->except(['create', 'edit', 'show']);



    Route::delete('sale-item/{id}', [SaleV2Controller::class, 'destroySaleItem'])->name('sale-item-delete');
    Route::post('update-sale-subtotal-with-transaction/{id}', [SaleV2Controller::class, 'updateCalculation'])->name('update-sale-subtotal-with-transaction');
    Route::get('update-paid-amount-with-transaction/{id}',    [SaleV2Controller::class, 'updatePaidAmount'])->name('update-paid-amount-with-transaction');


    // Bar Due Controller
    Route::post('due-collection/{id}',      [SaleV2Controller::class, 'dueCollection'])->name('due-collection');


    /**
     *
     * ---------------------------------------------------------------
     * REPORT ROUTE
     * ---------------------------------------------------------------
     *
     */
    Route::group(['prefix' => 'reports', 'as' => 'report.'], function () {


        Route::get('cash-flows',    [CashFlowController::class, 'index'])->name('cash-flows');
        Route::get('sales',         [SaleReportController::class, 'index'])->name('sales');
        Route::get('today',         [ReportController::class, 'todayReport'])->name('today');
        Route::get('inventory',     [ReportController::class, 'inventory'])->name('inventory');
    });








    /**
     *
     * ---------------------------------------------------------------
     * SEPARATE AJAX ROUTE
     * ---------------------------------------------------------------
     *
     **/

    Route::get('get-drugs',                         [AjaxController::class, 'getDrug'])->name('get-drugs');
    Route::get('get-purchasable-products/{id}',     [AjaxController::class, 'getPurchasableProduct'])->name('get-purchasable-products');
    Route::get('get-product-by-sale-invoice',       [AjaxController::class, 'getProductBySaleInvoice'])->name('get-product.sale.invoice');
    Route::get('get-saleable-products',             [AjaxController::class, 'getSaleableProduct'])->name('get-product.sale.invoice');


    // Save Guest from Ajax
    Route::post('save-guest-data',                  [AjaxController::class, 'saveGuestData'])->name('save-guest-data');



    // Save Guest from Ajax
    Route::get('get-guest-data',                  [AjaxController::class, 'GetGuestData'])->name('get-guest-data');




    // ---------------------------------------------------------------//
    //               CUSTOM ROUTE FOR RESTOURANT NIGHT AUDIT          //
    // ---------------------------------------------------------------//
    Route::get('night-audit',              [BarNightAuditController::class, 'index'])->name('night-audits.index');
    Route::get('night-audit-create',       [BarNightAuditController::class, 'create'])->name('night-audits.create');
    Route::get('night-audit-show/{id}',    [BarNightAuditController::class, 'show'])->name('night-audits.show');



});
