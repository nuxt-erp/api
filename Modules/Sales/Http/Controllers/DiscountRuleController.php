<?php

namespace Modules\Sales\Http\Controllers;

use Illuminate\Http\Request;
use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use Modules\Sales\Repositories\DiscountRuleRepository;
use Modules\Sales\Transformers\DiscountRuleResource;

class DiscountRuleController extends ControllerService implements CheckPolicies
{

    protected $repository;
    protected $resource;

    public function __construct(DiscountRuleRepository $repository, DiscountRuleResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
        parent::__construct();
    }
}
