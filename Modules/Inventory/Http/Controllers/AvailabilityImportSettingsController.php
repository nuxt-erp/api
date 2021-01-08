<?php

namespace Modules\Inventory\Http\Controllers;

use Illuminate\Http\Request;
use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use Modules\Inventory\Repositories\AvailabilityImportSettingsRepository;
use Modules\Inventory\Transformers\AvailabilityImportSettingsResource;

class AvailabilityImportSettingsController extends ControllerService implements CheckPolicies
{

    protected $repository;
    protected $resource;

    public function __construct(AvailabilityImportSettingsRepository $repository, AvailabilityImportSettingsResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
        parent::__construct();
    }
}
