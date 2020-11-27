<?php

namespace Modules\RD\Repositories;
use Illuminate\Support\Facades\DB;
use App\Repositories\RepositoryService;
use Illuminate\Support\Arr;
use Modules\RD\Entities\Recipe;

class RecipeRepository extends RepositoryService
{
    public function findBy(array $searchCriteria = [])
    {
        if(!empty($searchCriteria['list'])) {
            $this->queryBuilder->orderBy('name', 'asc');
        }

        if(empty($searchCriteria['version'])){
            $this->queryBuilder->where('last_version', TRUE);
        }

        if(!empty($searchCriteria['order_by'])) {
            $this->queryBuilder->orderBy('name', $searchCriteria['order_by']);
        }
        return parent::findBy($searchCriteria);

    }

    public function store(array $data)
    {
        //@todo update last_version = false in the old recipes
        DB::transaction(function () use ($data)
        {
            $user = auth()->user();
            $data['author_id']      = $user->id;
            $data['status']         = Recipe::NEW_RECIPE;
            $data['last_version']   = TRUE;


            parent::store($data);

        });

        if (Arr::has($data, 'attribute_ids')) {
            $this->model->attributes()->sync($data['attribute_ids']);
        }

    }

    public function update($model, array $data)
    {

        DB::transaction(function () use ($data, $model)
        {
            if (Arr::has($data, 'attribute_ids')) {
                $model->attributes()->sync($data['attribute_ids']);
            }

            unset($data['attribute_names']);
            unset($data['attribute_ids']);

            parent::update($model, $data);
        });
    }

}
