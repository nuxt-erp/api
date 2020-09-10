<?php

namespace Modules\Production\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Modules\Production\Entities\Action;
use Modules\Production\Entities\Flow;
use Modules\Production\Entities\FlowAction;
use Modules\Production\Entities\Machine;
use Modules\Production\Entities\Operation;
use Modules\Production\Entities\OperationResult;
use Modules\Production\Entities\Phase;
use Modules\Production\Entities\Production;
use Modules\Production\Policies\ActionPolicy;
use Modules\Production\Policies\FlowPolicy;
use Modules\Production\Policies\FlowActionPolicy;
use Modules\Production\Policies\MachinePolicy;
use Modules\Production\Policies\OperationPolicy;
use Modules\Production\Policies\OperationResultPolicy;
use Modules\Production\Policies\PhasePolicy;
use Modules\Production\Policies\ProductionPolicy;

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

        Gate::policy(Action::class, ActionPolicy::class);
        Gate::policy(Flow::class, FlowPolicy::class);
        Gate::policy(FlowAction::class, FlowActionPolicy::class);
        Gate::policy(Machine::class, MachinePolicy::class);
        Gate::policy(Operation::class, OperationPolicy::class);
        Gate::policy(OperationResult::class, OperationResultPolicy::class);
        Gate::policy(Phase::class, PhasePolicy::class);
        Gate::policy(Production::class, ProductionPolicy::class);
    }
}
