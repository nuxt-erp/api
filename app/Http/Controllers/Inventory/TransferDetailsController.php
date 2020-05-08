<?php

namespace App\Http\Controllers\Inventory;


use App\Http\Controllers\ControllerService;
use App\Repositories\TransferDetailsRepository;
use App\Resources\TransferDetailsResource;

class TransferDetailsController extends ControllerService
{
    protected $repository;
    protected $resource;

    public function __construct(TransferDetailsRepository $repository, TransferDetailsResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
    }

}
