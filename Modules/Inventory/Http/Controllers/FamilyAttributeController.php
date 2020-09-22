<?php

namespace Modules\Inventory\Http\Controllers;

use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use Modules\Inventory\Repositories\FamilyAttributeRepository;
use Modules\Inventory\Transformers\FamilyAttributeResource;

class FamilyAttributeController extends ControllerService implements CheckPolicies
{
    protected $repository;
    protected $resource;

    public function __construct(FamilyAttributeRepository $repository, FamilyAttributeResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
        parent::__construct();
    }
}