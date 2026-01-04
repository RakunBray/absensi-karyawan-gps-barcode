<?php

use App\Http\Controllers\LandingController;
use App\Http\Controllers\Admin\BarcodeController;
use App\Http\Controllers\Admin\MasterDataController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\ImportExportController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserAttendanceController;
use App\Helpers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;

/*
|--------------------------------------------------------------------------
| 1. PUBLIC AREA
|--------------------------------------------------------------------------
*/
Route::get('/', [LandingController::class, 'index'])->name('landing');

/*
|--------------------------------------------------------------------------
| 2. AUTHENTICATED AREA
|--------------------------------------------------------------------------
*/
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
])->group(function () {

    /*
    | Redirect setelah login
    */
    Route::get('/dashboard', function () {
        $user = Auth::user();

        if ($user && $user->isAdmin) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('home');
    })->name('dashboard');

    Route::get('/approval-pending', function () {
        return view('auth.approval-pending');
    })->name('approval.pending');

    /*
    |--------------------------------------------------------------------------
    | USER AREA (WAJIB SUDAH DIVERIFIKASI)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['user', 'verified'])->group(function () {

        Route::get('/home', HomeController::class)->name('home');

        Route::get('/apply-leave', [UserAttendanceController::class, 'applyLeave'])
            ->name('apply-leave');

        Route::post('/apply-leave', [UserAttendanceController::class, 'storeLeaveRequest'])
            ->name('store-leave-request');

        Route::get('/attendance-history', [UserAttendanceController::class, 'history'])
            ->name('attendance-history');
    });

    /*
    |--------------------------------------------------------------------------
    | ADMIN AREA
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->middleware('admin')->group(function () {

        Route::get('/', fn () => redirect()->route('admin.dashboard'));

        Route::get('/dashboard', fn () => view('admin.dashboard'))
            ->name('admin.dashboard');

        Route::get('/user-verification', fn () => view('admin.user-verification'))
            ->name('admin.user-verification');

        /*
        |--------------------------------------------------------------------------
        | EMPLOYEE MANAGEMENT (VERIFIKASI DI SINI)
        |--------------------------------------------------------------------------
        */
        Route::get('/employees', [EmployeeController::class, 'index'])
            ->name('admin.employees');

        Route::post('/employees/{id}/verify', [EmployeeController::class, 'verify'])
            ->name('admin.employees.verify');

        Route::post('/employees/{id}/deactivate', [EmployeeController::class, 'deactivate'])
            ->name('admin.employees.deactivate');

        Route::delete('/employees/{id}', [EmployeeController::class, 'destroy'])
            ->name('admin.employees.destroy');

        /*
        |--------------------------------------------------------------------------
        | BARCODE
        |--------------------------------------------------------------------------
        */
        Route::resource('/barcodes', BarcodeController::class)
            ->only(['index', 'show', 'create', 'store', 'edit', 'update'])
            ->names([
                'index'  => 'admin.barcodes',
                'show'   => 'admin.barcodes.show',
                'create' => 'admin.barcodes.create',
                'store'  => 'admin.barcodes.store',
                'edit'   => 'admin.barcodes.edit',
                'update' => 'admin.barcodes.update',
            ]);

        Route::get('/barcodes/download/all', [BarcodeController::class, 'downloadAll'])
            ->name('admin.barcodes.downloadall');

        Route::get('/barcodes/{id}/download', [BarcodeController::class, 'download'])
            ->name('admin.barcodes.download');

        /*
        |--------------------------------------------------------------------------
        | MASTER DATA
        |--------------------------------------------------------------------------
        */
        Route::prefix('masterdata')->group(function () {
            Route::get('/division',  [MasterDataController::class, 'division'])->name('admin.masters.division');
            Route::get('/job-title', [MasterDataController::class, 'jobTitle'])->name('admin.masters.job-title');
            Route::get('/education', [MasterDataController::class, 'education'])->name('admin.masters.education');
            Route::get('/shift',     [MasterDataController::class, 'shift'])->name('admin.masters.shift');
            Route::get('/admin',     [MasterDataController::class, 'admin'])->name('admin.masters.admin');
        });

        /*
        |--------------------------------------------------------------------------
        | ATTENDANCE
        |--------------------------------------------------------------------------
        */
        Route::get('/attendances', [AttendanceController::class, 'index'])
            ->name('admin.attendances');

        Route::get('/attendances/report', [AttendanceController::class, 'report'])
            ->name('admin.attendances.report');

        /*
        |--------------------------------------------------------------------------
        | IMPORT EXPORT
        |--------------------------------------------------------------------------
        */
        Route::prefix('import-export')->group(function () {
            Route::get('/users', [ImportExportController::class, 'users'])
                ->name('admin.import-export.users');

            Route::get('/attendances', [ImportExportController::class, 'attendances'])
                ->name('admin.import-export.attendances');

            Route::post('/users/import', [ImportExportController::class, 'importUsers'])
                ->name('admin.users.import');

            Route::post('/attendances/import', [ImportExportController::class, 'importAttendances'])
                ->name('admin.attendances.import');

            Route::get('/users/export', [ImportExportController::class, 'exportUsers'])
                ->name('admin.users.export');

            Route::get('/attendances/export', [ImportExportController::class, 'exportAttendances'])
                ->name('admin.attendances.export');
        });
    });
});

/*
|--------------------------------------------------------------------------
| LIVEWIRE ROUTE
|--------------------------------------------------------------------------
*/
Livewire::setUpdateRoute(function ($handle) {
    return Route::post(
        Helpers::getNonRootBaseUrlPath() . '/livewire/update',
        $handle
    );
});

Livewire::setScriptRoute(function ($handle) {
    $path = config('app.debug')
        ? '/livewire/livewire.js'
        : '/livewire/livewire.min.js';

    return Route::get(url($path), $handle);
});
