<?php

namespace App\Providers;

// MODELS
use App\Models\Brand;
use App\Models\Attribute;
use App\Models\Category;
use App\Models\Specification;
use App\Models\SubSpecification;
use App\Models\Country;
use App\Models\Province;
use App\Models\Supplier;
use App\Models\SystemParameter;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductSpecification;
use App\Models\Employee;
use App\Models\Role;
use App\Models\Location;
use App\Models\User;
use App\Models\ProductFamily;
use App\Models\ProductFamilyAttribute;
use App\Models\ProductAvailability;


// REPOSITORIES
use App\Repositories\ProductSpecificationRepository;
use App\Repositories\ProductAttributeRepository;
use App\Repositories\ProductAvailabilityRepository;
use App\Repositories\SystemParameterRepository;
use App\Repositories\SupplierRepository;
use App\Repositories\ProvinceRepository;
use App\Repositories\CountryRepository;
use App\Repositories\SubSpecificationRepository;
use App\Repositories\SpecificationRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\AttributeRepository;
use App\Repositories\RoleRepository;
use App\Repositories\BrandRepository;
use App\Repositories\LocationRepository;
use App\Repositories\UserRepository;
use App\Repositories\EmployeeRepository;
use App\Repositories\ProductRepository;
use App\Repositories\ProductFamilyRepository;
use App\Repositories\ProductFamilyAttributeRepository;


// RESOURCES
use App\Resources\ProductSpecificationResource;
use App\Resources\ProductAttributeResource;
use App\Resources\ProductAvailabilityResource;
use App\Resources\SystemParameterResource;
use App\Resources\CountryResource;
use App\Resources\SupplierResource;
use App\Resources\ProvinceResource;
use App\Resources\SubSpecificationResource;
use App\Resources\SpecificationResource;
use App\Resources\CategoryResource;
use App\Resources\AttributeResource;
use App\Resources\RoleResource;
use App\Resources\UserResource;
use App\Resources\BrandResource;
use App\Resources\LocationResource;
use Illuminate\Support\ServiceProvider;
use App\Resources\EmployeeResource;
use App\Resources\ProductCategoryResource;
use App\Resources\ProductResource;
use App\Resources\WarehouseResource;
use App\Resources\ProductFamilyResource;
use App\Resources\ProductFamilyAttributeResource;


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
        $this->app->bind(ProductSpecificationRepository::class, function () {
            return new ProductSpecificationRepository(new ProductSpecification());
        });

        $this->app->bind(ProductSpecificationResource::class, function () {
            return new ProductSpecificationResource(new ProductSpecification());
        });

        $this->app->bind(ProductAttributeRepository::class, function () {
            return new ProductAttributeRepository(new ProductAttribute());
        });

        $this->app->bind(ProductAttributeResource::class, function () {
            return new ProductAttributeResource(new ProductAttribute());
        });

        $this->app->bind(ProductRepository::class, function () {
            return new ProductRepository(new Product());
        });

        $this->app->bind(ProductResource::class, function () {
            return new ProductResource(new Product());
        });

        $this->app->bind(SystemParameterRepository::class, function () {
            return new SystemParameterRepository(new SystemParameter());
        });

        $this->app->bind(SystemParameterResource::class, function () {
            return new SystemParameterResource(new SystemParameter());
        });

        $this->app->bind(SupplierRepository::class, function () {
            return new SupplierRepository(new Supplier());
        });

        $this->app->bind(SupplierResource::class, function () {
            return new SupplierResource(new Supplier());
        });

        $this->app->bind(ProvinceRepository::class, function () {
            return new ProvinceRepository(new Province());
        });

        $this->app->bind(ProvinceResource::class, function () {
            return new ProvinceResource(new Province());
        });

        $this->app->bind(CountryRepository::class, function () {
            return new CountryRepository(new Country());
        });

        $this->app->bind(CountryResource::class, function () {
            return new CountryResource(new Country());
        });

        $this->app->bind(SubSpecificationRepository::class, function () {
            return new SubSpecificationRepository(new SubSpecification());
        });

        $this->app->bind(SubSpecificationResource::class, function () {
            return new SubSpecificationResource(new SubSpecification());
        });

        $this->app->bind(SpecificationRepository::class, function () {
            return new SpecificationRepository(new Specification());
        });

        $this->app->bind(SpecificationResource::class, function () {
            return new SpecificationResource(new Specification());
        });

        $this->app->bind(CategoryRepository::class, function () {
            return new CategoryRepository(new Category());
        });

        $this->app->bind(CategoryResource::class, function () {
            return new CategoryResource(new Category());
        });

        $this->app->bind(AttributeRepository::class, function () {
            return new AttributeRepository(new Attribute());
        });

        $this->app->bind(AttributeResource::class, function () {
            return new AttributeResource(new Attribute());
        });

        $this->app->bind(UserRepository::class, function () {
            return new UserRepository(new User());
        });

        $this->app->bind(UserResource::class, function () {
            return new UserResource(new User());
        });

        $this->app->bind(RoleRepository::class, function () {
            return new RoleRepository(new Role());
        });

        $this->app->bind(RoleResource::class, function () {
            return new RoleResource(new Role());
        });

        $this->app->bind(EmployeeRepository::class, function () {
            return new EmployeeRepository(new Employee());
        });

        $this->app->bind(EmployeeResource::class, function () {
            return new EmployeeResource(new Employee());
        });

        $this->app->bind(ProductRepository::class, function () {
            return new ProductRepository(new Product());
        });

        $this->app->bind(ProductResource::class, function () {
            return new ProductResource(new Product());
        });

        $this->app->bind(BrandRepository::class, function () {
            return new BrandRepository(new Brand());
        });

        $this->app->bind(BrandResource::class, function () {
            return new BrandResource(new Brand());
        });

        $this->app->bind(LocationRepository::class, function () {
            return new LocationRepository(new Location());
        });

        $this->app->bind(LocationResource::class, function () {
            return new LocationResource(new Location());
        });

        $this->app->bind(ProductFamilyRepository::class, function () {
            return new ProductFamilyRepository(new ProductFamily());
        });

        $this->app->bind(ProductFamilyResource::class, function () {
            return new ProductFamilyResource(new ProductFamily());
        });

        $this->app->bind(ProductFamilyAttributeRepository::class, function () {
            return new ProductFamilyAttributeRepository(new ProductFamilyAttribute());
        });

        $this->app->bind(ProductFamilyAttributeResource::class, function () {
            return new ProductFamilyAttributeResource(new ProductFamilyAttribute());
        });

        $this->app->bind(ProductAvailabilityRepository::class, function () {
            return new ProductAvailabilityRepository(new ProductAvailability());
        });

        $this->app->bind(ProductAvailabilityResource::class, function () {
            return new ProductAvailabilityResource(new ProductAvailability());
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
            RoleRepository::class,
            EmployeeRepository::class,
            ProductRepository::class,
            BrandRepository::class,
            LocationRepository::class,
            WarehouseRepository::class,
            ProductAvailabilityRepository::class,
            AttributeRepository::class,
            CategoryRepository::class,
            SpecificationRepository::class,
            SubSpecificationRepository::class,
            CountryRepository::class,
            ProvinceRepository::class,
            SupplierRepository::class,
            SystemParameterRepository::class,
            ProductAttributeRepository::class,
            ProductFamilyRepository::class,
            ProductSpecificationRepository::class,
            ProductFamilyAttributeRepository::class,
            ProductAvailabilityRepository::class,
        ];
    }
}
