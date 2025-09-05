<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
  /**
   * Register services.
   */
  public function register(): void
  {
    //
  }

  /**
   * Bootstrap services.
   */
  public function boot(): void
  {
    \View::composer('*', function ($view) {
      if (tenant()) {
        // 租戶環境 menu
        $verticalMenuJson = file_get_contents(base_path('resources/menu/tenantverticalMenu.json'));
      } else {
        // 中央環境 menu
        $verticalMenuJson = file_get_contents(base_path('resources/menu/verticalMenu.json'));
      }

      $verticalMenuData = json_decode($verticalMenuJson);

      $view->with('menuData', [$verticalMenuData]);
    });
  }
}
