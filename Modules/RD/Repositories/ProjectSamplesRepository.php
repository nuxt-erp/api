<?php

namespace Modules\RD\Repositories;
use Illuminate\Support\Facades\DB;
use App\Repositories\RepositoryService;
use Illuminate\Support\Arr;

class ProjectSamplesRepository extends RepositoryService
{
    public function findBy(array $searchCriteria = [])
    {        
        return parent::findBy($searchCriteria);
    }
    public function store(array $data)
    {
        DB::transaction(function () use ($data)
        {
            $user = auth()->user();
            $data['author_id'] = $user->id;
            
            parent::store($data);
            
        });
    
        if (Arr::has($data, 'attribute_ids')) {
            lad($data['attribute_ids']);
            $this->model->attributes()->sync($data['attribute_ids']);
        }

    }
}
