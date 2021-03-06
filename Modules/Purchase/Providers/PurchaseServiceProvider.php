<?php

namespace Modules\Purchase\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;

// models
use Modules\Purchase\Entities\Purchase;
use Modules\Purchase\Entities\PurchaseDetail;
use Modules\Purchase\Entities\PurchaseTrackingNumber;
// repositories
use Modules\Purchase\Repositories\PurchaseRepository;
use Modules\Purchase\Repositories\PurchaseDetailRepository;
use Modules\Purchase\Repositories\PurchaseTrackingNumberRepository;
// resources
use Modules\Purchase\Transformers\PurchaseResource;
use Modules\Purchase\Transformers\PurchaseDetailResource;
use Modules\Purchase\Transformers\PurchaseTrackingNumberResource;

class PurchaseServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected $moduleName = 'Purchase';

    /**
     * @var string $moduleNameLower
     */
    protected $moduleNameLower = 'purchase';

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

        $this->app->bind(PurchaseRepository::class, function () {
            return new PurchaseRepository(new Purchase());
        });

        $this->app->bind(PurchaseResource::class, function () {
            return new PurchaseResource(new Purchase());
        });

        $this->app->bind(PurchaseDetailRepository::class, function () {
            return new PurchaseDetailRepository(new PurchaseDetail());
        });

        $this->app->bind(PurchaseDetailResource::class, function () {
            return new PurchaseDetailResource(new PurchaseDetail());
        });

        $this->app->bind(PurchaseTrackingNumberRepository::class, function () {
            return new PurchaseTrackingNumberRepository(new PurchaseTrackingNumber());
        });

        $this->app->bind(PurchaseTrackingNumberResource::class, function () {
            return new PurchaseTrackingNumberResource(new PurchaseTrackingNumber());
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
            PurchaseRepository::class,
            PurchaseDetailRepository::class,
            PurchaseTrackingNumberRepository::class,
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

    /**
     * Register views.
     *
     * @return void
     */
   /* public function registerViews()
    {
        $viewPath = resource_path('views/modules/' . $this->moduleNameLower);

        $sourcePath = module_path($this->moduleName, 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ], ['views', $this->moduleNameLower . '-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->moduleNameLower);
    }*/


    /*private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (\Config::get('view.paths') as $path) {
            if (is_dir($path . '/modules/' . $this->moduleNameLower)) {
                $paths[] = $path . '/modules/' . $this->moduleNameLower;
            }
        }
        return $paths;
    }*/
}
