<?php

namespace App\Http\Controllers\General;

use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use App\Repositories\TaxRuleScopeRepository;
use App\Resources\TaxRuleScopeResource;

class TaxRuleScopeController extends ControllerService implements CheckPolicies
{

    protected $repository;
    protected $resource;

    public function __construct(TaxRuleScopeRepository $repository, TaxRuleScopeResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
        parent::__construct();
    }
}
