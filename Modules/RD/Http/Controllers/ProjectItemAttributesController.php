<?php

namespace Modules\RD\Http\Controllers;

use Illuminate\Http\Request;
use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use Modules\RD\Repositories\ProjectItemAttributesRepository;
use Modules\RD\Transformers\ProjectItemAttributesResource;

class ProjectItemAttributesController extends ControllerService implements CheckPolicies
{

    protected $repository;
    protected $resource;

    public function __construct(ProjectItemAttributesRepository $repository, ProjectItemAttributesResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
        parent::__construct();
    }
}
