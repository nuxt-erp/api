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
use Modules\Inventory\Entities\StockLocator;
use Modules\Inventory\Entities\Measure;
use Modules\Inventory\Entities\StockTake;
use Modules\Inventory\Entities\StockTakeDetail;


// repositories
use Modules\Inventory\Repositories\AttributeRepository;
use Modules\Inventory\Repositories\AvailabilityRepository;
use Modules\Inventory\Repositories\BrandRepository;
use Modules\Inventory\Repositories\StockLocatorRepository;
use Modules\Inventory\Repositories\CategoryRepository;
use Modules\Inventory\Repositories\FamilyRepository;
use Modules\Inventory\Repositories\ProductAttributeRepository;
use Modules\Inventory\Repositories\ProductLogRepository;
use Modules\Inventory\Repositories\ProductRepository;
use Modules\Inventory\Repositories\MeasureRepository;
use Modules\Inventory\Repositories\StockTakeRepository;
use Modules\Inventory\Repositories\StockTakeDetailRepository;

// resources
use Modules\Inventory\Transformers\AttributeResource;
use Modules\Inventory\Transformers\AvailabilityResource;
use Modules\Inventory\Transformers\BrandResource;
use Modules\Inventory\Transformers\StockLocatorResource;
use Modules\Inventory\Transformers\CategoryResource;
use Modules\Inventory\Transformers\FamilyResource;
use Modules\Inventory\Transformers\ProductAttributeResource;
use Modules\Inventory\Transformers\ProductLogResource;
use Modules\Inventory\Transformers\ProductResource;
use Modules\Inventory\Transformers\MeasureResource;
use Modules\Inventory\Transformers\StockTakeResource;
use Modules\Inventory\Transformers\StockTakeDetailResource;

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
        $this->registerConfig();
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
        $this->app->bind(MeasureRepository::class, function () {
            return new MeasureRepository(new Measure());
        });

        $this->app->bind(MeasureResource::class, function () {
            return new MeasureResource(new Measure());
        });
        $this->app->bind(StockLocatorRepository::class, function () {
            return new StockLocatorRepository(new StockLocator());
        });

        $this->app->bind(StockLocatorResource::class, function () {
            return new StockLocatorResource(new StockLocator());
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

        $this->app->bind(StockTakeResource::class, function () {
            return new StockTakeResource(new StockTake());
        });

        $this->app->bind(StockTakeDetailResource::class, function () {
            return new StockTakeDetailResource(new StockTakeDetail());
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
            ProductLogRepository::class,
            StockLocatorRepository::class,
            MeasureRepository::class,
            StockTakeRepository::class,
            StockTakeDetailRepository::class,

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
}
