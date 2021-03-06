<?php

namespace Modules\RD\Http\Controllers;

use Illuminate\Http\Request;
use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use Modules\RD\Repositories\RecipeImportSettingsRepository;
use Modules\RD\Transformers\RecipeImportSettingsResource;

class RecipeImportSettingsController extends ControllerService implements CheckPolicies
{

    protected $repository;
    protected $resource;

    public function __construct(RecipeImportSettingsRepository $repository, RecipeImportSettingsResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
        parent::__construct();
    }
}
