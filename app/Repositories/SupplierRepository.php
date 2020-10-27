<?php

namespace App\Repositories;

use App\Models\Contact;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class SupplierRepository extends RepositoryService
{

    public function findBy(array $searchCriteria = [])
    {
        if (!empty($searchCriteria['list']))
        {
            $this->queryBuilder->where('is_enabled', true);
        }

        if (!empty($searchCriteria['name'])) {
            $name = '%' . Arr::pull($searchCriteria, 'name') . '%';
            $searchCriteria['query_type'] = 'ILIKE';
            $searchCriteria['where']      = 'OR';
            $searchCriteria['name'] = $name;
        }

        return parent::findBy($searchCriteria);
    }
    public function store(array $data)
    {

        DB::transaction(function () use ($data)
        {
            parent::store($data);
            
            if(!empty($data['contacts'])) {
                foreach ($data['contacts'] as $contact) {
                    $new                = new Contact();
                    $new->entity_id     = $this->model->id;
                    $new->entity_type   = 'supplier';
                    $new->name          = $contact['name'];
                    $new->email         = $contact['email'];
                    $new->phone_number  = $contact['phone_number'];
                    $new->mobile        = $contact['mobile'];
                    $new->save();
                }
            }
        });
    }
    public function update($model, array $data)
    {

        DB::transaction(function () use ($data, $model)
        {    
            parent::update($model, $data);
            if(!empty($data['contacts'])) {
                foreach ($data['contacts'] as $contact) {
                    $contact['entity_id']      = $model->id;
                    $contact['entity_type']    = 'supplier';
                    
                    Contact::updateOrCreate(
                        ['id' => $contact['id']],
                        $contact
                    );
                }
            }
        });
    }

}
