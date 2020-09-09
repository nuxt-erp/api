<?php

namespace Modules\RD\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Modules\RD\Entities\Project;
use Modules\RD\Entities\ProjectItemAttributes;
use Modules\RD\Entities\ProjectSamples;
use Modules\RD\Entities\Recipe;
use Modules\RD\Entities\RecipeAttributes;
use Modules\RD\Entities\RecipeItems;
use Modules\RD\Entities\RecipeProposalItems;
use Modules\RD\Entities\RecipeProposals;
use Modules\RD\Policies\ProjectPolicy;
use Modules\RD\Policies\ProjectItemAttributesPolicy;
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
        Gate::policy(ProjectItemAttributes::class, ProjectItemAttributesPolicy::class);
        Gate::policy(ProjectSamples::class, ProjectSamplesPolicy::class);
        Gate::policy(Recipe::class, RecipePolicy::class);
        Gate::policy(RecipeAttributes::class, RecipeAttributesPolicy::class);
        Gate::policy(RecipeItems::class, RecipeItemsPolicy::class);
        Gate::policy(RecipeProposalItems::class, RecipeProposalItemsPolicy::class);
        Gate::policy(RecipeProposals::class, RecipeProposalsPolicy::class);
    }
}
