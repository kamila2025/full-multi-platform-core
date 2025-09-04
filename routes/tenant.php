<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

/**
 * 租戶路由
 */
Route::group([
  'prefix' => '/{tenant}',
  'middleware' => [
    'web',
    'tenant.session',
    Stancl\Tenancy\Middleware\InitializeTenancyByPath::class,
  ],
], function () {
  // 模擬租戶登入
  Route::get('/impersonate/{token}', function ($token) {
    return Stancl\Tenancy\Features\UserImpersonation::makeResponse($token);
  })->name('tenants.impersonate.login');

  // Auth
  Route::get('/login', [\App\Http\Controllers\Tenant\TenantAuthController::class, 'index'])->name('tenant.login.index');
  Route::post('/login', [\App\Http\Controllers\Tenant\TenantAuthController::class, 'login'])->name('tenant.login');
  Route::post('/logout', [\App\Http\Controllers\Tenant\TenantAuthController::class, 'logout'])->name('tenant.logout');

  // Dashboard
  Route::middleware(['auth:tenant'])->group(function () {
      Route::get('/dashboard', [\App\Http\Controllers\Tenant\TenantDashboardController::class, 'index'])->name('tenant.dashboard.index');
  });
});
