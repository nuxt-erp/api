<?php

namespace Modules\ExpensesApproval\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\ExpensesApproval\Entities\Category;
use Modules\ExpensesApproval\Entities\ExpensesApproval;
use Modules\ExpensesApproval\Entities\ExpensesAttachment;
use Modules\ExpensesApproval\Entities\ExpensesProposal;
use Modules\ExpensesApproval\Entities\ExpensesRule;
use Modules\ExpensesApproval\Repositories\CategoryRepository;
use Modules\ExpensesApproval\Repositories\ExpensesApprovalRepository;
use Modules\ExpensesApproval\Repositories\ExpensesAttachmentRepository;
use Modules\ExpensesApproval\Repositories\ExpensesProposalRepository;
use Modules\ExpensesApproval\Repositories\ExpensesRuleRepository;
use Modules\ExpensesApproval\Transformers\CategoryResource;
use Modules\ExpensesApproval\Transformers\ExpensesApprovalResource;
use Modules\ExpensesApproval\Transformers\ExpensesAttachmentResource;
use Modules\ExpensesApproval\Transformers\ExpensesProposalResource;
use Modules\ExpensesApproval\Transformers\ExpensesRuleResource;

class ExpensesApprovalServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected $moduleName = 'ExpensesApproval';

    /**
     * @var string $moduleNameLower
     */
    protected $moduleNameLower = 'expensesapproval';

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerConfig();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
        // $this->app->register(AuthServiceProvider::class);

        $this->app->bind(CategoryRepository::class, function () {
            return new CategoryRepository(new Category());
        });

        $this->app->bind(CategoryResource::class, function () {
            return new CategoryResource(new Category());
        });

        $this->app->bind(ExpensesApprovalRepository::class, function () {
            return new ExpensesApprovalRepository(new ExpensesApproval());
        });

        $this->app->bind(ExpensesApprovalResource::class, function () {
            return new ExpensesApprovalResource(new ExpensesApproval());
        });

        $this->app->bind(ExpensesAttachmentRepository::class, function () {
            return new ExpensesAttachmentRepository(new ExpensesAttachment());
        });

        $this->app->bind(ExpensesAttachmentResource::class, function () {
            return new ExpensesAttachmentResource(new ExpensesAttachment());
        });

        $this->app->bind(ExpensesProposalRepository::class, function () {
            return new ExpensesProposalRepository(new ExpensesProposal());
        });

        $this->app->bind(ExpensesProposalResource::class, function () {
            return new ExpensesProposalResource(new ExpensesProposal());
        });

        $this->app->bind(ExpensesRuleRepository::class, function () {
            return new ExpensesRuleRepository(new ExpensesRule());
        });

        $this->app->bind(ExpensesRuleResource::class, function () {
            return new ExpensesRuleResource(new ExpensesRule());
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            CategoryRepository::class,
            ExpensesApprovalRepository::class,
            ExpensesAttachmentRepository::class,
            ExpensesProposalRepository::class,
            ExpensesRuleRepository::class
        ];
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            module_path($this->moduleName, 'Config/config.php') => config_path($this->moduleNameLower . '.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path($this->moduleName, 'Config/config.php'), $this->moduleNameLower
        );
    }

}
