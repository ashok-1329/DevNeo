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
use App\Http\Controllers\Admin\DocketController;
use App\Http\Controllers\Admin\SubcontractorController;
use App\Http\Controllers\Admin\LabourController;
use App\Http\Controllers\Admin\MaterialController;
use App\Http\Controllers\Admin\Configuration\ProjectConfigurationController;
use App\Http\Controllers\Admin\Configuration\ContractTypeController;
use App\Http\Controllers\Admin\Configuration\PaymentTermController;
use App\Http\Controllers\Admin\Configuration\ProjectCodeCategoryController;
use App\Http\Controllers\Admin\Configuration\PlantTypeController;
use App\Http\Controllers\Admin\Configuration\PlantCapacityController;
use App\Http\Controllers\Admin\Configuration\ProjectRegionController;



Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {

    // TEMP: remove admin middleware
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])
        ->name('admin.dashboard');

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


    // Route::get('/users-data', [UserController::class, 'getUsers'])
    //     ->name('users.data');
    // Route::resource('users', UserController::class);

    // Route::post('/user-step', [UserController::class, 'handleStep'])
    //     ->name('users.step');


    Route::prefix('users')->name('users.')->group(function () {

        Route::get('{id}/full', [UserController::class, 'getUserFullData']);

        // ✅ CUSTOM ROUTES FIRST (VERY IMPORTANT)
        Route::get('data', [UserController::class, 'getUsers'])->name('data');

        Route::post('step', [UserController::class, 'handleStep'])->name('step');
        Route::post('upload', [UserController::class, 'uploadFile'])->name('upload');
        // CERT ROUTES
        Route::get('cert/{certId}/get', [UserController::class, 'getCert'])->name('cert.get');
        Route::post('cert/{certId}/update', [UserController::class, 'updateCert'])->name('cert.update');
        Route::delete('cert/{certId}', [UserController::class, 'deleteCert'])->name('cert.delete');

        // ✅ RESOURCE ROUTES (CLEAN)
        Route::resource('/', UserController::class)->parameters([
            '' => 'id'
        ])->except(['store', 'update']);
    });

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

    Route::get('projects/data', [ProjectController::class, 'getData'])->name('projects.data');
    Route::post('projects/step',         [ProjectController::class, 'handleStep'])->name('projects.step');
    Route::post('projects/{id}/update-status', [ProjectController::class, 'updateStatus'])->name('projects.updateStatus');
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

    Route::get('subcontractors/data', [SubcontractorController::class, 'getData'])
        ->name('subcontractors.data');
    Route::resource('subcontractors', SubcontractorController::class);

    Route::get('materials/data', [MaterialController::class, 'getData'])
        ->name('materials.data');
    Route::get('materials/products-by-category/{categoryId}', [MaterialController::class, 'productsByCategory'])
        ->name('materials.productsByCategory');
    Route::post('materials/{id}/status', [MaterialController::class, 'statusUpdate'])
        ->name('materials.statusUpdate');
    Route::resource('materials', MaterialController::class);


    Route::prefix('dockets')->group(function () {
        Route::get('/', [DocketController::class, 'index'])->name('dockets.index');
        Route::get('/data', [DocketController::class, 'getData'])->name('dockets.data');
        Route::get('/create', [DocketController::class, 'create'])->name('dockets.create');
        Route::post('/', [DocketController::class, 'store'])->name('dockets.store');
        Route::get('/{id}', [DocketController::class, 'show'])->name('dockets.show');
        Route::get('/{id}/edit', [DocketController::class, 'edit'])->name('dockets.edit');
        Route::put('/{id}', [DocketController::class, 'update'])->name('dockets.update');
        Route::delete('/{id}', [DocketController::class, 'destroy'])->name('dockets.destroy');
    });

    Route::prefix('configuration')->name('admin.project.configuration.')->group(function () {

        // Main index page
        Route::get('/', [ProjectConfigurationController::class, 'index'])->name('index');

        // Contract Types  (Setting model, type = 1)
        Route::resource('contract-types', ContractTypeController::class)
            ->only(['index', 'store', 'update', 'destroy'])
            ->parameters(['contract-types' => 'contractType']);

        // Payment Terms
        Route::resource('payment-terms', PaymentTermController::class)
            ->only(['index', 'store', 'update', 'destroy'])
            ->parameters(['payment-terms' => 'paymentTerm']);

        // Project Code Categories
        Route::resource('code-categories', ProjectCodeCategoryController::class)
            ->only(['index', 'store', 'update', 'destroy'])
            ->parameters(['code-categories' => 'codeCategory']);

        // Plant Types
        Route::resource('plant-types', PlantTypeController::class)
            ->only(['index', 'store', 'update', 'destroy'])
            ->parameters(['plant-types' => 'plantType']);

        // Plant Capacities
        Route::resource('plant-capacities', PlantCapacityController::class)
            ->only(['index', 'store', 'update', 'destroy'])
            ->parameters(['plant-capacities' => 'plantCapacity']);

        // Project Regions
        Route::resource('project-regions', ProjectRegionController::class)
            ->only(['index', 'store', 'update', 'destroy'])
            ->parameters(['project-regions' => 'projectRegion']);
    });

    // Route::get('/users/{id}/step2', [UserController::class, 'step2'])->name('users.step2');
    // Route::get('/users/{id}/step3', [UserController::class, 'step3'])->name('users.step3');
    // Route::get('/users/{id}/step4', [UserController::class, 'step4'])->name('users.step4');

    // Route::post('/users/{id}/complete', [UserController::class, 'complete'])->name('users.complete');

    // Route::post('/upload-cert', [UserController::class, 'uploadCert']);
    // Route::post('/upload-contract', [UserController::class, 'uploadContract']);

});

require __DIR__ . '/auth.php';
