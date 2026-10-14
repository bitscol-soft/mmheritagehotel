<?php

use Illuminate\Support\Facades\Route;
use Module\Permission\Controllers\ModuleController;
use Module\Permission\Controllers\ParentPermissionController;
use Module\Permission\Controllers\PermissionController;
use Module\Permission\Controllers\SubmoduleController;
use Module\Permission\Controllers\UserPermissionController;
use App\Http\Controllers\ActivityLogController;

    // user permission routes [akash]
    Route::group(['prefix' => 'setting'], function () {


        Route::resource('modules',                      ModuleController::class);
        Route::resource('parent-permissions', ParentPermissionController::class)->except(['show']); // round 3: show not implemented (docs/BUGS.md)
        Route::resource('submodules', SubmoduleController::class)->except(['show']); // round 3: show not implemented (docs/BUGS.md)
        Route::resource('permissions', PermissionController::class)->except(['show']); // round 3: show not implemented (docs/BUGS.md)
        Route::resource('permission-access',            UserPermissionController::class)->except(['index', 'show', 'destroy']);
        // plain /setting/permission-access now opens the permitted-users grid instead of 500
        Route::get('permission-access', function () {
            return redirect()->route('permitted.users');
        })->name('permission-access.index');


        Route::get('active-deactive-module/{module}',   [ModuleController::class, 'activeDeactive'])->name('active.deactive.module');


        Route::get('select/employee/list',              [UserPermissionController::class, 'employee_list'])->name('employee_list');
        Route::get('permission-access/create/{id}',     [UserPermissionController::class, 'index'])->name('load.existing.users.permission');
        Route::get('permitted/employee/list',           [UserPermissionController::class, 'permittedEmployeeList'])->name('permitted.employee.list');
        Route::get('permission-access-employee',        [UserPermissionController::class, 'employeePermission'])->name('permission-access.employee');
        Route::post('permission-access-employee',       [UserPermissionController::class, 'employeePermissionStore'])->name('permission-access.employee.store');


        Route::get('users/create',                      [UserPermissionController::class, 'createUser'])->name('settings.create-user');
        Route::post('users/create',                     [UserPermissionController::class, 'storeUser'])->name('settings.store-user');
        Route::get('view-permitted-users',              [UserPermissionController::class, 'view_permitted_users'])->name('permitted.users');


        Route::get('user-activity-logs', [ActivityLogController::class, 'index'])->name('activity-log.index');
    });




    Route::get('user/{id}/status/{status}', [PermissionController::class, 'userChangeStatus'])->name('user.active.deactive');
    Route::delete('setting/permitted-users/delete/{user}', [PermissionController::class, 'permittedUserDelete'])->name('permitted.user.delete');
    Route::get('/permitted-users/{id}/edit', [UserPermissionController::class, 'edit'])->name('edit.permitted.users');
    Route::put('/update-permitted/{id}/users', [UserPermissionController::class, 'update'])->name('update.permission.access');
