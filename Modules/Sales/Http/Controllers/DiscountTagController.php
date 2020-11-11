<?php

namespace Modules\Sales\Http\Controllers;

use Illuminate\Http\Request;
use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use Modules\Sales\Repositories\DiscountTagRepository;
use Modules\Sales\Transformers\DiscountTagResource;

class DiscountTagController extends ControllerService implements CheckPolicies
{

    protected $repository;
    protected $resource;

    public function __construct(DiscountTagRepository $repository, DiscountTagResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
        parent::__construct();
    }
}
