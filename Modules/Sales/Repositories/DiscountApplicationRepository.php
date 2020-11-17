<?php

namespace Modules\Sales\Repositories;

use App\Repositories\RepositoryService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Modules\Sales\Entities\DiscountRule;

class DiscountApplicationRepository extends RepositoryService
{
    public function findBy(array $searchCriteria = [])
    {
        return parent::findBy($searchCriteria);
    }

    public function store($data)
    {
        DB::transaction(function () use ($data)
        {          

            parent::store($data);
        });
    }

    public function update($model, array $data)
    {
        DB::transaction(function () use ($data, $model)
        {
            parent::update($model, $data);
        });
    }

    public function delete($model)
    {
        $result = true;
        DB::transaction(function () use ($model, &$result)
        {
            foreach($model->discount_rules as $rule) {
                $deleted = DiscountRule::find($rule->id)->delete();
                if(!$deleted) {
                    $result = false;
                }
            }
            parent::delete($model);

        });
        return $result;

    }
}
