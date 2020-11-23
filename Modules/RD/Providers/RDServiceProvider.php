<?php

namespace Modules\RD\Providers;

use Illuminate\Support\ServiceProvider;

// REPOSITORY
use Modules\RD\Repositories\ProjectRepository;
use Modules\RD\Repositories\ProjectSampleAttributesRepository;
use Modules\RD\Repositories\ProjectSamplesRepository;
use Modules\RD\Repositories\RecipeAttributesRepository;
use Modules\RD\Repositories\RecipeItemsRepository;
use Modules\RD\Repositories\RecipeProposalItemsRepository;
use Modules\RD\Repositories\RecipeProposalsRepository;
use Modules\RD\Repositories\RecipeRepository;
use Modules\RD\Repositories\ProjectLogsRepository;
use Modules\RD\Repositories\ProjectSampleLogsRepository;
use Modules\RD\Repositories\FlowRepository;
use Modules\RD\Repositories\PhaseRepository;
use Modules\RD\Repositories\PhaseRoleRepository;
use Modules\RD\Repositories\RecipeSpecificationRepository;
use Modules\RD\Repositories\RecipeSpecificationAttributesRepository;
use Modules\RD\Repositories\RecipeImportSettingsRepository;


// RESOURCES
use Modules\RD\Transformers\ProjectResource;
use Modules\RD\Transformers\ProjectSampleAttributesResource;
use Modules\RD\Transformers\ProjectSamplesResource;
use Modules\RD\Transformers\RecipeAttributesResource;
use Modules\RD\Transformers\RecipeItemsResource;
use Modules\RD\Transformers\RecipeProposalItemsResource;
use Modules\RD\Transformers\RecipeProposalsResource;
use Modules\RD\Transformers\RecipeResource;
use Modules\RD\Transformers\ProjectLogsResource;
use Modules\RD\Transformers\ProjectSampleLogsResource;
use Modules\RD\Transformers\FlowResource;
use Modules\RD\Transformers\PhaseResource;
use Modules\RD\Transformers\PhaseRoleResource;
use Modules\RD\Transformers\RecipeSpecificationResource;
use Modules\RD\Transformers\RecipeSpecificationAttributesResource;
use Modules\RD\Transformers\RecipeImportSettingsResource;

// MODELS
use Modules\RD\Entities\Project;
use Modules\RD\Entities\ProjectSampleAttributes;
use Modules\RD\Entities\ProjectLogs;
use Modules\RD\Entities\ProjectSampleLogs;
use Modules\RD\Entities\ProjectSamples;
use Modules\RD\Entities\RecipeAttributes;
use Modules\RD\Entities\RecipeItems;
use Modules\RD\Entities\RecipeProposalItems;
use Modules\RD\Entities\RecipeProposals;
use Modules\RD\Entities\Recipe;
use Modules\RD\Entities\Flow;
use Modules\RD\Entities\Phase;
use Modules\RD\Entities\PhaseRole;
use Modules\RD\Entities\RecipeImportSettings;
use Modules\RD\Entities\RecipeSpecification;
use Modules\RD\Entities\RecipeSpecificationAttributes;

class RDServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected $moduleName = 'RD';

    /**
     * @var string $moduleNameLower
     */
    protected $moduleNameLower = 'rd';

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerConfig();
        $this->registerViews();
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
        $this->app->register(AuthServiceProvider::class);

        $this->app->bind(ProjectRepository::class, function () {
            return new ProjectRepository(new Project());
        });

        $this->app->bind(ProjectResource::class, function () {
            return new ProjectResource(new Project());
        });

        $this->app->bind(ProjectSampleAttributesRepository::class, function () {
            return new ProjectSampleAttributesRepository(new ProjectSampleAttributes());
        });

        $this->app->bind(ProjectSampleAttributesResource::class, function () {
            return new ProjectSampleAttributesResource(new ProjectSampleAttributes());
        });

        $this->app->bind(ProjectSamplesRepository::class, function () {
            return new ProjectSamplesRepository(new ProjectSamples());
        });

        $this->app->bind(ProjectSamplesResource::class, function () {
            return new ProjectSamplesResource(new ProjectSamples());
        });

        $this->app->bind(ProjectLogsRepository::class, function () {
            return new ProjectLogsRepository(new ProjectLogs());
        });

        $this->app->bind(ProjectLogsResource::class, function () {
            return new ProjectLogsResource(new ProjectLogs());
        });

        $this->app->bind(ProjectSampleLogsRepository::class, function () {
            return new ProjectSampleLogsRepository(new ProjectSampleLogs());
        });

        $this->app->bind(ProjectSampleLogsResource::class, function () {
            return new ProjectSampleLogsResource(new ProjectSampleLogs());
        });

        $this->app->bind(RecipeAttributesRepository::class, function () {
            return new RecipeAttributesRepository(new RecipeAttributes());
        });

        $this->app->bind(RecipeAttributesResource::class, function () {
            return new RecipeAttributesResource(new RecipeAttributes());
        });

        $this->app->bind(RecipeRepository::class, function () {
            return new RecipeRepository(new Recipe());
        });

        $this->app->bind(RecipeResource::class, function () {
            return new RecipeResource(new Recipe());
        });

        $this->app->bind(RecipeItemsRepository::class, function () {
            return new RecipeItemsRepository(new RecipeItems());
        });

        $this->app->bind(RecipeItemsResource::class, function () {
            return new RecipeItemsResource(new RecipeItems());
        });

        $this->app->bind(RecipeProposalItemsRepository::class, function () {
            return new RecipeProposalItemsRepository(new RecipeProposalItems());
        });

        $this->app->bind(RecipeProposalItemsResource::class, function () {
            return new RecipeProposalItemsResource(new RecipeProposalItems());
        });

        $this->app->bind(RecipeProposalsRepository::class, function () {
            return new RecipeProposalsRepository(new RecipeProposals());
        });

        $this->app->bind(RecipeProposalsResource::class, function () {
            return new RecipeProposalsResource(new RecipeProposals());
        });

        $this->app->bind(RecipeImportSettingsRepository::class, function () {
            return new RecipeImportSettingsRepository(new RecipeImportSettings());
        });

        $this->app->bind(RecipeImportSettingsResource::class, function () {
            return new RecipeImportSettingsResource(new RecipeImportSettings());
        });

        $this->app->bind(RecipeSpecificationRepository::class, function () {
            return new RecipeSpecificationRepository(new RecipeSpecification());
        });

        $this->app->bind(RecipeSpecificationResource::class, function () {
            return new RecipeSpecificationResource(new RecipeSpecification());
        });

        $this->app->bind(RecipeSpecificationAttributesRepository::class, function () {
            return new RecipeSpecificationAttributesRepository(new RecipeSpecificationAttributes());
        });

        $this->app->bind(RecipeSpecificationAttributesResource::class, function () {
            return new RecipeSpecificationAttributesResource(new RecipeSpecificationAttributes());
        });

        $this->app->bind(PhaseRepository::class, function () {
            return new PhaseRepository(new Phase());
        });

        $this->app->bind(PhaseResource::class, function () {
            return new PhaseResource(new Phase());
        });

        $this->app->bind(PhaseRoleRepository::class, function () {
            return new PhaseRoleRepository(new PhaseRole());
        });

        $this->app->bind(PhaseRoleResource::class, function () {
            return new PhaseRoleResource(new PhaseRole());
        });

        $this->app->bind(FlowRepository::class, function () {
            return new FlowRepository(new Flow());
        });

        $this->app->bind(FlowResource::class, function () {
            return new FlowResource(new Flow());
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
            ProjectRepository::class,
            ProjectSampleAttributesRepository::class,
            ProjectSamplesRepository::class,
            RecipeAttributesRepository::class,
            RecipeRepository::class,
            RecipeItemsRepository::class,
            RecipeProposalItemsRepository::class,
            RecipeProposalsRepository::class,
            RecipeSpecificationRepository::class,
            RecipeSpecificationAttributesRepository::class,
            RecipeImportSettingsRepository::class,
            ProjectLogsRepository::class,
            ProjectSampleLogsRepository::class,
            PhaseRepository::class,
            FlowRepository::class,
            PhaseRoleRepository::class
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

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/' . $this->moduleNameLower);

        $sourcePath = module_path($this->moduleName, 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ], ['views', $this->moduleNameLower . '-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->moduleNameLower);
    }

    private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (\Config::get('view.paths') as $path) {
            if (is_dir($path . '/modules/' . $this->moduleNameLower)) {
                $paths[] = $path . '/modules/' . $this->moduleNameLower;
            }
        }
        return $paths;
    }
}
