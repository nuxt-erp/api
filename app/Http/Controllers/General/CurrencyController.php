<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\ControllerService;
use App\Repositories\CurrencyRepository;
use App\Resources\CurrencyResource;

class CurrencyController extends ControllerService
{
    protected $repository;
    protected $resource;

    public function __construct(CurrencyRepository $repository, CurrencyResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;

        parent::__construct();
    }
}
