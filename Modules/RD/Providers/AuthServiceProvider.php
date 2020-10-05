<?php

namespace Modules\RD\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Modules\RD\Entities\Flow;
use Modules\RD\Entities\Phase;
use Modules\RD\Entities\PhaseRole;
use Modules\RD\Entities\Project;
use Modules\RD\Entities\ProjectSampleAttributes;
use Modules\RD\Entities\ProjectLogs;
use Modules\RD\Entities\ProjectSampleLogs;
use Modules\RD\Entities\ProjectSamples;
use Modules\RD\Entities\Recipe;
use Modules\RD\Entities\RecipeAttributes;
use Modules\RD\Entities\RecipeItems;
use Modules\RD\Entities\RecipeProposalItems;
use Modules\RD\Entities\RecipeProposals;
use Modules\RD\Policies\FlowPolicy;
use Modules\RD\Policies\PhasePolicy;
use Modules\RD\Policies\PhaseRolePolicy;
use Modules\RD\Policies\ProjectPolicy;
use Modules\RD\Policies\ProjectSampleAttributesPolicy;
use Modules\RD\Policies\ProjectLogsPolicy;
use Modules\RD\Policies\ProjectSampleLogsPolicy;
use Modules\RD\Policies\ProjectSamplesPolicy;
use Modules\RD\Policies\RecipePolicy;
use Modules\RD\Policies\RecipeAttributesPolicy;
use Modules\RD\Policies\RecipeItemsPolicy;
use Modules\RD\Policies\RecipeProposalItemsPolicy;
use Modules\RD\Policies\RecipeProposalsPolicy;

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

        Gate::policy(Project::class, ProjectPolicy::class);
        Gate::policy(ProjectSampleAttributes::class, ProjectSampleAttributesPolicy::class);
        Gate::policy(ProjectSamples::class, ProjectSamplesPolicy::class);
        Gate::policy(ProjectLogs::class, ProjectLogsPolicy::class);
        Gate::policy(ProjectSampleLogs::class, ProjectSampleLogsPolicy::class);
        Gate::policy(Recipe::class, RecipePolicy::class);
        Gate::policy(RecipeAttributes::class, RecipeAttributesPolicy::class);
        Gate::policy(RecipeItems::class, RecipeItemsPolicy::class);
        Gate::policy(RecipeProposalItems::class, RecipeProposalItemsPolicy::class);
        Gate::policy(RecipeProposals::class, RecipeProposalsPolicy::class);
        Gate::policy(Phase::class, PhasePolicy::class);
        Gate::policy(Flow::class, FlowPolicy::class);
        Gate::policy(PhaseRole::class, PhaseRolePolicy::class);
    }
}
