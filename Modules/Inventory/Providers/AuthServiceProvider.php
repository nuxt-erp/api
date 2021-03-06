<?php

namespace Modules\Inventory\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
// models
use Modules\Inventory\Entities\Attribute;
use Modules\Inventory\Entities\Availability;
use Modules\Inventory\Entities\AvailabilityImportSettings;
use Modules\Inventory\Entities\BinImportSettings;
use Modules\Inventory\Entities\Brand;
use Modules\Inventory\Entities\Category;
use Modules\Inventory\Entities\Family;
use Modules\Inventory\Entities\Product;
use Modules\Inventory\Entities\ProductAttributes;
use Modules\Inventory\Entities\ProductSuppliers;
use Modules\Inventory\Entities\ProductSupplierLocations;
use Modules\Inventory\Entities\FamilyAttribute;
use Modules\Inventory\Entities\Flavor;
use Modules\Inventory\Entities\ProductLog;
use Modules\Inventory\Entities\StockLocator;
use Modules\Inventory\Entities\Measure;
use Modules\Inventory\Entities\PriceTier;
use Modules\Inventory\Entities\PriceTierItems;
use Modules\Inventory\Entities\ProductCustomPrice;
use Modules\Inventory\Entities\ProductImportSettings;
use Modules\Inventory\Entities\ProductPromo;
use Modules\Inventory\Entities\ProductReorderLevel;
use Modules\Inventory\Entities\ProductTag;
use Modules\Inventory\Entities\Receiving;
use Modules\Inventory\Entities\ReceivingDetail;
use Modules\Inventory\Entities\StockAdjustment;
use Modules\Inventory\Entities\StockAdjustmentDetail;
use Modules\Inventory\Entities\StockCount;
use Modules\Inventory\Entities\StockCountDetail;
use Modules\Inventory\Entities\StockCountFilter;
use Modules\Inventory\Entities\Transfer;
use Modules\Inventory\Entities\TransferDetails;
// policies
use Modules\Inventory\Policies\AttributePolicy;
use Modules\Inventory\Policies\AvailabilityImportSettingsPolicy;
use Modules\Inventory\Policies\AvailabilityPolicy;
use Modules\Inventory\Policies\BinImportSettingsPolicy;
use Modules\Inventory\Policies\BrandPolicy;
use Modules\Inventory\Policies\CategoryPolicy;
use Modules\Inventory\Policies\FamilyPolicy;
use Modules\Inventory\Policies\ProductAttributesPolicy;
use Modules\Inventory\Policies\ProductSuppliersPolicy;
use Modules\Inventory\Policies\ProductSupplierLocationsPolicy;
use Modules\Inventory\Policies\FamilyAttributePolicy;
use Modules\Inventory\Policies\FlavorPolicy;
use Modules\Inventory\Policies\ProductLogPolicy;
use Modules\Inventory\Policies\ProductPolicy;
use Modules\Inventory\Policies\StockLocatorPolicy;
use Modules\Inventory\Policies\MeasurePolicy;
use Modules\Inventory\Policies\PriceTierPolicy;
use Modules\Inventory\Policies\PriceTierItemsPolicy;

use Modules\Inventory\Policies\ProductCustomPricePolicy;
use Modules\Inventory\Policies\ProductImportSettingsPolicy;
use Modules\Inventory\Policies\ProductPromoPolicy;
use Modules\Inventory\Policies\ProductReorderLevelPolicy;
use Modules\Inventory\Policies\ProductTagPolicy;
use Modules\Inventory\Policies\ReceivingDetailPolicy;
use Modules\Inventory\Policies\ReceivingPolicy;
use Modules\Inventory\Policies\StockAdjustmentDetailPolicy;
use Modules\Inventory\Policies\StockAdjustmentPolicy;
use Modules\Inventory\Policies\StockCountPolicy;
use Modules\Inventory\Policies\StockCountDetailPolicy;
use Modules\Inventory\Policies\StockCountFilterPolicy;
use Modules\Inventory\Policies\TransferPolicy;
use Modules\Inventory\Policies\TransferDetailsPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::policy(Attribute::class, AttributePolicy::class);
        Gate::policy(Availability::class, AvailabilityPolicy::class);
        Gate::policy(Brand::class, BrandPolicy::class);
        Gate::policy(Flavor::class, FlavorPolicy::class);
        Gate::policy(Category::class, CategoryPolicy::class);
        Gate::policy(Family::class, FamilyPolicy::class);
        Gate::policy(Product::class, ProductPolicy::class);
        Gate::policy(ProductLog::class, ProductLogPolicy::class);
        Gate::policy(ProductAttributes::class, ProductAttributesPolicy::class);
        Gate::policy(ProductSuppliers::class, ProductSuppliersPolicy::class);
        Gate::policy(ProductSupplierLocations::class, ProductSupplierLocationsPolicy::class);
        Gate::policy(ProductReorderLevel::class, ProductReorderLevelPolicy::class);
        Gate::policy(FamilyAttribute::class, FamilyAttributePolicy::class);
        Gate::policy(Transfer::class, TransferPolicy::class);
        Gate::policy(TransferDetails::class, TransferDetailsPolicy::class);
        Gate::policy(ProductReorderLevel::class, ProductReorderLevelPolicy::class);
        Gate::policy(ProductPromo::class, ProductPromoPolicy::class);
        Gate::policy(ProductCustomPrice::class, ProductCustomPricePolicy::class);
        Gate::policy(ProductTag::class, ProductTagPolicy::class);

        Gate::policy(StockLocator::class, StockLocatorPolicy::class);
        Gate::policy(Measure::class, MeasurePolicy::class);
        Gate::policy(StockCount::class, StockCountPolicy::class);
        Gate::policy(StockCountFilter::class, StockCountFilterPolicy::class);
        Gate::policy(StockCountDetail::class, StockCountDetailPolicy::class);
        Gate::policy(StockAdjustment::class, StockAdjustmentPolicy::class);
        Gate::policy(StockAdjustmentDetail::class, StockAdjustmentDetailPolicy::class);

        Gate::policy(ProductImportSettings::class, ProductImportSettingsPolicy::class);
        Gate::policy(AvailabilityImportSettings::class, AvailabilityImportSettingsPolicy::class);
        Gate::policy(BinImportSettings::class, BinImportSettingsPolicy::class);
        Gate::policy(PriceTier::class, PriceTierPolicy::class);
        Gate::policy(PriceTierItems::class, PriceTierItemsPolicy::class);
        Gate::policy(Receiving::class, ReceivingPolicy::class);
        Gate::policy(ReceivingDetail::class, ReceivingDetailPolicy::class);

    }
}
