<?php

namespace Modules\Inventory\Http\Controllers;

use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use Illuminate\Http\Request;
use Modules\Inventory\Repositories\ProductImportSettingsRepository;
use Modules\Inventory\Transformers\ProductImportSettingsResource;

class ProductImportSettingsController extends ControllerService implements CheckPolicies
{

    protected $repository;
    protected $resource;

    public function __construct(ProductImportSettingsRepository $repository, ProductImportSettingsResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
        parent::__construct();
    }

}
