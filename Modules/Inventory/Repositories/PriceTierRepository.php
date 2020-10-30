<?php

namespace Modules\Inventory\Repositories;

use App\Repositories\RepositoryService;
use Illuminate\Support\Arr;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Inventory\Entities\PriceTierItems;

class PriceTierRepository extends RepositoryService
{

    public function store(array $data)
    {
        DB::transaction(function () use ($data){

            $user = auth()->user();
            $data['author_id']          = $user->id;
            $data['last_updater_id']    = $user->id;
            parent::store($data);

            $this->model->items()->sync($data['items']);

        });
    }

    public function update($model, array $data)
    {
        DB::transaction(function () use ($model, $data){

            $user = auth()->user();
            $data['last_updater_id']    = $user->id;
            parent::update($model, $data);

            $this->model->items()->sync($data['items']);

        });
    }

}
