<?php

namespace Modules\Sales\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Sales\Entities\Sale;
use Modules\Sales\Entities\SaleDetails;
use Modules\Sales\Repositories\SaleDetailsRepository;
use Modules\Sales\Repositories\SaleRepository;
use Modules\Sales\Transformers\SaleDetailsResource;
use Modules\Sales\Transformers\SaleResource;

class SalesServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected $moduleName = 'Sales';

    /**
     * @var string $moduleNameLower
     */
    protected $moduleNameLower = 'sales';

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerConfig();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(AuthServiceProvider::class);

        $this->app->bind(SaleRepository::class, function () {
            return new SaleRepository(new Sale());
        });
        $this->app->bind(SaleResource::class, function () {
            return new SaleResource(new Sale());
        });

        $this->app->bind(SaleDetailsRepository::class, function () {
            return new SaleDetailsRepository(new SaleDetails());
        });
        $this->app->bind(SaleDetailsResource::class, function () {
            return new SaleDetailsResource(new SaleDetails());
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            SaleRepository::class,
            SaleDetailsRepository::class
        ];
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            module_path($this->moduleName, 'Config/config.php') => config_path($this->moduleNameLower . '.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path($this->moduleName, 'Config/config.php'), $this->moduleNameLower
        );
    }

}
