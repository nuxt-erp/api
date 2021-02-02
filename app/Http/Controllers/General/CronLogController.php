<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\ControllerService;
use App\Repositories\CronLogRepository;
use App\Resources\CronLogResource;

class CronLogController extends ControllerService
{
    protected $repository;
    protected $resource;

    public function __construct(CronLogRepository $repository, CronLogResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;

        parent::__construct();
    }
}
