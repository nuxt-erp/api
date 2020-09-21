<?php

namespace Modules\RD\Providers;

use Illuminate\Support\ServiceProvider;
// REPOSITORY
use Modules\RD\Repositories\ProjectRepository;
use Modules\RD\Repositories\ProjectAttributesRepository;
use Modules\RD\Repositories\ProjectSamplesRepository;
use Modules\RD\Repositories\RecipeAttributesRepository;
use Modules\RD\Repositories\RecipeItemsRepository;
use Modules\RD\Repositories\RecipeProposalItemsRepository;
use Modules\RD\Repositories\RecipeProposalsRepository;
use Modules\RD\Repositories\RecipeRepository;
// RESOURCES
use Modules\RD\Transformers\ProjectResource;
use Modules\RD\Transformers\ProjectAttributesResource;
use Modules\RD\Transformers\ProjectSamplesResource;
use Modules\RD\Transformers\RecipeAttributesResource;
use Modules\RD\Transformers\RecipeItemsResource;
use Modules\RD\Transformers\RecipeProposalItemsResource;
use Modules\RD\Transformers\RecipeProposalsResource;
use Modules\RD\Transformers\RecipeResource;
// MODELS
use Modules\RD\Entities\Project;
use Modules\RD\Entities\ProjectAttributes;
use Modules\RD\Entities\ProjectSamples;
use Modules\RD\Entities\RecipeAttributes;
use Modules\RD\Entities\RecipeItems;
use Modules\RD\Entities\RecipeProposalItems;
use Modules\RD\Entities\RecipeProposals;
use Modules\RD\Entities\Recipe;

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
        
        $this->app->bind(ProjectAttributesRepository::class, function () {
            return new ProjectAttributesRepository(new ProjectAttributes());
        });

        $this->app->bind(ProjectAttributesResource::class, function () {
            return new ProjectAttributesResource(new ProjectAttributes());
        });

        $this->app->bind(ProjectSamplesRepository::class, function () {
            return new ProjectSamplesRepository(new ProjectSamples());
        });

        $this->app->bind(ProjectSamplesResource::class, function () {
            return new ProjectSamplesResource(new ProjectSamples());
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
            ProjectAttributesRepository::class,
            ProjectSamplesRepository::class,
            RecipeAttributesRepository::class,
            RecipeRepository::class,
            RecipeItemsRepository::class,
            RecipeProposalItemsRepository::class,
            RecipeProposalsRepository::class
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
