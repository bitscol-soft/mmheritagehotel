<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;
use Module\GeneralStore\Controllers\ExportGsExcelController;
use Module\GeneralStore\Controllers\ExportGsPdfController;
use Module\GeneralStore\Controllers\GoodsRequisitionController;
use Module\GeneralStore\Controllers\InventoryReportController;
use Module\GeneralStore\Controllers\ItemController;
use Module\GeneralStore\Controllers\ItemUnitController;
use Module\GeneralStore\Controllers\PurchaseController;
use Module\GeneralStore\Controllers\PurchaseReceiveController;
use Module\GeneralStore\Controllers\SupplierController;
use Module\GeneralStore\Controllers\SupplierTypeController;

Route::group(['prefix' => 'gs'], function () {

    Route::resource('item-units', ItemUnitController::class)->except(['show']); // round 3: show not implemented (docs/BUGS.md)

    Route::resource('items', ItemController::class);

    // Item Upload Routes
    Route::get('item-export', [ItemController::class, 'export'])->name('gs.item.export');
    Route::get('item-upload', [ItemController::class, 'itemUploadShow'])->name('gs.item.upload');
    Route::post('item-upload', [ItemController::class, 'itemUpload']);


    Route::get('print-gin-list-details/{goodsRequisition}', [GoodsRequisitionController::class, 'printGin'])->name('print.gin-details');
    Route::resource('goods-requisitions', GoodsRequisitionController::class)->except(['show']); // round 3: show not implemented (docs/BUGS.md)
    Route::get('gin-list', [GoodsRequisitionController::class, 'GINList'])->name('gin.list');
    Route::get('gin-list/{goodsRequisition}', [GoodsRequisitionController::class, 'GINListShow'])->name('gin.list.show');
    Route::get('goods-requisition/approve/{goodsRequisition}', [GoodsRequisitionController::class, 'approveGoodsRequisitionShow'])->name('approve.goods.requisition.show');
    Route::put('goods-requisition/{goodsRequisition}/approve', [GoodsRequisitionController::class, 'approveGoodsRequisition'])->name('approve.goods.requisition');
    Route::get('goods-requisition/{goodsRequisition}/unapprove', [GoodsRequisitionController::class, 'unapproveGoodsRequisition'])->name('unapprove.goods.requisition');


    Route::resource('purchases', PurchaseController::class);
    Route::get('purchase-approve/{id}', [PurchaseController::class, 'purchaseApproveShow'])->name('gs.approve.purchase.show');
    Route::put('purchase-approve/{purchase}', [PurchaseController::class, 'purchaseApprove'])->name('gs.approve.purchase');
    Route::get('purchase/unapprove/{purchase}', [PurchaseController::class, 'purchaseUnapprove'])->name('gs.unapprove.purchase');


    Route::get('purchase-receive/list/{purchase}', [PurchaseReceiveController::class, 'purchaseReceiveList'])->name('purchase.receive.list');
    Route::get('purchase-receive-detail-print/{purchaseReceive}', [PurchaseReceiveController::class, 'print'])->name('print.purchase-receive');
    Route::get('grn-list', [PurchaseReceiveController::class, 'grnList'])->name('grn.list');
    Route::get('grn-list/{id}', [PurchaseReceiveController::class, 'grnListShow'])->name('grn.list.show');
    Route::get('purchase-receive/create/{purchase}', [PurchaseReceiveController::class, 'purchaseReceiveCreate'])->name('purchase.receives.create');
    Route::post('purchase-receive/store', [PurchaseReceiveController::class, 'purchaseReceiveStore'])->name('purchase.receives.store');
    Route::delete('purchase-receive/delete/{purchaseReceive}', [PurchaseReceiveController::class, 'purchaseReceiveDelete'])->name('purchase.receives.destroy');


    Route::group(['prefix' => 'gs-reports'], function () {
        Route::get('items_stock', [InventoryReportController::class, 'items_stock'])->name('items_stock');
        Route::get('company-items', [InventoryReportController::class, 'getCompanyItems'])->name('company-items');
        Route::get('item_details', [InventoryReportController::class, 'item_details'])->name('item_details');
        Route::get('weakly/movement/issue', [InventoryReportController::class, 'weaklyMovementIssue'])->name('weakly.movement.issue');
    });
});





Route::group(['prefix' => 'generalstore'], function () {
    Route::post('/export-gs-as-excel', [ExportGsExcelController::class, 'export'])->name('export.gs.as.excel');
    Route::post('/export-gs-as-pdf', [ExportGsPdfController::class, 'exportPdf'])->name('export.gs.as.pdf');
    Route::post('/export-item-details-excel', [ExportGsExcelController::class, 'exportItemDetails'])->name('export.item.details');




    Route::resource('suppliers', SupplierController::class)->except(['show']); // round 3: show not implemented (docs/BUGS.md)
    Route::resource('supplier-types', SupplierTypeController::class);

    /*
     |-------------------------------------
     | AJAX ROUTE
     |-------------------------------------
     */
    Route::group(['prefix' => 'ajax'], function () {
        Route::get('items/get-item-list-by-type', [ItemController::class, 'getItemListByType']);
        Route::get('item/get-item-details', [ItemController::class, 'getItemDetails']);
        Route::get('items/get-item-list', [ItemController::class, 'getItemList']);
        Route::get('item/get-item-details/approve', [ItemController::class, 'getItemDetailsForApprove']);
        Route::get('item/get-item-details/purchase', [ItemController::class, 'getItemDetailsForPurchase']);
    });
});

