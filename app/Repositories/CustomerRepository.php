<?php

namespace App\Repositories;

use App\Models\Contact;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Modules\Inventory\Entities\ProductCustomPrice;

class CustomerRepository extends RepositoryService
{

    public function findBy(array $searchCriteria = [])

    {
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
                    
                    $new->disabled_at     = $custom_product_price['disabled_at'];
                    lad($custom_product_price['is_enabled']);
                    if (empty($custom_product_price['disabled_at']) && $custom_product_price['is_enabled'] === 0) {
                        lad(now());
                        $custom_product_price['disabled_at'] = now();
                    }
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
                    lad($custom_product_price['is_enabled']);

                    if (empty($custom_product_price['disabled_at']) && $custom_product_price['is_enabled'] === 0) {

                        lad(now());
                        $custom_product_price['disabled_at'] = now();
                    }
                    ProductCustomPrice::updateOrCreate(
                        ['id' => $custom_product_price['id']],
                        $custom_product_price
                    );
                }
            }
        });
    }
}
