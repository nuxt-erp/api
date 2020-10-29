<?php

namespace App\Http\Controllers\General;

use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use App\Repositories\TaxRuleRepository;
use App\Resources\TaxRuleResource;

class TaxRuleController extends ControllerService implements CheckPolicies
{

    protected $repository;
    protected $resource;

    public function __construct(TaxRuleRepository $repository, TaxRuleResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
        parent::__construct();
    }
    public function getScopes(array $searchCriteria = []){
        $constants = $this->repository->model->getStatuses();
        $keyValue   = [];
        $i          = 0;

        foreach($constants as $key => $constant) {
            if($key === 'SCOPES') {
                foreach($constant as $key => $value) {
                    $keyValue[$i]['label'] = $value;
                    $keyValue[$i]['value'] = strtolower($key);
                    $i++;
                }

            }
        }
        return $this->sendArray($keyValue);
    }
    public function getComputations(array $searchCriteria = []){
        $constants = $this->repository->model->getStatuses();
        $keyValue   = [];
        $i          = 0;

        foreach($constants as $key => $constant) {
            if($key === 'COMPUTATIONS') {
                foreach($constant as $key => $value) {
                    $keyValue[$i]['label'] = $value;
                    $keyValue[$i]['value'] = strtolower($key);
                    $i++;
                }

            }
        }
        return $this->sendArray($keyValue);
    }
}
