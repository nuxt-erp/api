<?php

namespace App\Http\Controllers\Inventory;


use App\Http\Controllers\ControllerService;
use App\Repositories\TransferRepository;
use App\Resources\TransferResource;

class TransferController extends ControllerService
{
    protected $repository;
    protected $resource;

    public function __construct(TransferRepository $repository, TransferResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
    }

    public function finish($Transfer_id)
    {
        $status = $this->repository->finish($Transfer_id);
        return $this->setStatusCode(201)->respondWithObject($this->repository->model, $this->resource);
    }
}
