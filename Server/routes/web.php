<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DRJourneyFrame\DRAdminController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DRJourneyFrame\DRHomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'index'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('loginuser');
Route::get('logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('home', function () {
        return view('home');
    })->name('home');
    Route::prefix('doctor')->name('dr.journey.')->group(function () {
        // Route::get('dashboard', function () {
        //     dd('test');
        // })->name('dashboard');
        Route::get('dashboard', [DRHomeController::class, 'index'])->name('dashboard');
        Route::get('data', [DRHomeController::class, 'meetingData'])->name('dashboard.data');
        Route::get('create', [DRHomeController::class, 'create'])->name('create');
        Route::post('store', [DRHomeController::class, 'store'])->name('store');
        Route::get('edit/{id}', [DRHomeController::class, 'edit'])->name('edit');
        Route::post('update/{id}', [DRHomeController::class, 'update'])->name('update');
        Route::post('delete/{id}', [DRHomeController::class, 'delete'])->name('delete');

        Route::get('details/{id}', [DRHomeController::class, 'details'])->name('details');
        Route::post('photo-reupload', [DRHomeController::class, 'photoReupload'])->name('photo.reupload');
        Route::post('generate-poster', [DRHomeController::class, 'downloadDP'])->name('generate.poster');
    });
});

Route::prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminController::class, 'login'])->name('admin.login.user');

    Route::middleware(['auth:admin'])->group(function () {
        Route::get('logout', [AdminController::class, 'logout'])->name('admin.logout');
        Route::get('dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

        Route::get('users', [AdminController::class, 'userlist'])->name('admin.userlist');
        Route::get('users-data', [AdminController::class, 'userdata'])->name('admin.users.data');
        Route::get('users-add', [AdminController::class, 'useradd'])->name('admin.user.add');
        Route::post('users-store', [AdminController::class, 'userstore'])->name('admin.users.store');
        Route::get('users-edit/{id}', [AdminController::class, 'useredit'])->name('admin.user.edit');
        Route::post('users-update/{id}', [AdminController::class, 'userupdate'])->name('admin.users.update');
        Route::delete('users/delete/{id}', [AdminController::class, 'userdelete'])->name('admin.user.delete');
        Route::get('users-deleteall', [AdminController::class, 'deleteall'])->name('admin.user.deleteall');
        Route::get('users-csv', [AdminController::class, 'usercsv'])->name('admin.user.csv');
        Route::post('user/import', [AdminController::class, 'importUsers'])->name('admin.user.import');


        Route::get('requestlist', [AdminController::class, 'requestlist'])->name('admin.requestlist');
        Route::get('request-data', [AdminController::class, 'requestdata'])->name('admin.request.data');
        Route::get('request/edit/{id}', [AdminController::class, 'requestedit'])->name('admin.request.edit');
        Route::post('request/edit/update/{id}', [AdminController::class, 'editupdate'])->name('admin.request.edit.update');
        Route::post('request/delete/{id}', [AdminController::class, 'requestdelete'])->name('admin.request.delete');
        Route::get('request-csv', [AdminController::class, 'requestcsv'])->name('admin.request.csv');

        Route::prefix('doctor')->name('admin.dr.journey.')->group(function () {
            Route::get('requestlist', [DRAdminController::class, 'requestlist'])->name('request.list');
            Route::get('request-data', [DRAdminController::class, 'requestdata'])->name('request.data');
            // Route::get('request/edit/{id}', [DRAdminController::class, 'requestedit'])->name('request.edit');
            // Route::post('request/edit/update/{id}', [DRAdminController::class, 'editupdate'])->name('request.edit.update');
            Route::post('request/delete/{id}', [DRAdminController::class, 'requestdelete'])->name('request.delete');
            Route::get('request-csv', [DRAdminController::class, 'requestcsv'])->name('request.csv');
        });
    });
});
