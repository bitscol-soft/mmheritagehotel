<?php

use Illuminate\Support\Facades\Route;
use Module\CRM\Controllers\CustomerController;
use Module\CRM\Controllers\MailTemplateController;

/*
 |--------------------------------------------------------------------------
 | CRM ROUTES (revival, BUGS #27)
 |--------------------------------------------------------------------------
 | URLs, route names and the seeded permission SLUGS all read `crm.templates.*`
 | for the mail-template master: the permission rows shipped with the schema are
 | `crm.templates.index/create/edit/delete` (ids 170032-170036), so the screen
 | restores that naming instead of inventing a `mail-templates` family.
 |
 | Loaded by RouteServiceProvider only while the CRM module row is active.
 */

Route::group(['prefix' => 'crm', 'as' => 'crm.'], function () {

    Route::resource('customers', CustomerController::class)->except(['show']);
    Route::resource('templates', MailTemplateController::class)->except(['show']);
});
