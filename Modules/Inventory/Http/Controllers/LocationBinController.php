<?php

namespace Modules\Inventory\Http\Controllers;

use App\Http\Controllers\ControllerService;
use Modules\Inventory\Repositories\LocationBinRepository;
use Modules\Inventory\Transformers\LocationBinResource;

class LocationBinController extends ControllerService
{

    protected $repository;
    protected $resource;

    public function __construct(LocationBinRepository $repository, LocationBinResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
        parent::__construct();
    }
}
