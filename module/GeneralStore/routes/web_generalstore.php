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

Route::group(['prefix' => 'gs'], function () {

    Route::resource('item-units', 'ItemUnitController')->except(['show']); // round 3: show not implemented (docs/BUGS.md)

    Route::resource('items', 'ItemController');

    // Item Upload Routes
    Route::get('item-export', 'ItemController@export')->name('gs.item.export');
    Route::get('item-upload', 'ItemController@itemUploadShow')->name('gs.item.upload');
    Route::post('item-upload', 'ItemController@itemUpload');


    Route::get('print-gin-list-details/{goodsRequisition}', 'GoodsRequisitionController@printGin')->name('print.gin-details');
    Route::resource('goods-requisitions', 'GoodsRequisitionController')->except(['show']); // round 3: show not implemented (docs/BUGS.md)
    Route::get('gin-list', 'GoodsRequisitionController@GINList')->name('gin.list');
    Route::get('gin-list/{goodsRequisition}', 'GoodsRequisitionController@GINListShow')->name('gin.list.show');
    Route::get('goods-requisition/approve/{goodsRequisition}', 'GoodsRequisitionController@approveGoodsRequisitionShow')->name('approve.goods.requisition.show');
    Route::put('goods-requisition/{goodsRequisition}/approve', 'GoodsRequisitionController@approveGoodsRequisition')->name('approve.goods.requisition');
    Route::get('goods-requisition/{goodsRequisition}/unapprove', 'GoodsRequisitionController@unapproveGoodsRequisition')->name('unapprove.goods.requisition');


    Route::resource('purchases', 'PurchaseController');
    Route::get('purchase-approve/{id}', 'PurchaseController@purchaseApproveShow')->name('gs.approve.purchase.show');
    Route::put('purchase-approve/{purchase}', 'PurchaseController@purchaseApprove')->name('gs.approve.purchase');
    Route::get('purchase/unapprove/{purchase}', 'PurchaseController@purchaseUnapprove')->name('gs.unapprove.purchase');


    Route::get('purchase-receive/list/{purchase}', 'PurchaseReceiveController@purchaseReceiveList')->name('purchase.receive.list');
    Route::get('purchase-receive-detail-print/{purchaseReceive}', 'PurchaseReceiveController@print')->name('print.purchase-receive');
    Route::get('grn-list', 'PurchaseReceiveController@grnList')->name('grn.list');
    Route::get('grn-list/{id}', 'PurchaseReceiveController@grnListShow')->name('grn.list.show');
    Route::get('purchase-receive/create/{purchase}', 'PurchaseReceiveController@purchaseReceiveCreate')->name('purchase.receives.create');
    Route::post('purchase-receive/store', 'PurchaseReceiveController@purchaseReceiveStore')->name('purchase.receives.store');
    Route::delete('purchase-receive/delete/{purchaseReceive}', 'PurchaseReceiveController@purchaseReceiveDelete')->name('purchase.receives.destroy');


    Route::group(['prefix' => 'gs-reports'], function () {
        Route::get('items_stock', 'InventoryReportController@items_stock')->name('items_stock');
        Route::get('company-items', 'InventoryReportController@getCompanyItems')->name('company-items');
        Route::get('item_details', 'InventoryReportController@item_details')->name('item_details');
        Route::get('weakly/movement/issue', 'InventoryReportController@weaklyMovementIssue')->name('weakly.movement.issue');
    });
});





Route::group(['prefix' => 'generalstore'], function () {
    Route::post('/export-gs-as-excel', 'ExportGsExcelController@export')->name('export.gs.as.excel');
    Route::post('/export-gs-as-pdf', 'ExportGsPdfController@exportPdf')->name('export.gs.as.pdf');
    Route::post('/export-item-details-excel', 'ExportGsExcelController@exportItemDetails')->name('export.item.details');




    Route::resource('suppliers', 'SupplierController')->except(['show']); // round 3: show not implemented (docs/BUGS.md)
    Route::resource('supplier-types', 'SupplierTypeController');

    /*
     |-------------------------------------
     | AJAX ROUTE
     |-------------------------------------
     */
    Route::group(['prefix' => 'ajax'], function () {
        Route::get('items/get-item-list-by-type', 'ItemController@getItemListByType');
        Route::get('item/get-item-details', 'ItemController@getItemDetails');
        Route::get('items/get-item-list', 'ItemController@getItemList');
        Route::get('item/get-item-details/approve', 'ItemController@getItemDetailsForApprove');
        Route::get('item/get-item-details/purchase', 'ItemController@getItemDetailsForPurchase');
    });
});

