<?php

namespace Modules\Inventory\Repositories;
use App\Repositories\RepositoryService;
use Illuminate\Support\Arr;
use Auth;
use Illuminate\Support\Facades\DB;


class TransferRepository extends RepositoryService
{


    protected $availabilityRepository;

    public function findBy(array $searchCriteria = [])
    {
        $searchCriteria['order_by'] = [
            'field'         => 'id',
            'direction'     => 'asc'
        ];

        if (!empty($searchCriteria['id'])) {
            $this->queryBuilder
            ->where('id', Arr::pull($searchCriteria, 'id'));
        }

     //   $this->queryBuilder->where('company_id', Auth::user()->company_id);
        return parent::findBy($searchCriteria);
    }

    public function store($data)
    {
        DB::transaction(function () use ($data)
        {
          
            parent::store($data);
            // SAVE Transfer DETAILS
           // $this->saveTransferDetails($data, $this->model->id);
        });
    }

  
}
