<?php

namespace Modules\Inventory\Repositories;

use App\Repositories\RepositoryService;
use Illuminate\Support\Arr;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Inventory\Entities\PriceTierItems;
use Modules\Inventory\Entities\PriceTier;
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

    public function applyChanges(array $data = [])
    {

        $price = 0;
        $query = Product::query();

        if ($data['category']) {
            $query->where('category_id', $data['category']);
        }

        if ($data['brand']) {
            $query->where('brand_id', $data['brand']);
        }

        $collect = $query->get();

        DB::transaction(function () use ($data, $collect){

            // Save price tier
            if ($data["editing"]) {
                $tier = PriceTier::find($data['id']);
                parent::update($tier, $data);
            } else {
                $user = auth()->user();
                $data['author_id']          = $user->id;
                $data['last_updater_id']    = $user->id;
                parent::store($data);
            }

            // Delete all items
            PriceTierItems::where('price_tier_id', $this->model->id)->delete();

            $new = [];

            // Save price tier items
            foreach ($collect as $key => $value) {

                if ($data['markup'] > 0 || $data['custom_price'] > 0) {
                    switch ($data['markup_type']) {
                        case 'cost':
                            $price = $value->cost + ($value->cost * $data['markup'] / 100);
                            break;
                        case 'msrp':
                            $price = $value->msrp + ($value->msrp * $data['markup'] / 100);
                            break;
                        case '':
                            $price = $data['custom_price'];
                            break;
                    }
                }

                array_push($new, [
                    'product_id'        => $value->id,
                    'custom_price'      => $price,
                    'price_tier_id'     => $this->model->id
                ]);
            }

            PriceTierItems::insert($new);

        });

    }

    public function store(array $data)
    {
        DB::transaction(function () use ($data){

            $user = auth()->user();
            $data['author_id']          = $user->id;
            $data['last_updater_id']    = $user->id;
            parent::store($data);

            $this->model->items()->sync($data['items']);
            //$this->syncProductsPrice($data['items']);

        });
    }

    public function update($model, array $data)
    {
        DB::transaction(function () use ($model, $data){

            $user = auth()->user();
            $data['last_updater_id']    = $user->id;
            parent::update($model, $data);

            $this->model->items()->sync($data['items']);
            //$this->syncProductsPrice($data['items']);

        });
    }

    private function syncProductsPrice($items)
    {
        foreach ($items as $item) {
            Product::where('id', $item['product_id'])
            ->update(['price' => $item['custom_price']]);
        }
    }

}
