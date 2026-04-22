<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {

    // TEMP: remove admin middleware
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])
        ->name('admin.dashboard');

    Route::resource('users', UserController::class);

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::get('/projects', function () {
            return "Projects Module";
        })->name('projects.index');

    Route::get('/cashflow', function () {
            return "Cashflow Module";
        })->name('cashflow.index');

    Route::get('/reports', function () {
            return "Reports Module";
        })->name('reports.index');

    Route::get('/change-password', [App\Http\Controllers\ChangePasswordController::class, 'index'])
    ->name('change.password');

    Route::post('/change-password', [App\Http\Controllers\ChangePasswordController::class, 'update'])
    ->name('change.password.update');


    Route::get('/users-data', [App\Http\Controllers\Admin\UserController::class, 'getUsers'])
    ->name('users.data');
    Route::resource('users', UserController::class);

    Route::post('/user-step', [UserController::class, 'handleStep'])
    ->name('users.step');


    // Route::get('/users/{id}/step2', [UserController::class, 'step2'])->name('users.step2');
    // Route::get('/users/{id}/step3', [UserController::class, 'step3'])->name('users.step3');
    // Route::get('/users/{id}/step4', [UserController::class, 'step4'])->name('users.step4');

    // Route::post('/users/{id}/complete', [UserController::class, 'complete'])->name('users.complete');

    // Route::post('/upload-cert', [UserController::class, 'uploadCert']);
    // Route::post('/upload-contract', [UserController::class, 'uploadContract']);

});

require __DIR__.'/auth.php';
