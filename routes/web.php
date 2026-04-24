<?php

use App\Http\Controllers\Admin\ClientController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PaymentRuleController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DiaryProductController;
use App\Http\Controllers\Admin\LabourController;

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

    Route::get('payment-rules/data', [PaymentRuleController::class, 'getData'])
        ->name('payment-rules.data');

    Route::resource('payment-rules', PaymentRuleController::class);

    // ── Projects (basic — expand as needed) ───────────────────────────────────────
    Route::get('projects/data', [ProjectController::class, 'getData'])
        ->name('projects.data');

    Route::resource('projects', ProjectController::class)->only(['index', 'show']);

    Route::get('clients/data', [ClientController::class, 'getData'])
        ->name('clients.data');

    Route::resource('clients', ClientController::class);

    Route::get('diary-products/data', [DiaryProductController::class, 'getData'])
        ->name('diary-products.data');

    Route::patch('diary-products/{id}/toggle-status', [DiaryProductController::class, 'toggleStatus'])
        ->name('diary-products.toggle-status');

    Route::resource('diary-products', DiaryProductController::class);

    Route::get('project/labour/data',         [LabourController::class, 'getData'])
        ->name('admin.project.labour.data');

    Route::get('project/labour/autocomplete', [LabourController::class, 'autocomplete'])
        ->name('admin.project.labour.autocomplete');

    Route::get('project/labour/rate',         [LabourController::class, 'getRate'])
        ->name('admin.project.labour.rate');

    Route::resource('project/labour', LabourController::class)
        ->names('admin.project.labour');

    // Route::get('/users/{id}/step2', [UserController::class, 'step2'])->name('users.step2');
    // Route::get('/users/{id}/step3', [UserController::class, 'step3'])->name('users.step3');
    // Route::get('/users/{id}/step4', [UserController::class, 'step4'])->name('users.step4');

    // Route::post('/users/{id}/complete', [UserController::class, 'complete'])->name('users.complete');

    // Route::post('/upload-cert', [UserController::class, 'uploadCert']);
    // Route::post('/upload-contract', [UserController::class, 'uploadContract']);

});

require __DIR__ . '/auth.php';
