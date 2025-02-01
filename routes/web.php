<?php

use App\Http\Controllers\Accounting\AdditionalDocumentController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Accounting\InvoiceController;
use App\Http\Controllers\Master\SupplierController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\DB;

Route::middleware('guest')->group(function () {
    Route::controller(LoginController::class)->group(function () {
        Route::get('/login', 'index')->name('login');
        Route::post('/login', 'authenticate')->name('authenticate');
    });

    Route::controller(RegisterController::class)->group(function () {
        Route::get('/register', 'index')->name('register');
        Route::post('/register', 'store')->name('register.store');
    });
});

// middleware('auth') means that the user must be authenticated to access the route
Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index']);
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/test', [TestController::class, 'index'])->name('test');

    require __DIR__ . '/admin.php';
    require __DIR__ . '/accounting.php';
    require __DIR__ . '/finance.php';
    require __DIR__ . '/logistic.php';
    require __DIR__ . '/master.php';

    // Add this route for departments
    Route::get(
        '/api/projects/{projectCode}/departments',
        [App\Http\Controllers\Logistic\LpdController::class, 'getDepartments']
    )
        ->name('api.projects.departments')
        ->middleware('web');
});

Route::get('/check-invoice-number', [InvoiceController::class, 'checkInvoiceNumber'])->name('check.invoice.number');
Route::get('/get-payment-project', [SupplierController::class, 'getPaymentProject'])->name('get.payment.project');
Route::get('/get-project-location', [InvoiceController::class, 'getProjectLocation'])->name('get.project.location');
Route::get('/check-addoc-combination', [AdditionalDocumentController::class, 'checkDocumentCombination'])->name('check.addoc.combination');
Route::get('/search-invoices-by-po', [AdditionalDocumentController::class, 'searchInvoicesByPo'])->name('search.invoices.by.po');
Route::get('/search-additional-documents-by-po', [AdditionalDocumentController::class, 'searchAdditionalDocumentsByPo'])->name('search_addocs_by_po');
Route::get('/additional-documents/search', [AdditionalDocumentController::class, 'search'])
    ->name('additional-documents.search');
Route::get('/accounting/invoices/search', [InvoiceController::class, 'search'])
    ->name('accounting.invoices.search');

Route::post(
    'accounting/additional-documents/{document}/update-receive-date',
    [App\Http\Controllers\Accounting\AdditionalDocumentController::class, 'updateReceiveDate']
)
    ->name('accounting.additional-documents.update-receive-date');

// Add these test routes
Route::get('/test/projects', function () {
    $projects = \App\Models\Project::all(['code', 'name'])->toArray();
    dd('Projects:', $projects);
});

Route::get('/test/departments/all', function () {
    $departments = \App\Models\Department::all(['id', 'project', 'department_name', 'akronim'])->toArray();
    dd('All Departments:', $departments);
});

Route::get('/test/departments/{projectCode}', function ($projectCode) {
    $project = \App\Models\Project::where('code', $projectCode)->first();
    $departments = \App\Models\Department::where('project', $projectCode)
        ->orderBy('department_name')
        ->get(['id', 'project', 'department_name', 'akronim']);

    dd([
        'Project' => $project ? $project->toArray() : null,
        'Departments' => $departments->toArray(),
        'SQL' => \DB::getQueryLog()
    ]);
});

// Add this test route
Route::get('/test/db/departments/{projectCode}', function ($projectCode) {
    try {
        $query = DB::table('departments')
            ->select('id', 'project', 'department_name', 'akronim')
            ->where('project', $projectCode);

        dd([
            'SQL' => $query->toSql(),
            'Bindings' => $query->getBindings(),
            'Results' => $query->get()->toArray()
        ]);
    } catch (\Exception $e) {
        dd([
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }
});
