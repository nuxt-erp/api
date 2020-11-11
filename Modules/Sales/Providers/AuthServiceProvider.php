<?php

namespace Modules\Sales\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

// models
use Modules\Sales\Entities\Sale;
use Modules\Sales\Entities\SaleDetails;
use Modules\Sales\Entities\Discount;
use Modules\Sales\Entities\DiscountApplication;
use Modules\Sales\Entities\DiscountRule;
use Modules\Sales\Entities\DiscountTag;

// policies
use Modules\Sales\Policies\SaleDetailsPolicy;
use Modules\Sales\Policies\SalePolicy;
use Modules\Sales\Policies\DiscountApplicationPolicy;
use Modules\Sales\Policies\DiscountPolicy;
use Modules\Sales\Policies\DiscountRulePolicy;
use Modules\Sales\Policies\DiscountTagPolicy;

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

        Gate::policy(Sale::class, SalePolicy::class);
        Gate::policy(SaleDetails::class, SaleDetailsPolicy::class);
        Gate::policy(Discount::class, DiscountPolicy::class);
        Gate::policy(DiscountApplication::class, DiscountApplicationPolicy::class);
        Gate::policy(DiscountTag::class, DiscountTagPolicy::class);
        Gate::policy(DiscountRule::class, DiscountRulePolicy::class);
    }
}
