<?php

namespace App\Http\Controllers\General;

use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use App\Repositories\TaxRuleRepository;
use App\Resources\TaxRuleResource;

class TaxRuleController extends ControllerService implements CheckPolicies
{

    protected $repository;
    protected $resource;

    public function __construct(TaxRuleRepository $repository, TaxRuleResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
        parent::__construct();
    }
}
