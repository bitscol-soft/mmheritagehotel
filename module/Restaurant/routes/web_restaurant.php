<?php


use Illuminate\Support\Facades\Route;
use Module\Restaurant\Controllers\AjaxController;
use Module\Restaurant\Controllers\SaleController;
use Module\Restaurant\Controllers\SaleV2Controller;
use Module\Restaurant\Controllers\PurchaseController;
use Module\Restaurant\Controllers\SupplierController;
use Module\Restaurant\Controllers\SaleReturnController;
use Module\Restaurant\Controllers\TableManageController;
use Module\Restaurant\Controllers\SaleExchangeController;
use Module\Restaurant\Controllers\Report\ReportController;
use Module\Restaurant\Controllers\Kitchen\KitchenController;
use Module\Restaurant\Controllers\Report\CashFlowController;
use Module\Restaurant\Controllers\Inventory\ProductController;
use Module\Restaurant\Controllers\Report\SaleReportController;
use Module\Restaurant\Controllers\Inventory\InventoryController;
use Module\Restaurant\Controllers\Kitchen\KitchenOrderController;
use Module\Restaurant\Controllers\RestaurantNightAuditController;
use Module\Restaurant\Controllers\Inventory\ProductUnitController;
use Module\Restaurant\Controllers\Inventory\RstPurchaseController;
use Module\Restaurant\Controllers\Inventory\RestMaterialController;
use Module\Restaurant\Controllers\Inventory\ProductUploadController;
use Module\Restaurant\Controllers\Inventory\RstProductionController;
use Module\Restaurant\Controllers\Inventory\MatrialProductController;
use Module\Restaurant\Controllers\Inventory\ProductCategoryController;
use Module\Restaurant\Controllers\Inventory\StockAdjustmentController;
use Module\Restaurant\Controllers\Inventory\RestMaterialUnitController;
// use Module\Restaurant\Controllers\Inventory\StockAdjustmentController;


/**
 *
 * ---------------------------------------------------------------
 * FOOD MENU ROUTE
 * ---------------------------------------------------------------
 *
 */
Route::get('food-menu-restaurant',          [ProductController::class, 'getMenu'])->name('getFoodMenu');


Route::group(['prefix'  => 'restaurant', 'as' => 'rst.'], function () {



    Route::prefix('inventory')->group(function () {
        Route::resources([
            'product-categories'    => ProductCategoryController::class,
            'product-units'         => ProductUnitController::class,
            'products'              => ProductController::class,
            'suppliers'             => SupplierController::class,
            'inventory-report'      => InventoryController::class,
            'product-uploads'       => ProductUploadController::class,
            'stock-adjustment'      => StockAdjustmentController::class,
            'material-unit'         => RestMaterialUnitController::class,
            'material'              => RestMaterialController::class,
            'mat-products'          => MatrialProductController::class,
            'purchase'              => RstPurchaseController::class,
            'production'            => RstProductionController::class,
        ]);
        Route::get('adjustment-delete/{id}',                [StockAdjustmentController::class, 'delete'])->name('stock-adjustment.delete');



        Route::get('get-all-product',                       [ProductController::class, 'getAllProduct'])->name('get-all-product');
        Route::get('getItemList',                           [RstPurchaseController::class, 'getItemList'])->name('getItemList');
        Route::get('get-item-details/approve',              [RstPurchaseController::class, 'getItemDetailsForApprove']);


         /**
         *
         * ---------------------------------------------------------------
         * GET PRODUCT AJAX ROUTE
         * ---------------------------------------------------------------
         *
         */
        Route::get('getProduct',                            [RstPurchaseController::class, 'getProduct'])->name('getProduct');

        // Matrial Product
        Route::get('GetMetrial',              [RstProductionController::class, 'getMatList'])->name('get-item');
        Route::get('GetProducts',             [RstProductionController::class, 'getProductsList'])->name('get-product');
        Route::get('get-matrial-details',     [RstProductionController::class, 'getMetrialDetails']);
        Route::get('get-item-details',        [RstProductionController::class, 'getItemDetails']);

        // Assign Matrial To Product
        Route::post('assign-mat-product',     [RstProductionController::class, 'AssignMatrialToProduct'])->name('product-matrial.store');



        /**
         *
         * ---------------------------------------------------------------
         * Purchase ROUTE
         * ---------------------------------------------------------------
         *
         */
        Route::get('purchase-approve/{id}', [RstPurchaseController::class, 'purchaseApproveShow'])->name('approve.purchase.show');
        Route::put('purchase-approve/{purchase}', [RstPurchaseController::class, 'purchaseApprove'])->name('approve.purchase');
        Route::get('purchase/unapprove/{purchase}', [RstPurchaseController::class, 'purchaseUnapprove'])->name('unapprove.purchase');

        // approve.purchase




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
        Route::get('get-product-batches',   [ProductController::class, 'getProductBatch'])->name('get.batch');

        Route::get('get-saleable-data',     [AjaxController::class, 'getSale'])->name('get-sale-data');
        Route::get('get-tables',            [AjaxController::class, 'getTable'])->name('get-tables');

        Route::get('get-date',              [AjaxController::class, 'getDate'])->name('get-date');






    });


    Route::resources([
        'sales'                     => SaleController::class,
        'sales-v2'                  => SaleV2Controller::class,
        'purchases'                 => PurchaseController::class,
        'sale-returns'              => SaleReturnController::class,
        'sale-exchanges'            => SaleExchangeController::class,
        'table-manages'             => TableManageController::class,
        'night-audits'              => RestaurantNightAuditController::class,
    ]);



    Route::delete('sale-item/{id}', [SaleV2Controller::class, 'destroySaleItem'])->name('sale-item-delete');
    // Route::post('update-sale-subtotal-with-transaction/{id}', [SaleV2Controller::class, 'updateCalculation'])->name('update-sale-subtotal-with-transaction');
    // Route::get('update-paid-amount-with-transaction/{id}',    [SaleV2Controller::class, 'updatePaidAmount'])->name('update-paid-amount-with-transaction');

    Route::get('office-copy/{id}',            [SaleV2Controller::class, 'officeCopy'])->name('office.copy');
    Route::get('purchase-approve/{id}',       [PurchaseController::class, 'purchaseApproveShow'])->name('approve.show');
    Route::put('purchase-approve/{purchase}', [PurchaseController::class, 'purchaseApprove'])->name('approvePurchase');

    Route::get('payment-collection',       [SaleController::class, 'paymentCollection'])->name('sales.payment-collection');
    Route::post('store-payment-collection',[SaleController::class, 'storePaymentCollection'])->name('sales.store-payment-collection');


    // ---------------------------------------------------------------//
    //               CUSTOM ROUTE FOR RESTOURANT NIGHT AUDIT          //
    // ---------------------------------------------------------------//

    Route::get('night-audit',              [RestaurantNightAuditController::class, 'index'])->name('night-audits.index');
    Route::get('night-audit-show/{id}',    [RestaurantNightAuditController::class, 'show'])->name('night-audits.show');





    /**
     *
     * ---------------------------------------------------------------
     * REPORT ROUTE
     * ---------------------------------------------------------------
     *
     */
    Route::group(['prefix' => 'reports', 'as' => 'report.'], function () {


        Route::get('cash-flows',        [CashFlowController::class, 'index'])->name('cash-flows');
        Route::get('sales',             [SaleReportController::class, 'index'])->name('sales');
        Route::get('today',             [ReportController::class, 'todayReport'])->name('today');
        Route::get('inventory',         [ReportController::class, 'inventory'])->name('inventory');
        Route::get('inventory-ledger',  [ReportController::class, 'inventoryLedger'])->name('inventory-ledger');

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
});


Route::group(['prefix'  => 'kitchen', 'as' => 'kit.'], function () {

    Route::resources([
        'kitchen'    => KitchenController::class,
    ]);
    Route::post('update-status/{id}',                  [KitchenOrderController::class, 'status'])->name('update-status');
    Route::get('details',                              [KitchenOrderController::class, 'details'])->name('orders.details');
});



