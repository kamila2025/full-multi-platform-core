<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->group(function () {
  Route::prefix('admin')->middleware([])->group(function () {
    // Auth
    Route::get('/login', [\App\Http\Controllers\Admin\AdminAuthController::class, 'index'])->name('admin.login.index');
    Route::post('/login', [\App\Http\Controllers\Admin\AdminAuthController::class, 'login'])->name('admin.login');
    Route::post('/logout', [\App\Http\Controllers\Admin\AdminAuthController::class, 'logout'])->name('admin.logout');

    // 管理後台
    Route::middleware(['auth:web'])->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('admin.dashboard.index');

        // Tenant
        Route::resource('tenants', \App\Http\Controllers\Admin\AdminTenantController::class, [
          'names' => [
              'index' => 'central.tenant.index',
              'create' => 'central.tenant.create',
              'store' => 'central.tenant.store',
              'show' => 'central.tenant.show',
              'edit' => 'central.tenant.edit',
              'update' => 'central.tenant.update',
              'destroy' => 'central.tenant.destroy',
          ]
        ]);
    });

  });
});


// Main Page Route
// Route::get('/', [HomePage::class, 'index'])->name('pages-home');
// Route::get('/page-2', [Page2::class, 'index'])->name('pages-page-2');

// // locale
// Route::get('lang/{locale}', [LanguageController::class, 'swap']);

// // pages
// Route::get('/pages/misc-error', [MiscError::class, 'index'])->name('pages-misc-error');

// // authentication
// Route::get('/auth/login-basic', [LoginBasic::class, 'index'])->name('auth-login-basic');
// Route::get('/auth/register-basic', [RegisterBasic::class, 'index'])->name('auth-register-basic');
