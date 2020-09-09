<?php

namespace Modules\ExpensesApproval\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
// models
use Modules\ExpensesApproval\Entities\Category;
use Modules\ExpensesApproval\Entities\ExpensesApproval;
use Modules\ExpensesApproval\Entities\ExpensesAttachment;
use Modules\ExpensesApproval\Entities\ExpensesProposal;
use Modules\ExpensesApproval\Entities\ExpensesRule;
// policies
use Modules\ExpensesApproval\Policies\CategoryPolicy;
use Modules\ExpensesApproval\Policies\ExpensesApprovalPolicy;
use Modules\ExpensesApproval\Policies\ExpensesAttachmentPolicy;
use Modules\ExpensesApproval\Policies\ExpensesProposalPolicy;
use Modules\ExpensesApproval\Policies\ExpensesRulePolicy;

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

        Gate::policy(Category::class, CategoryPolicy::class);
        Gate::policy(ExpensesApproval::class, ExpensesApprovalPolicy::class);
        Gate::policy(ExpensesAttachment::class, ExpensesAttachmentPolicy::class);
        Gate::policy(ExpensesProposal::class, ExpensesProposalPolicy::class);
        Gate::policy(ExpensesRule::class, ExpensesRulePolicy::class);
       
    }
}