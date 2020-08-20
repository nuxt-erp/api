<?php

namespace App\Providers;

// MODELS
use App\Models\Config;
use App\Models\Country;
use App\Models\Location;
use App\Models\Parameter;
use App\Models\Province;
use App\Models\Role;
use App\Models\Supplier;
use App\Models\User;
// REPOSITORIES
use App\Repositories\ConfigRepository;
use App\Repositories\CountryRepository;
use App\Repositories\LocationRepository;
use App\Repositories\ParameterRepository;
use App\Repositories\ProvinceRepository;
use App\Repositories\RoleRepository;
use App\Repositories\SupplierRepository;
use App\Repositories\UserRepository;
// RESOURCES
use App\Resources\ConfigResource;
use App\Resources\CountryResource;
use App\Resources\LocationResource;
use App\Resources\ParameterResource;
use App\Resources\ProvinceResource;
use App\Resources\RoleResource;
use App\Resources\SupplierResource;
use App\Resources\UserResource;

use Illuminate\Support\ServiceProvider;

class RepositoriesServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(UserRepository::class, function () {
            return new UserRepository(new User());
        });

        $this->app->bind(UserResource::class, function () {
            return new UserResource(new User());
        });

        $this->app->bind(ConfigRepository::class, function () {
            return new ConfigRepository(new Config());
        });

        $this->app->bind(ConfigResource::class, function () {
            return new ConfigResource(new Config());
        });

        $this->app->bind(RoleRepository::class, function () {
            return new RoleRepository(new Role());
        });

        $this->app->bind(RoleResource::class, function () {
            return new RoleResource(new Role());
        });

        $this->app->bind(ParameterRepository::class, function () {
            return new ParameterRepository(new Parameter());
        });

        $this->app->bind(ParameterResource::class, function () {
            return new ParameterResource(new Parameter());
        });

        $this->app->bind(LocationRepository::class, function () {
            return new LocationRepository(new Location());
        });

        $this->app->bind(LocationResource::class, function () {
            return new LocationResource(new Location());
        });

        $this->app->bind(CountryRepository::class, function () {
            return new CountryRepository(new Country());
        });

        $this->app->bind(CountryResource::class, function () {
            return new CountryResource(new Country());
        });

        $this->app->bind(ProvinceRepository::class, function () {
            return new ProvinceRepository(new Province());
        });

        $this->app->bind(ProvinceResource::class, function () {
            return new ProvinceResource(new Province());
        });

        $this->app->bind(SupplierRepository::class, function () {
            return new SupplierRepository(new Supplier());
        });

        $this->app->bind(SupplierResource::class, function () {
            return new SupplierResource(new Supplier());
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
            UserRepository::class,
            ConfigRepository::class,
            RoleRepository::class,
            ParameterRepository::class,
            LocationRepository::class,
            CountryRepository::class,
            ProvinceRepository::class,
            SupplierRepository::class
        ];
    }
}
