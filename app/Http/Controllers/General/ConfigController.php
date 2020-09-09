<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\ControllerService;
use App\Repositories\ConfigRepository;
use App\Resources\ConfigResource;

class ConfigController extends ControllerService
{

    protected $repository;
    protected $resource;

    public function __construct(ConfigRepository $repository, ConfigResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
        parent::__construct();
    }
}
