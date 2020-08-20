<?php

namespace App\Providers;

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
            //return new DearService(config('dear.id'), config('dear.key'), config('dear.url'));
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
