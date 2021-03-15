<?php

namespace App\Providers;

use App\Models\Contact;
use App\Models\Country;
use App\Models\CronLog;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\CustomerTag;
use App\Models\Location;
use App\Models\Parameter;
use App\Models\ParameterType;
use App\Models\Province;
use App\Models\Role;
use App\Models\SalesRep;
use App\Models\SettingsImages;
use App\Models\Supplier;
use App\Models\Tag;
use App\Models\TaxRule;
use App\Models\TaxRuleComponent;
use App\Models\TaxRuleScope;
use App\Models\User;
// Policies
use App\Policies\ContactPolicy;
use App\Policies\CountryPolicy;
use App\Policies\CronLogPolicy;
use App\Policies\CurrencyPolicy;
use App\Policies\CustomerPolicy;
use App\Policies\CustomerTagPolicy;
use App\Policies\LocationPolicy;
use App\Policies\ParameterPolicy;
use App\Policies\ParameterTypePolicy;
use App\Policies\ProvincePolicy;
use App\Policies\RolePolicy;
use App\Policies\SalesRepPolicy;
use App\Policies\SettingsImagesPolicy;
use App\Policies\SupplierPolicy;
use App\Policies\TagPolicy;
use App\Policies\TaxRuleComponentPolicy;
use App\Policies\TaxRulePolicy;
use App\Policies\TaxRuleScopePolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
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
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Role::class, RolePolicy::class);
        Gate::policy(Parameter::class, ParameterPolicy::class);
        Gate::policy(Location::class, LocationPolicy::class);
        Gate::policy(Country::class, CountryPolicy::class);
        Gate::policy(Province::class, ProvincePolicy::class);
        Gate::policy(Supplier::class, SupplierPolicy::class);
        Gate::policy(Customer::class, CustomerPolicy::class);
        Gate::policy(Contact::class, ContactPolicy::class);
        Gate::policy(ParameterType::class, ParameterTypePolicy::class);
        Gate::policy(TaxRule::class, TaxRulePolicy::class);
        Gate::policy(TaxRuleComponent::class, TaxRuleComponentPolicy::class);
        Gate::policy(TaxRuleScope::class, TaxRuleScopePolicy::class);
        Gate::policy(SalesRep::class, SalesRepPolicy::class);
        Gate::policy(Tag::class, TagPolicy::class);
        Gate::policy(CustomerTag::class, CustomerTagPolicy::class);
        Gate::policy(SettingsImages::class, SettingsImagesPolicy::class);
        Gate::policy(CronLog::class, CronLogPolicy::class);
        Gate::policy(Currency::class, CurrencyPolicy::class);
    }
}
