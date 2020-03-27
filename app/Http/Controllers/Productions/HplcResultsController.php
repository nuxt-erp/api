<?php

namespace App\Http\Controllers\Productions;

use App\Http\Controllers\ControllerService;
use App\Repositories\HplcResultsRepository;
use App\Resources\HplcResultsResource;

class HplcResultsController extends ControllerService
{

    protected $repository;
    protected $resource;

    public function __construct(HplcResultsRepository $repository, HplcResultsResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
    }
}
