<?php

use \Illuminate\Support\Facades\Route;
use Module\Account\Controllers\SaleController;
use Module\Account\Controllers\UnitController;
use Module\Account\Controllers\DamageController;
use Module\Account\Controllers\AccountController;
use Module\Account\Controllers\PaymentController;
use Module\Account\Controllers\ProductController;
use Module\Account\Controllers\CategoryController;
use Module\Account\Controllers\CustomerController;
use Module\Account\Controllers\PurchaseController;
use Module\Account\Controllers\SupplierController;
use Module\Account\Controllers\Ajax\AjaxController;
use Module\Account\Controllers\CollectionController;
use Module\Account\Controllers\SaleReturnController;
use Module\Account\Controllers\AccountAjaxController;
use Module\Account\Controllers\AccountGroupController;
use Module\Account\Controllers\AccountSetupController;
use Module\Account\Controllers\FundTransferController;
use Module\Account\Controllers\AccountReportController;
use Module\Account\Controllers\ContraVoucherController;
use Module\Account\Controllers\AccountControlController;
use Module\Account\Controllers\JournalVoucherController;
use Module\Account\Controllers\PaymentVoucherController;
use Module\Account\Controllers\PurchaseReturnController;
use Module\Account\Controllers\ReceiveVoucherController;
use Module\Account\Controllers\InventoryReportController;
use Module\Account\Controllers\AccountSubsidiaryController;
use Module\Account\Controllers\AccountOpeningBalanceController;

Route::group(['prefix' => 'setup'], function () {

    Route::get('account-setups',                        [AccountSetupController::class, 'index'])->name('account-setups.index');
    Route::get('account-groups',                        [AccountGroupController::class, 'index'])->name('account-groups.index');


    Route::resource('accounts',                         AccountController::class);
    Route::resource('account-controls',                 AccountControlController::class);
    Route::resource('account-subsidiaries',             AccountSubsidiaryController::class);
    Route::resource('account-opening-balances',         AccountOpeningBalanceController::class)->only(['create', 'store']);


    // AJAX
    Route::get('account-control-data',                  [AccountAjaxController::class, 'getAccountControlsByAccountGroup'])->name('ajax.account-controls');
    Route::get('account-subsidiary-data',               [AccountAjaxController::class, 'getAccountSubsidiariesByAccountControl'])->name('ajax.account-subsidiaries');
    Route::get('account-data',                          [AccountAjaxController::class, 'getAccountsByAccountControlAndAccountSubsidiary'])->name('ajax.accounts-by-control-and-subsidiary');
    Route::get('account-subsidiary-and-account-data',   [AccountAjaxController::class, 'getAccountSubsidiariesAndAccountsByAccountControl'])->name('ajax.subsidiaries-and-accounts-by-control');
});





Route::resource('fund-transfers',                       FundTransferController::class);

Route::post('fund-transfers/{fundTransfer}/approve',    [FundTransferController::class, 'approveFundTransfer'])->name('fund-transfers.approve.update');



Route::group(['prefix' => 'reports'], function () {


    Route::get('account-ledger',                        [AccountReportController::class, 'accountLedgerReport'])->name('report.account-ledger');




    Route::get('chart-of-account',                      [AccountReportController::class, 'chartOfAccountReport'])->name('report.chart-of-account');
    Route::get('ledger-journal',                        [AccountReportController::class, 'JournalReport'])->name('report.ledger-journal');
    Route::get('transaction-ledger',                    [AccountReportController::class, 'transactionLedgerReport'])->name('report.transaction-ledger');
    Route::get('subsidiary-wise-ledger',                [AccountReportController::class, 'subsidiaryWiseLedgerReport'])->name('report.subsidiary-wise-ledger');
    Route::get('nominal-account-ledger',                [AccountReportController::class, 'nominalAccountLedgerReport'])->name('report.nominal-account-ledger');


    Route::get('customer-ledger',                       [AccountReportController::class, 'customerLedgerReport'])->name('report.customer-ledger');
    Route::get('supplier-ledger',                       [AccountReportController::class, 'supplierLedgerReport'])->name('report.supplier-ledger');

    Route::get('supplier-report',                       [AccountReportController::class, 'supplierReport'])->name('report.supplier');


    Route::get('account-receivable',                    [AccountReportController::class, 'accountReceivableReport'])->name('report.account-receivable');
    Route::get('account-payable',                       [AccountReportController::class, 'accountPayableReport'])->name('report.account-payable');


    Route::get('revenue-analysis',                      [AccountReportController::class, 'revenueAnalysisReport'])->name('report.revenue-analysis');
    Route::get('expense-analysis',                      [AccountReportController::class, 'expenseAnalysisReport'])->name('report.expense-analysis');
    Route::get('ratio-analysis',                        [AccountReportController::class, 'ratioAnalysisReport'])->name('report.ratio-analysis');
    Route::get('received-payment-statement',            [AccountReportController::class, 'receivedPaymentStatementReport'])->name('report.received-payment-statement');



    Route::get('voucher-report',                        [AccountReportController::class, 'getVoucherReport'])->name('report.voucher-report');







    Route::group(['prefix' => 'financial-statements'], function () {

        Route::get('trial-balance',                     [AccountReportController::class, 'trialBalanceReport' ])->name('report.trial-balance');
        Route::get('income-statement',                  [AccountReportController::class, 'incomeStatement'    ])->name('report.income-statement');
        Route::get('income-expense-statement',          [AccountReportController::class, 'incomeStatement'    ])->name('report.income-expense-statement');

        Route::get('equity-statement',                  [AccountReportController::class, 'equityStatement'    ])->name('report.equity-statement');
        Route::get('balance-sheet',                     [AccountReportController::class, 'balanceSheetReport' ])->name('report.balance-sheet');
        Route::get('cash-flow',                         [AccountReportController::class, 'cashFlowReport'     ])->name('report.cash.flow');
    });



    Route::group(['prefix' => 'inventory'], function () {


        Route::get('item-ledger',                       [InventoryReportController::class, 'getItemLedger'])->name('account.item-ledger');
        Route::get('stock-in-hand',                     [InventoryReportController::class, 'getStockInHand'])->name('account.stock-in-hand');

    });
});






// Product
Route::group(['prefix' => 'product'], function () {

    Route::resource('units',                            UnitController::class);
    Route::resource('categories',                       CategoryController::class);
    Route::resource('products',                         ProductController::class);
    Route::resource('damages',                          DamageController::class);

});



// Party
Route::group(['prefix' => 'party'], function () {

    Route::resource('acc-customers',                    CustomerController::class);
    Route::resource('acc-suppliers',                    SupplierController::class);

});



// Purchase
Route::group(['prefix' => 'purchase'], function () {

    Route::resource('acc-payments',                 PaymentController::class);
    Route::resource('acc-purchases',                PurchaseController::class);
    Route::resource('acc-purchase-returns',         PurchaseReturnController::class);

    Route::get('acc-returnable-purchase-invoices',  [PurchaseReturnController::class, 'getReturnablePurchaseInvoices'])->name('acc-returnable-purchase-invoices');
    Route::get('acc-returnable-purchase-items',     [PurchaseReturnController::class, 'getReturnablePurchaseItems'])->name('acc-returnable-purchase-items');

});




// Sale
Route::group(['prefix' => 'sale'], function () {

    Route::resource('acc_collections',          CollectionController::class);
    Route::resource('acc-sales',                SaleController::class);
    Route::resource('acc-sale-returns',         SaleReturnController::class);

    Route::get('acc-returnable-sale-invoices',  [SaleReturnController::class, 'getReturnableSaleInvoices'])->name('acc-returnable-sale-invoices');
    Route::get('acc-returnable-sale-items',     [SaleReturnController::class, 'getReturnableSaleItems'])->name('acc-returnable-sale-items');
});






// Voucher
Route::group(['prefix' => 'voucher', 'as' => 'voucher-'], function () {


    // Receive
    Route::post('receives/{receive}/approve',   [ReceiveVoucherController::class, 'approveReceiveVoucher'])->name('receives.approve');
    Route::resource('receives',                 ReceiveVoucherController::class);


    // Payment
    Route::post('payments/{payment}/approve',   [PaymentVoucherController::class, 'approvePaymentVoucher'])->name('payments.approve');
    Route::resource('payments',                 PaymentVoucherController::class);


    // Contra
    Route::post('contras/{contra}/approve',     [ContraVoucherController::class, 'approveContraVoucher'])->name('contras.approve');
    Route::resource('contras',                  ContraVoucherController::class);


    // Journal
    Route::post('journals/{journal}/approve',   [JournalVoucherController::class, 'approveJournalVoucher'])->name('journals.approve');
    Route::resource('journals',                 JournalVoucherController::class);
});


Route::group(['prefix' => 'ajax', 'as' => 'ajax-'], function () {

    Route::get('company-wise-product', [AjaxController::class, 'getCompanyProduct'])->name('company-product-wise');
});
