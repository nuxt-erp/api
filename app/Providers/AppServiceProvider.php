<?php

namespace App\Providers;

use App\Services\ShopifyService;
use App\Services\DearService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Dear\API', function(){
            return new DearService();
        });

        $this->app->bind('Shopify\API', function(){
            return new ShopifyService();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
