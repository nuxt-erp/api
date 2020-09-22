<?php

namespace Modules\RD\Repositories;
use Illuminate\Support\Facades\DB;
use App\Repositories\RepositoryService;
use Illuminate\Support\Arr;

class RecipeRepository extends RepositoryService
{
    public function store(array $data)
    {
        DB::transaction(function () use ($data)
        {
            $user = auth()->user();
            $data['author_id'] = $user->id;

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
