<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SupplierController;
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


    Route::get('/users-data', [UserController::class, 'getUsers'])
    ->name('users.data');
    Route::resource('users', UserController::class);

    Route::post('/user-step', [UserController::class, 'handleStep'])
        ->name('users.step');

    Route::prefix('suppliers')->name('suppliers.')->group(function () {

        Route::get('/',             [SupplierController::class, 'index'])->name('index');
        Route::get('/data',         [SupplierController::class, 'getData'])->name('data');
        Route::get('/create',       [SupplierController::class, 'create'])->name('create');
        Route::post('/',            [SupplierController::class, 'store'])->name('store');

        Route::get('/{id}',         [SupplierController::class, 'show'])->name('show');
        Route::get('/{id}/edit',    [SupplierController::class, 'edit'])->name('edit');
        Route::put('/{id}',         [SupplierController::class, 'update'])->name('update');
        Route::delete('/{id}',      [SupplierController::class, 'destroy'])->name('destroy');

        // Inline rank update from listing (AJAX)
        Route::patch('/{id}/rank',  [SupplierController::class, 'updateRank'])->name('updateRank');

        // Excel import
        Route::post('/import',      [SupplierController::class, 'import'])->name('import');

        // Download import template
        Route::get('/import/template', function () {
            $headers = [
                'Content-Type'        => 'text/csv',
                'Content-Disposition' => 'attachment; filename="suppliers_import_template.csv"',
            ];
            $columns = implode(',', [
                'category',
                'business_name',
                'email',
                'phone',
                'abn',
                'address',
                'account_email',
                'bank_name',
                'bsb_no',
                'account_number',
                'account_name',
                'payment_terms',
                'notes',
            ]);
            return response($columns . "\n", 200, $headers);
        })->name('import.template');
    });


    // Route::get('/users/{id}/step2', [UserController::class, 'step2'])->name('users.step2');
    // Route::get('/users/{id}/step3', [UserController::class, 'step3'])->name('users.step3');
    // Route::get('/users/{id}/step4', [UserController::class, 'step4'])->name('users.step4');

    // Route::post('/users/{id}/complete', [UserController::class, 'complete'])->name('users.complete');

    // Route::post('/upload-cert', [UserController::class, 'uploadCert']);
    // Route::post('/upload-contract', [UserController::class, 'uploadContract']);

});

require __DIR__ . '/auth.php';
