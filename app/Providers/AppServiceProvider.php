<?php

namespace App\Providers;

use App\Service\DearService;
use App\Service\ShopifyService;
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
            return new DearService(config('dear.id'), config('dear.key'), config('dear.url'));
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
