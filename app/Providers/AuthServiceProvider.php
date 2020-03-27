<?php

namespace App\Providers;

use App\Models\Brand;
use App\Models\Employee;
use App\Models\Flavor;
use App\Models\Flow;
use App\Models\FlowAction;
use App\Models\Location;
use App\Models\Machine;
use App\Models\Operation;
use App\Models\Parameter;
use App\Models\Phase;
use App\Models\Product;
use App\Models\ProductAvailability;
use App\Models\ProductGroup;
use App\Models\ProductionOrder;
use App\Models\Role;
use App\Models\User;
use App\Models\Warehouse;
use App\Policies\BrandPolicy;
use App\Policies\EmployeePolicy;
use App\Policies\FlavorPolicy;
use App\Policies\FlowActionPolicy;
use App\Policies\FlowPolicy;
use App\Policies\LocationPolicy;
use App\Policies\MachinePolicy;
use App\Policies\OperationPolicy;
use App\Policies\ParameterPolicy;
use App\Policies\PhasePolicy;
use App\Policies\ProductAvailabilityPolicy;
use App\Policies\ProductGroupPolicy;
use App\Policies\ProductionOrderPolicy;
use App\Policies\ProductPolicy;
use App\Policies\RolePolicy;
use App\Policies\UserPolicy;
use App\Policies\WarehousePolicy;
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
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::policy(Warehouse::class, WarehousePolicy::class);
        Gate::policy(Location::class, LocationPolicy::class);
        Gate::policy(ProductAvailability::class, ProductAvailabilityPolicy::class);
        Gate::policy(Flow::class, FlowPolicy::class);
        Gate::policy(Phase::class, PhasePolicy::class);
        Gate::policy(FlowAction::class, FlowActionPolicy::class);
        Gate::policy(ProductionOrder::class, ProductionOrderPolicy::class);
        Gate::policy(Operation::class, OperationPolicy::class);

        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Role::class, RolePolicy::class);
        Gate::policy(Employee::class, EmployeePolicy::class);
        Gate::policy(Machine::class, MachinePolicy::class);
        Gate::policy(ProductGroup::class, ProductGroupPolicy::class);
        Gate::policy(Product::class, ProductPolicy::class);
        Gate::policy(Parameter::class, ParameterPolicy::class);
        Gate::policy(Flavor::class, FlavorPolicy::class);
        Gate::policy(Brand::class, BrandPolicy::class);


        //Gate::define('update-post', 'App\Policies\PostPolicy@update');

    }
}
