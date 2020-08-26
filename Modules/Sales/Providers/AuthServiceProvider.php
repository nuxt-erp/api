<?php

namespace Modules\Sales\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
// models
use Modules\Sales\Entities\Sale;
use Modules\Sales\Entities\SaleDetails;
// policies
use Modules\Sales\Policies\SaleDetailsPolicy;
use Modules\Sales\Policies\SalePolicy;

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
    }
}
