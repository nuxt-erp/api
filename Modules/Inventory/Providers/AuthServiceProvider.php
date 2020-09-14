<?php

namespace Modules\Inventory\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
// models
use Modules\Inventory\Entities\Attribute;
use Modules\Inventory\Entities\Availability;
use Modules\Inventory\Entities\Brand;
use Modules\Inventory\Entities\Category;
use Modules\Inventory\Entities\Family;
use Modules\Inventory\Entities\Product;
use Modules\Inventory\Entities\ProductAttributes;
use Modules\Inventory\Entities\ProductFamilyAttribute;
use Modules\Inventory\Entities\ProductLog;
use Modules\Inventory\Entities\StockLocator;
use Modules\Inventory\Entities\Measure;

// policies
use Modules\Inventory\Policies\AttributePolicy;
use Modules\Inventory\Policies\AvailabilityPolicy;
use Modules\Inventory\Policies\BrandPolicy;
use Modules\Inventory\Policies\CategoryPolicy;
use Modules\Inventory\Policies\FamilyPolicy;
use Modules\Inventory\Policies\ProductAttributesPolicy;
use Modules\Inventory\Policies\ProductFamilyAttributePolicy;
use Modules\Inventory\Policies\ProductLogPolicy;
use Modules\Inventory\Policies\ProductPolicy;
use Modules\Inventory\Policies\StockLocatorPolicy;
use Modules\Inventory\Policies\MeasurePolicy;


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
        Gate::policy(Category::class, CategoryPolicy::class);
        Gate::policy(Family::class, FamilyPolicy::class);
        Gate::policy(Product::class, ProductPolicy::class);
        Gate::policy(ProductLog::class, ProductLogPolicy::class);
        Gate::policy(ProductAttributes::class, ProductAttributesPolicy::class);
        Gate::policy(ProductFamilyAttribute::class, ProductFamilyAttributePolicy::class);

        Gate::policy(StockLocator::class, StockLocatorPolicy::class);
        Gate::policy(Measure::class, MeasurePolicy::class);

    }
}