<?php

namespace Modules\Production\Providers;

use Illuminate\Support\ServiceProvider;

// REPOSITORY
use Modules\Production\Repositories\ActionRepository;
use Modules\Production\Repositories\FlowRepository;
use Modules\Production\Repositories\FlowActionRepository;
use Modules\Production\Repositories\MachineRepository;
use Modules\Production\Repositories\OperationRepository;
use Modules\Production\Repositories\OperationResultRepository;
use Modules\Production\Repositories\PhaseRepository;
use Modules\Production\Repositories\ProductionRepository;

// RESOURCES
use Modules\Production\Transformers\ActionResource;
use Modules\Production\Transformers\FlowResource;
use Modules\Production\Transformers\FlowActionResource;
use Modules\Production\Transformers\MachineResource;
use Modules\Production\Transformers\OperationResource;
use Modules\Production\Transformers\OperationResultResource;
use Modules\Production\Transformers\PhaseResource;
use Modules\Production\Transformers\ProductionResource;

// MODELS
use Modules\Production\Entities\Action;
use Modules\Production\Entities\Flow;
use Modules\Production\Entities\FlowAction;
use Modules\Production\Entities\Machine;
use Modules\Production\Entities\Operation;
use Modules\Production\Entities\OperationResult;
use Modules\Production\Entities\Phase;
use Modules\Production\Entities\Production;


class ProductionServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected $moduleName = 'Production';

    /**
     * @var string $moduleNameLower
     */
    protected $moduleNameLower = 'production';

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

        $this->app->bind(ActionRepository::class, function () {
            return new ActionRepository(new Action());
        });
        $this->app->bind(ActionResource::class, function () {
            return new ActionResource(new Action());
        });
        $this->app->bind(FlowRepository::class, function () {
            return new FlowRepository(new Flow());
        });
        $this->app->bind(FlowResource::class, function () {
            return new FlowResource(new Flow());
        });
        $this->app->bind(FlowActionRepository::class, function () {
            return new FlowActionRepository(new FlowAction());
        });
        $this->app->bind(FlowActionResource::class, function () {
            return new FlowActionResource(new FlowAction());
        });
        $this->app->bind(MachineRepository::class, function () {
            return new MachineRepository(new Machine());
        });
        $this->app->bind(MachineResource::class, function () {
            return new MachineResource(new Machine());
        });
        $this->app->bind(OperationRepository::class, function () {
            return new OperationRepository(new Operation());
        });
        $this->app->bind(OperationResource::class, function () {
            return new OperationResource(new Operation());
        });
        $this->app->bind(OperationResultRepository::class, function () {
            return new OperationResultRepository(new OperationResult());
        });
        $this->app->bind(OperationResultResource::class, function () {
            return new OperationResultResource(new OperationResult());
        });
        $this->app->bind(PhaseRepository::class, function () {
            return new PhaseRepository(new Phase());
        });
        $this->app->bind(PhaseResource::class, function () {
            return new PhaseResource(new Phase());
        });
        $this->app->bind(ProductionRepository::class, function () {
            return new ProductionRepository(new Production());
        });
        $this->app->bind(ProductionResource::class, function () {
            return new ProductionResource(new Production());
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
            ActionRepository::class,
            FlowRepository::class,
            FlowActionRepository::class,
            MachineRepository::class,
            OperationRepository::class,
            OperationResultRepository::class,
            PhaseRepository::class,
            ProductionRepository::class
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
