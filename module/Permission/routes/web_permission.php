<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActivityLogController;

    // user permission routes [akash]
    Route::group(['prefix' => 'setting'], function () {


        Route::resource('modules',                      'ModuleController');
        Route::resource('parent-permissions',           'ParentPermissionController');
        Route::resource('submodules',                   'SubmoduleController');
        Route::resource('permissions',                  'PermissionController');
        Route::resource('permission-access',            'UserPermissionController')->except(['index', 'show', 'destroy']);
        // plain /setting/permission-access now opens the permitted-users grid instead of 500
        Route::get('permission-access', function () {
            return redirect()->route('permitted.users');
        })->name('permission-access.index');


        Route::get('active-deactive-module/{module}',   'ModuleController@activeDeactive')->name('active.deactive.module');


        Route::get('select/employee/list',              'UserPermissionController@employee_list')->name('employee_list');
        Route::get('permission-access/create/{id}',     'UserPermissionController@index')->name('load.existing.users.permission');
        Route::get('permitted/employee/list',           'UserPermissionController@permittedEmployeeList')->name('permitted.employee.list');
        Route::get('permission-access-employee',        'UserPermissionController@employeePermission')->name('permission-access.employee');
        Route::post('permission-access-employee',       'UserPermissionController@employeePermissionStore')->name('permission-access.employee.store');


        Route::get('users/create',                      'UserPermissionController@createUser')->name('settings.create-user');
        Route::post('users/create',                     'UserPermissionController@storeUser')->name('settings.store-user');
        Route::get('view-permitted-users',              'UserPermissionController@view_permitted_users')->name('permitted.users');


        Route::get('user-activity-logs', [ActivityLogController::class, 'index'])->name('activity-log.index');
    });




    Route::get('user/{id}/status/{status}', 'PermissionController@userChangeStatus')->name('user.active.deactive');
    Route::delete('setting/permitted-users/delete/{user}', 'PermissionController@permittedUserDelete')->name('permitted.user.delete');
    Route::get('/permitted-users/{id}/edit', 'UserPermissionController@edit')->name('edit.permitted.users');
    Route::put('/update-permitted/{id}/users', 'UserPermissionController@update')->name('update.permission.access');
