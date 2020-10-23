<?php

namespace App\Http\Controllers\General;

use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use App\Repositories\TaxRuleComponentRepository;
use App\Resources\TaxRuleComponentResource;

class TaxRuleComponentController extends ControllerService implements CheckPolicies
{

    protected $repository;
    protected $resource;

    public function __construct(TaxRuleComponentRepository $repository, TaxRuleComponentResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
        parent::__construct();
    }
}
