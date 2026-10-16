<?php

use Illuminate\Support\Facades\Route;
use Module\CRM\Controllers\CustomerController;
use Module\CRM\Controllers\MailTemplateController;

/*
 |--------------------------------------------------------------------------
 | CRM ROUTES (revival, BUGS #27)
 |--------------------------------------------------------------------------
 | Route names are module-prefixed (`crm.customers.*`, `crm.mail-templates.*`)
 | while the seeded permission SLUGS use underscores (`crm.customers.*`,
 | `crm.mail_templates.*`) - same convention as the acc-payments route names
 | vs acc_payments slugs in the Account module.
 |
 | Loaded by RouteServiceProvider only while the CRM module row is active.
 */

Route::group(['prefix' => 'crm', 'as' => 'crm.'], function () {

    Route::resource('customers', CustomerController::class)->except(['show']);
    Route::resource('mail-templates', MailTemplateController::class)->except(['show']);
});
