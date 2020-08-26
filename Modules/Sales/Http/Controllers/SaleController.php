<?php

namespace Modules\Sales\Http\Controllers;

use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use Modules\Sales\Repositories\SaleRepository;
use Modules\Sales\Transformers\SaleResource;
use Illuminate\Http\Request;

class SaleController extends ControllerService implements CheckPolicies
{

    protected $repository;
    protected $resource;

    public function __construct(SaleRepository $repository, SaleResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
    }

    public function importFromShopify(){

        $api    = resolve('Shopify\API');
        $api->syncOrders();
    }
}
