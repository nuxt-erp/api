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
use Modules\Inventory\Entities\ProductSupplierLocations;
use Modules\Inventory\Entities\StockLocator;
use Modules\Inventory\Entities\Measure;
use Modules\Inventory\Entities\StockCount;
use Modules\Inventory\Entities\StockCountDetail;
use Modules\Inventory\Entities\FamilyAttribute;
use Modules\Inventory\Entities\ProductImages;
use Modules\Inventory\Entities\ProductPromo;
use Modules\Inventory\Entities\ProductReorderLevel;
use Modules\Inventory\Entities\Transfer;
use Modules\Inventory\Entities\TransferDetails;
use Modules\Inventory\Entities\CustomerDiscount;
use Modules\Inventory\Entities\LocationBin;
use Modules\Inventory\Entities\PriceTier;
use Modules\Inventory\Entities\PriceTierItems;
use Modules\Inventory\Entities\ProductCustomPrice;
use Modules\Inventory\Entities\ProductImportSettings;
use Modules\Inventory\Entities\ProductTag;
use Modules\Inventory\Entities\StockAdjustment;
use Modules\Inventory\Entities\StockAdjustmentDetail;
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
use Modules\Inventory\Repositories\ProductSupplierLocationsRepository;
use Modules\Inventory\Repositories\MeasureRepository;
use Modules\Inventory\Repositories\StockCountRepository;
use Modules\Inventory\Repositories\StockCountDetailRepository;
use Modules\Inventory\Repositories\FamilyAttributeRepository;
use Modules\Inventory\Repositories\ProductImagesRepository;
use Modules\Inventory\Repositories\ProductPromoRepository;
use Modules\Inventory\Repositories\ProductReorderLevelRepository;
use Modules\Inventory\Repositories\TransferRepository;
use Modules\Inventory\Repositories\TransferDetailsRepository;
use Modules\Inventory\Repositories\CustomerDiscountRepository;
use Modules\Inventory\Repositories\LocationBinRepository;
use Modules\Inventory\Repositories\PriceTierRepository;
use Modules\Inventory\Repositories\PriceTierItemsRepository;

use Modules\Inventory\Repositories\ProductCustomPriceRepository;
use Modules\Inventory\Repositories\ProductImportSettingsRepository;
use Modules\Inventory\Repositories\ProductTagRepository;
use Modules\Inventory\Repositories\StockAdjustmentDetailRepository;
use Modules\Inventory\Repositories\StockAdjustmentRepository;
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
use Modules\Inventory\Transformers\ProductSupplierLocationsResource;
use Modules\Inventory\Transformers\MeasureResource;
use Modules\Inventory\Transformers\StockCountResource;
use Modules\Inventory\Transformers\StockCountDetailResource;
use Modules\Inventory\Transformers\FamilyAttributeResource;
use Modules\Inventory\Transformers\ProductImagesResource;
use Modules\Inventory\Transformers\ProductPromoResource;
use Modules\Inventory\Transformers\ProductReorderLevelResource;
use Modules\Inventory\Transformers\TransferResource;
use Modules\Inventory\Transformers\TransferDetailsResource;
use Modules\Inventory\Transformers\CustomerDiscountResource;
use Modules\Inventory\Transformers\LocationBinResource;
use Modules\Inventory\Transformers\PriceTierResource;
use Modules\Inventory\Transformers\PriceTierItemsResource;

use Modules\Inventory\Transformers\ProductCustomPriceResource;
use Modules\Inventory\Transformers\ProductImportSettingsResource;
use Modules\Inventory\Transformers\ProductTagResource;
use Modules\Inventory\Transformers\StockAdjustmentDetailResource;
use Modules\Inventory\Transformers\StockAdjustmentResource;

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

        $this->app->bind(CustomerDiscountRepository::class, function () {
            return new CustomerDiscountRepository(new CustomerDiscount());
        });

        $this->app->bind(CustomerDiscountResource::class, function () {
            return new CustomerDiscountResource(new CustomerDiscount());
        });
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
        $this->app->bind(ProductSupplierLocationsResource::class, function () {
            return new ProductSupplierLocationsResource(new ProductSupplierLocations());
        });

        $this->app->bind(ProductReorderLevelResource::class, function () {
            return new ProductReorderLevelResource(new ProductReorderLevel());
        });

        $this->app->bind(ProductReorderLevelRepository::class, function () {
            return new ProductReorderLevelRepository(new ProductReorderLevel());
        });

        $this->app->bind(ProductPromoResource::class, function () {
            return new ProductPromoResource(new ProductPromo());
        });

        $this->app->bind(ProductPromoRepository::class, function () {
            return new ProductPromoRepository(new ProductPromo());
        });

        $this->app->bind(FamilyRepository::class, function () {
            return new FamilyRepository(new Family());
        });

        $this->app->bind(FamilyResource::class, function () {
            return new FamilyResource(new Family());
        });
        $this->app->bind(TransferRepository::class, function () {
            return new TransferRepository(new Transfer());
        });

        $this->app->bind(TransferResource::class, function () {
            return new TransferResource(new Transfer());
        });
        $this->app->bind(TransferDetailsRepository::class, function () {
            return new TransferDetailsRepository(new TransferDetails());
        });

        $this->app->bind(TransferDetailsResource::class, function () {
            return new TransferDetailsResource(new TransferDetails());
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

        $this->app->bind(ProductSupplierLocationsRepository::class, function () {
            return new ProductSupplierLocationsRepository(new ProductSupplierLocations());
        });

        $this->app->bind(StockCountRepository::class, function () {
            return new StockCountRepository(new StockCount());
        });

        $this->app->bind(StockCountDetailRepository::class, function () {
            return new StockCountDetailRepository(new StockCountDetail());
        });

        $this->app->bind(StockCountDetailResource::class, function () {
            return new StockCountDetailResource(new StockCountDetail());
        });

        $this->app->bind(StockCountResource::class, function () {
            return new StockCountResource(new StockCount());
        });

        $this->app->bind(ProductLogResource::class, function () {
            return new ProductLogResource(new ProductLog());
        });
        $this->app->bind(AttributeResource::class, function () {
            return new AttributeResource(new Attribute());
        });

        $this->app->bind(ProductImagesRepository::class, function () {
            return new ProductImagesRepository(new ProductImages());
        });
        $this->app->bind(ProductImagesResource::class, function () {
            return new ProductImagesResource(new ProductImages());
        });

        $this->app->bind(ProductCustomPriceRepository::class, function () {
            return new ProductCustomPriceRepository(new ProductCustomPrice());
        });
        $this->app->bind(ProductCustomPriceResource::class, function () {
            return new ProductCustomPriceResource(new ProductCustomPrice());
        });

        $this->app->bind(StockAdjustmentRepository::class, function () {
            return new StockAdjustmentRepository(new StockAdjustment());
        });
        $this->app->bind(StockAdjustmentResource::class, function () {
            return new StockAdjustmentResource(new StockAdjustment());
        });

        $this->app->bind(StockAdjustmentDetailRepository::class, function () {
            return new StockAdjustmentDetailRepository(new StockAdjustmentDetail());
        });
        $this->app->bind(StockAdjustmentDetailResource::class, function () {
            return new StockAdjustmentDetailResource(new StockAdjustmentDetail());
        });

        $this->app->bind(ProductTagRepository::class, function () {
            return new ProductTagRepository(new ProductTag());
        });
        $this->app->bind(ProductTagResource::class, function () {
            return new ProductTagResource(new ProductTag());
        });

        $this->app->bind(ProductImportSettingsRepository::class, function () {
            return new ProductImportSettingsRepository(new ProductImportSettings());
        });
        $this->app->bind(ProductImportSettingsResource::class, function () {
            return new ProductImportSettingsResource(new ProductImportSettings());
        });

        $this->app->bind(PriceTierRepository::class, function () {
            return new PriceTierRepository(new PriceTier());
        });
        $this->app->bind(PriceTierResource::class, function () {
            return new PriceTierResource(new PriceTier());
        });

        $this->app->bind(LocationBinRepository::class, function () {
            return new LocationBinRepository(new LocationBin());
        });
        $this->app->bind(LocationBinResource::class, function () {
            return new LocationBinResource(new LocationBin());
        });

        $this->app->bind(PriceTierItemsRepository::class, function () {
            return new PriceTierItemsRepository(new PriceTierItems());
        });
        $this->app->bind(PriceTierItemsResource::class, function () {
            return new PriceTierItemsResource(new PriceTierItems());
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
            ProductSupplierLocationsRepository::class,
            StockLocatorRepository::class,
            MeasureRepository::class,
            StockCountRepository::class,
            StockCountDetailRepository::class,
            FamilyAttributeRepository::class,
            TransferRepository::class,
            TransferDetailsRepository::class,
            CustomerDiscountRepository::class,
            ProductImagesRepository::class,
            ProductReorderLevelRepository::class,
            ProductPromoRepository::class,
            ProductCustomPriceRepository::class,
            StockAdjustmentRepository::class,
            StockAdjustmentDetailRepository::class,
            ProductTagRepository::class,
            ProductImportSettingsRepository::class,
            PriceTierRepository::class,
            LocationBinRepository::class,
            PriceTierItemsRepository::class

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
