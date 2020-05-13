<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\ControllerService;
use App\Repositories\SaleRepository;
use App\Resources\SaleResource;
use App\Http\Controllers\ResponseTrait;

class SaleController extends ControllerService
{
    protected $repository;
    protected $resource;

    use ResponseTrait;

    public function __construct(SaleRepository $repository, SaleResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
    }

    public function importShopify()
    {
        $data = $this->repository->importShopify();
        return $this->setStatusCode(201)->respondWithArray($data);
    }

}
