<?php

namespace Modules\Inventory\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Console\Scheduling\Schedule;
// models
use Modules\Inventory\Entities\Attribute;
use Modules\Inventory\Entities\Availability;
use Modules\Inventory\Entities\Brand;
use Modules\Inventory\Entities\Category;
use Modules\Inventory\Entities\Family;
use Modules\Inventory\Entities\Product;
use Modules\Inventory\Entities\ProductAttributes;
use Modules\Inventory\Entities\ProductLog;
// repositories
use Modules\Inventory\Repositories\AttributeRepository;
use Modules\Inventory\Repositories\AvailabilityRepository;
use Modules\Inventory\Repositories\BrandRepository;
use Modules\Inventory\Repositories\CategoryRepository;
use Modules\Inventory\Repositories\FamilyRepository;
use Modules\Inventory\Repositories\ProductAttributeRepository;
use Modules\Inventory\Repositories\ProductLogRepository;
use Modules\Inventory\Repositories\ProductRepository;
// resources
use Modules\Inventory\Resources\AttributeResource;
use Modules\Inventory\Resources\AvailabilityResource;
use Modules\Inventory\Resources\BrandResource;
use Modules\Inventory\Resources\CategoryResource;
use Modules\Inventory\Resources\FamilyResource;
use Modules\Inventory\Resources\ProductAttributeResource;
use Modules\Inventory\Resources\ProductLogResource;
use Modules\Inventory\Resources\ProductResource;

class InventoryServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected $moduleName = 'Inventory';

    /**
     * @var string $moduleNameLower
     */
    protected $moduleNameLower = 'inventory';

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->registerFactories();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));

        // $this->app->booted(function () {
        //     $schedule = $this->app->make(Schedule::class);
        //     $schedule->call('Modules\Inventory\Http\Controllers\ImportController@importShopify')->everyMinute();
        // });


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

        $this->app->bind(BrandRepository::class, function () {
            return new BrandRepository(new Brand());
        });

        $this->app->bind(BrandResource::class, function () {
            return new BrandResource(new Brand());
        });

        $this->app->bind(CategoryRepository::class, function () {
            return new CategoryRepository(new Category());
        });

        $this->app->bind(CategoryResource::class, function () {
            return new CategoryResource(new Category());
        });

        $this->app->bind(AttributeRepository::class, function () {
            return new AttributeRepository(new Attribute());
        });

        $this->app->bind(AttributeResource::class, function () {
            return new AttributeResource(new Attribute());
        });

        $this->app->bind(ProductRepository::class, function () {
            return new ProductRepository(new Product());
        });

        $this->app->bind(ProductResource::class, function () {
            return new ProductResource(new Product());
        });

        $this->app->bind(FamilyRepository::class, function () {
            return new FamilyRepository(new Family());
        });

        $this->app->bind(FamilyResource::class, function () {
            return new FamilyResource(new Family());
        });

        $this->app->bind(AvailabilityRepository::class, function () {
            return new AvailabilityRepository(new Availability());
        });

        $this->app->bind(AvailabilityResource::class, function () {
            return new AvailabilityResource(new Availability());
        });

        $this->app->bind(ProductAttributeRepository::class, function () {
            return new ProductAttributeRepository(new ProductAttributes());
        });

        $this->app->bind(ProductAttributeResource::class, function () {
            return new ProductAttributeResource(new ProductAttributes());
        });

        $this->app->bind(ProductLogRepository::class, function () {
            return new ProductLogRepository(new ProductLog());
        });

        $this->app->bind(ProductLogResource::class, function () {
            return new ProductLogResource(new ProductLog());
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
            BrandRepository::class,
            CategoryRepository::class,
            AttributeRepository::class,
            ProductRepository::class,
            FamilyRepository::class,
            AvailabilityRepository::class,
            ProductAttributeRepository::class,
            ProductLogRepository::class
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
    public function registerViews()
    {
        // $viewPath = resource_path('views/modules/' . $this->moduleNameLower);

        // $sourcePath = module_path($this->moduleName, 'Resources/views');

        // $this->publishes([
        //     $sourcePath => $viewPath
        // ], ['views', $this->moduleNameLower . '-module-views']);

        // $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->moduleNameLower);
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        // $langPath = resource_path('lang/modules/' . $this->moduleNameLower);

        // if (is_dir($langPath)) {
        //     $this->loadTranslationsFrom($langPath, $this->moduleNameLower);
        // } else {
        //     $this->loadTranslationsFrom(module_path($this->moduleName, 'Resources/lang'), $this->moduleNameLower);
        // }
    }

    /**
     * Register an additional directory of factories.
     *
     * @return void
     */
    public function registerFactories()
    {
        if (! app()->environment('production') && $this->app->runningInConsole()) {
            app(Factory::class)->load(module_path($this->moduleName, 'Database/factories'));
        }
    }

    private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (\Config::get('view.paths') as $path) {
            if (is_dir($path . '/modules/' . $this->moduleNameLower)) {
                $paths[] = $path . '/modules/' . $this->moduleNameLower;
            }
        }
        return $paths;
    }
}
