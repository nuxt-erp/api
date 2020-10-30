<?php

namespace App\Http\Controllers\General;

use Illuminate\Http\Request;
use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use App\Repositories\TagRepository;
use App\Resources\TagResource;

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
