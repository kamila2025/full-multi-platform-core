<?php

use Illuminate\Support\Facades\Route;

/**
 * 管理後台路由
 */
Route::middleware(['web'])->group(function () {
  Route::prefix('admin')->middleware([])->group(function () {
    // Auth
    Route::get('/login', [\App\Http\Controllers\Admin\AdminAuthController::class, 'index'])->name('admin.login.index');
    Route::post('/login', [\App\Http\Controllers\Admin\AdminAuthController::class, 'login'])->name('admin.login');
    Route::post('/logout', [\App\Http\Controllers\Admin\AdminAuthController::class, 'logout'])->name('admin.logout');

    // 管理後台
    Route::middleware(['auth:web'])->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('admin.dashboard.index');

        // 租戶 Tenant
        Route::resource('tenants', \App\Http\Controllers\Admin\AdminTenantController::class)->names('admin.tenants');
        // 模擬登入租戶
        Route::get('/tenants/{id}/impersonate', [\App\Http\Controllers\Admin\AdminTenantController::class, 'impersonate'])->name('admin.impersonate.login');;
    });
  });
});

/**
 * 租戶路由
 */
Route::group([
  'prefix' => '/{tenant}',
  'middleware' => [Stancl\Tenancy\Middleware\InitializeTenancyByPath::class],
], function () {
  // 模擬租戶登入
  Route::get('/impersonate/{token}', function ($token) {
    return Stancl\Tenancy\Features\UserImpersonation::makeResponse($token);
  })->name('tenants.impersonate.login');

  Route::get('/', function () {
    return 'This is your multi-tenant application. The id of the current tenant is ' . tenant('id');
  });
});
