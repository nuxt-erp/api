<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\ControllerService;
use App\Repositories\SaleRepository;
use App\Resources\SaleResource;
use Illuminate\Http\Request;

class SaleController extends ControllerService
{
    protected $repository;
    protected $resource;

    public function __construct(SaleRepository $repository, SaleResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
    }

    public function importShopify()
    {
        $data = $this->repository->importShopify();
        return $this->setStatusCode(201)->respondWithArray(['total' => $data]);
    }

    public function remove(Request $request) {
        $this->repository->remove($request->id);
        return $this->respond(['ok' => true]);
    }

}
