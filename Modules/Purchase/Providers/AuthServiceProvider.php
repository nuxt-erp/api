<?php

namespace Modules\Purchase\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
// models
use Modules\Purchase\Entities\Purchase;
use Modules\Purchase\Entities\PurchaseDetail;

// policies
use Modules\Purchase\Policies\PurchaseDetailPolicy;
use Modules\Purchase\Policies\PurchasePolicy;

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

        Gate::policy(Purchase::class, PurchasePolicy::class);
        Gate::policy(PurchaseDetail::class, PurchaseDetailPolicy::class);

    }
}
