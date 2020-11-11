<?php

namespace Modules\Inventory\Repositories;

use App\Repositories\RepositoryService;
use Illuminate\Support\Arr;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Inventory\Entities\PriceTierItems;
use Modules\Inventory\Entities\Product;

class PriceTierRepository extends RepositoryService
{
  /*  public function findBy(array $searchCriteria = [])
    {
        if(empty($searchCriteria['order_by'])){
            $searchCriteria['order_by'] = [
                'field'         => 'name',
                'direction'     => 'desc'
            ];
        }
        if (!empty($searchCriteria['product_id']))
        {
            $product_id = Arr::pull($searchCriteria, 'product_id');
            $this->queryBuilder->whereHas('items', function ($query) use ($product_id) {
                $query->where('inv_price_tier_items.product_id', $product_id);
            });
        }

        return parent::findBy($searchCriteria);
    }
*/

    public function store(array $data)
    {
        DB::transaction(function () use ($data){

            $user = auth()->user();
            $data['author_id']          = $user->id;
            $data['last_updater_id']    = $user->id;
            parent::store($data);

            $this->model->items()->sync($data['items']);
            $this->syncProductsPrice($data['items']);

        });
    }

    public function update($model, array $data)
    {
        DB::transaction(function () use ($model, $data){

            $user = auth()->user();
            $data['last_updater_id']    = $user->id;
            parent::update($model, $data);

            $this->model->items()->sync($data['items']);
            $this->syncProductsPrice($data['items']);

        });
    }

    private function syncProductsPrice($items){

        foreach ($items as $item) {
            Product::where('id', $item['product_id'])
            ->update(['price' => $item['custom_price']]);
        }
    }

}
