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
// policies
use Modules\Inventory\Policies\AttributePolicy;
use Modules\Inventory\Policies\AvailabilityPolicy;
use Modules\Inventory\Policies\BrandPolicy;
use Modules\Inventory\Policies\CategoryPolicy;
use Modules\Inventory\Policies\FamilyPolicy;
use Modules\Inventory\Policies\ProductPolicy;

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
    }
}
