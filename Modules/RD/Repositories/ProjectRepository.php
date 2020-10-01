<?php

namespace Modules\RD\Repositories;
use Illuminate\Support\Facades\DB;
use App\Repositories\RepositoryService;
use Illuminate\Support\Arr;

class ProjectRepository extends RepositoryService
{

    public function store(array $data)
    {
        DB::transaction(function () use ($data)
        {
            $user = auth()->user();
            $data['author_id'] = $user->id;

            if(!empty($data['closed'])){
                $data['closed_at'] = $data['closed'] ? now() : null;
                unset($data['closed']);
            }

            if(!empty($data['started'])){

                $data['start_at'] = $data['started'] ? now() : null;
                unset($data['started']);
            }
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
            parent::update($model, $data);
        });

    }


}
