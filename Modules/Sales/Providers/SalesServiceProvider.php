<?php

namespace Modules\Sales\Providers;

use Illuminate\Support\ServiceProvider;
//Models
use Modules\Sales\Entities\Discount;
use Modules\Sales\Entities\DiscountApplication;
use Modules\Sales\Entities\DiscountRule;
use Modules\Sales\Entities\DiscountTag;
use Modules\Sales\Entities\Sale;
use Modules\Sales\Entities\SaleDetails;
//Repos
use Modules\Sales\Repositories\DiscountApplicationRepository;
use Modules\Sales\Repositories\DiscountRepository;
use Modules\Sales\Repositories\DiscountRuleRepository;
use Modules\Sales\Repositories\DiscountTagRepository;
use Modules\Sales\Repositories\SaleDetailsRepository;
use Modules\Sales\Repositories\SaleRepository;
//Resources
use Modules\Sales\Transformers\DiscountApplicationResource;
use Modules\Sales\Transformers\DiscountResource;
use Modules\Sales\Transformers\DiscountRuleResource;
use Modules\Sales\Transformers\DiscountTagResource;
use Modules\Sales\Transformers\SaleDetailsResource;
use Modules\Sales\Transformers\SaleResource;

class SalesServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected $moduleName = 'Sales';

    /**
     * @var string $moduleNameLower
     */
    protected $moduleNameLower = 'sales';

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
        $this->app->register(AuthServiceProvider::class);

        $this->app->bind(SaleRepository::class, function () {
            return new SaleRepository(new Sale());
        });
        $this->app->bind(SaleResource::class, function () {
            return new SaleResource(new Sale());
        });

        $this->app->bind(SaleDetailsRepository::class, function () {
            return new SaleDetailsRepository(new SaleDetails());
        });
        $this->app->bind(SaleDetailsResource::class, function () {
            return new SaleDetailsResource(new SaleDetails());
        });

        $this->app->bind(DiscountRepository::class, function () {
            return new DiscountRepository(new Discount());
        });
        $this->app->bind(DiscountResource::class, function () {
            return new DiscountResource(new Discount());
        });

        $this->app->bind(DiscountApplicationRepository::class, function () {
            return new DiscountApplicationRepository(new DiscountApplication());
        });
        $this->app->bind(DiscountApplicationResource::class, function () {
            return new DiscountApplicationResource(new DiscountApplication());
        });

        $this->app->bind(DiscountRuleRepository::class, function () {
            return new DiscountRuleRepository(new DiscountRule());
        });
        $this->app->bind(DiscountRuleResource::class, function () {
            return new DiscountRuleResource(new DiscountRule());
        });

        $this->app->bind(DiscountTagRepository::class, function () {
            return new DiscountTagRepository(new DiscountTag());
        });
        $this->app->bind(DiscountTagResource::class, function () {
            return new DiscountTagResource(new DiscountTag());
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
            SaleRepository::class,
            SaleDetailsRepository::class,
            DiscountRepository::class,
            DiscountApplicationRepository::class,
            DiscountRuleRepository::class,
            DiscountTagRepository::class
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
