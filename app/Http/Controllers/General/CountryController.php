<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\ControllerService;
use App\Repositories\CountryRepository;
use App\Resources\CountryResource;

class CountryController extends ControllerService
{
    protected $repository;
    protected $resource;

    public function __construct(CountryRepository $repository, CountryResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
    }
}
