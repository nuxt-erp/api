<?php

namespace Modules\Inventory\Http\Controllers;

use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use Modules\Inventory\Repositories\AttributeRepository;
use Modules\Inventory\Transformers\AttributeResource;

class AttributeController extends ControllerService implements CheckPolicies
{

    protected $repository;
    protected $resource;

    public function __construct(AttributeRepository $repository, AttributeResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
    }
}
