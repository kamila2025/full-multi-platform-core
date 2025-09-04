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

// Route::middleware([
//     'web',
//     InitializeTenancyByDomain::class,
//     PreventAccessFromCentralDomains::class,
// ])->group(function () {
//     Route::get('/', function () {
//         return 'This is your multi-tenant application. The id of the current tenant is ' . tenant('id');
//     });
// });

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
});
