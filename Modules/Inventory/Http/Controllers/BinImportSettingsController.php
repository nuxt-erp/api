<?php

namespace Modules\Inventory\Http\Controllers;

use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use Illuminate\Http\Request;
use Modules\Inventory\Repositories\BinImportSettingsRepository;
use Modules\Inventory\Transformers\BinImportSettingsResource;

class BinImportSettingsController extends ControllerService implements CheckPolicies
{

    protected $repository;
    protected $resource;

    public function __construct(BinImportSettingsRepository $repository, BinImportSettingsResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
        parent::__construct();
    }

}
