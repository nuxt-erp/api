<?php

namespace Modules\ExpensesApproval\Repositories;

use App\Repositories\RepositoryService;
use Illuminate\Support\Arr;
use Modules\ExpensesApproval\Entities\Subcategory;

class CategoryRepository extends RepositoryService
{

    public function findBy(array $searchCriteria = [])
    {          
        if (!empty($searchCriteria['not_finished'])) {
            $this->queryBuilder->where('is_finished', false);
        }
        
        return parent::findBy($searchCriteria);
    }

    public function store($data)
    {
        if(isset($data['is_finished']) && $data['is_finished']){
            $data['finished_at'] = now();
        }
                
        parent::store($data);
        
        if($this->model) {
            // CREATE DEFAULT SUBCATEGORY 'GENERAL' WHEN CREATING NEW CATEGORY
            Subcategory::create([
                'expenses_category_id'  => $this->model->id,
                'name'                  => 'General'
            ]);
        }
        
    }

    public function update($model, array $data)
    {        
        if(isset($data['is_finished']) && $data['is_finished']){
            $data['finished_at'] = now();
        } else {
            $data['finished_at'] = null;
        }
        
        parent::update($model, $data); 
    }

    
}
