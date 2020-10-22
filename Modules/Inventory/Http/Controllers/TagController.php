<?php

namespace Modules\Inventory\Http\Controllers;

use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use Modules\Inventory\Repositories\TagRepository;
use Modules\Inventory\Transformers\TagResource;

class TagController extends ControllerService implements CheckPolicies
{

    protected $repository;
    protected $resource;

    public function __construct(TagRepository $repository, TagResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
        parent::__construct();
    }
}