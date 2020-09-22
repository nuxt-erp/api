<?php

namespace Modules\Inventory\Providers;

use Illuminate\Support\ServiceProvider;
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
use Modules\Inventory\Entities\ProductSuppliers;
use Modules\Inventory\Entities\StockLocator;
use Modules\Inventory\Entities\Measure;
use Modules\Inventory\Entities\StockCount;
use Modules\Inventory\Entities\StockCountDetail;
use Modules\Inventory\Entities\FamilyAttribute;


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
use Modules\Inventory\Repositories\ProductSuppliersRepository;
use Modules\Inventory\Repositories\MeasureRepository;
use Modules\Inventory\Repositories\StockCountRepository;
use Modules\Inventory\Repositories\StockCountDetailRepository;
use Modules\Inventory\Repositories\FamilyAttributeRepository;

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
use Modules\Inventory\Transformers\ProductSuppliersResource;
use Modules\Inventory\Transformers\MeasureResource;
use Modules\Inventory\Transformers\StockCountResource;
use Modules\Inventory\Transformers\StockCountDetailResource;
use Modules\Inventory\Transformers\FamilyAttributeResource;

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

        $this->app->bind(ProductSuppliersResource::class, function () {
            return new ProductSuppliersResource(new ProductSuppliers());
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
        $this->app->bind(FamilyAttributeRepository::class, function () {
            return new FamilyAttributeRepository(new FamilyAttribute());
        });
        $this->app->bind(FamilyAttributeResource::class, function () {
            return new FamilyAttributeResource(new FamilyAttribute());
        });

        $this->app->bind(ProductLogRepository::class, function () {
            return new ProductLogRepository(new ProductLog());
        });

        $this->app->bind(ProductSuppliersRepository::class, function () {
            return new ProductSuppliersRepository(new ProductSuppliers());
        });

        $this->app->bind(StockCountRepository::class, function () {
            return new StockCountRepository(new StockCount());
        });

        $this->app->bind(StockCountDetailRepository::class, function () {
            return new StockCountDetailRepository(new StockCountDetail());
        });

        $this->app->bind(ProductLogResource::class, function () {
            return new ProductLogResource(new ProductLog());
        });

        $this->app->bind(StockCountResource::class, function () {
            return new StockCountResource(new StockCount());
        });

        $this->app->bind(StockCountDetailResource::class, function () {
            return new StockCountDetailResource(new StockCountDetail());
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
            ProductSuppliersRepository::class,
            StockLocatorRepository::class,
            MeasureRepository::class,
            StockCountRepository::class,
            StockCountDetailRepository::class,
            FamilyAttributeRepository::class
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
