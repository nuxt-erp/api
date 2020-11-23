<?php

namespace App\Repositories;

use App\Models\Contact;
use App\Models\CustomerTag;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Modules\Inventory\Entities\ProductCustomPrice;

class CustomerRepository extends RepositoryService
{

    public function findBy(array $searchCriteria = [])
    {

        $searchCriteria['order_by'] = [
            'field'         => 'name',
            'direction'     => 'asc'
        ];

        if (!empty($searchCriteria['text'])) {
            $text = '%' . Arr::pull($searchCriteria, 'text') . '%';
            $this->queryBuilder->where(function ($query) use($text) {
                      $query->where('email', 'ILIKE', $text)
                ->orWhere('name', 'ILIKE', $text);
            });
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
                    $new->entity_type   = 'customer';
                    $new->name          = $contact['name'];
                    $new->email         = $contact['email'];
                    $new->phone_number  = $contact['phone_number'];
                    $new->mobile        = $contact['mobile'];
                    $new->save();
                }
            }
            if(!empty($data['custom_product_prices'])) {
                foreach ($data['custom_product_prices'] as $custom_product_price) {
                    $new                  = new ProductCustomPrice();
                    $new->customer_id     = $this->model->id;
                    $new->product_id      = $custom_product_price['product_id'];
                    $new->currency        = $custom_product_price['currency'];
                    $new->custom_price    = $custom_product_price['custom_price'];
                    $new->is_enabled      = $custom_product_price['is_enabled'];
                    if (empty($custom_product_price['disabled_at']) && $custom_product_price['is_enabled'] === 0) {
                        $custom_product_price['disabled_at'] = now();
                    }
                    $new->save();
                }
            }

            if(!empty($data['tag_ids'])) {
                foreach ($data['tag_ids'] as $tag_id) {
                    CustomerTag::create([
                        'customer_id' => $this->model->id,
                        'tag_id'      => $tag_id
                    ]);
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
                    $contact['entity_id']   = $model->id;
                    $contact['entity_type'] = 'customer';

                    Contact::updateOrCreate(
                        ['id' => $contact['id']],
                        $contact
                    );
                }
            }
            if(!empty($data['custom_product_prices'])) {
                foreach ($data['custom_product_prices'] as $custom_product_price) {
                    $custom_product_price['customer_id']   = $model->id;
                    $custom_product_price['entity_type']   = 'customer';

                    if (empty($custom_product_price['disabled_at']) && $custom_product_price['is_enabled'] === 0) {
                        $custom_product_price['disabled_at'] = now();
                    }
                    ProductCustomPrice::updateOrCreate(
                        ['id' => $custom_product_price['id']],
                        $custom_product_price
                    );
                }
            }

            if(!empty($data["tag_ids"])) {

                $current_tags = $this->model->tags->pluck('tag_id')->toArray();
                $deleted_tags = array_diff($current_tags, $data["tag_ids"]);

                foreach($data["tag_ids"] as $tag_id) {
                    if ( !in_array($tag_id, $current_tags)) {
                        CustomerTag::create([
                            'customer_id'    => $this->model->id,
                            'tag_id'        => $tag_id,
                        ]);
                    }
                }

                foreach($deleted_tags as $tag_id) {
                    CustomerTag::where('customer_id', $this->model->id)->where('tag_id', $tag_id)->delete();
                }
            }
        });
    }
}
