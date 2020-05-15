<?php

namespace App\Http\Controllers\Purchases;

use App\Http\Controllers\ControllerService;
use App\Repositories\PurchaseRepository;
use App\Resources\PurchaseResource;
use Illuminate\Http\Request;

class PurchaseController extends ControllerService
{

    protected $repository;
    protected $resource;

    public function __construct(PurchaseRepository $repository, PurchaseResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
    }

    public function remove(Request $request) {
        $this->repository->remove($request->id);
        return $this->respond(['ok' => true]);
    }
}
