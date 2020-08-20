<?php

namespace App\Providers;

use App\Models\Country;
use App\Models\Location;
use App\Models\Parameter;
use App\Models\Province;
use App\Models\Role;
use App\Models\Supplier;
use App\Models\User;
// Policies
use App\Policies\CountryPolicy;
use App\Policies\LocationPolicy;
use App\Policies\ParameterPolicy;
use App\Policies\ProvincePolicy;
use App\Policies\RolePolicy;
use App\Policies\SupplierPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
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

        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Role::class, RolePolicy::class);
        Gate::policy(Parameter::class, ParameterPolicy::class);
        Gate::policy(Location::class, LocationPolicy::class);
        Gate::policy(Country::class, CountryPolicy::class);
        Gate::policy(Province::class, ProvincePolicy::class);
        Gate::policy(Supplier::class, SupplierPolicy::class);
    }
}
