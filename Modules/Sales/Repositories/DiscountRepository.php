<?php

namespace Modules\Sales\Repositories;

use App\Repositories\RepositoryService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Modules\Sales\Entities\Discount;
use Modules\Sales\Entities\DiscountApplication;
use Modules\Sales\Entities\DiscountRule;
use Modules\Sales\Entities\DiscountTag;

class DiscountRepository extends RepositoryService
{
    public function findBy(array $searchCriteria = [])
    {
        return parent::findBy($searchCriteria);
    }

    public function store($data)
    {
        DB::transaction(function () use ($data)
        {          
            
            $order_value = null;
            $order_operation = null;
            if(!empty($data['order_rule_operation']) && $data['order_rule_value'] !== 0) {
                $order_operation = $data['order_rule_operation'];
                $order_value = $data['order_rule_value'];
            }
            $save_model = [
                'title'                => $data['title'], 
                'order_rule_operation' => $order_operation, 
                'order_rule_value'     => $order_value,
                'start_date'           => $data['start_date'], 
                'end_date'             => $data['end_date'], 
                'stackable'            => $data['stackable']
            ];
            parent::store($save_model);

            if(!empty($data['customer_tags'])){
                foreach($data['customer_tags'] as $tag) {
                    DiscountTag::updateOrCreate([
                        'tag_id'      => $tag,
                        'discount_id' => $this->model->id,
                        'type'        => 'customer'
                    ]);
                }
            }

            if(!empty($data['stackable_include'])){
                foreach($data['stackable_include'] as $stackable) {
                    DiscountRule::updateOrCreate([
                        'type'                      => 'App\\Models\\Tag', 
                        'type_id'                   => $stackable, 
                        'discount_id'               => $this->model->id,
                        'discount_application_id'   => null,
                        'include'                   => true,
                        'exclude'                   => false,
                        'all_products'              => false, 
                        'stackable'                 => true
                    ]);
                }
            }

            if(!empty($data['stackable_exclude'])){

                foreach($data['stackable_exclude'] as $stackable) {
                    DiscountRule::updateOrCreate([
                        'type'                      => 'App\\Models\\Tag', 
                        'type_id'                   => $stackable, 
                        'discount_id'               => $this->model->id,
                        'discount_application_id'   => null,
                        'include'                   => false,
                        'exclude'                   => true,
                        'all_products'              => false, 
                        'stackable'                 => true
                    ]);
                } 
            }

            if(!empty($data['applications'])){
                foreach($data['applications'] as $application) {
                    DiscountRule::where('discount_application_id', '=', $this->model->id)->delete();

                    if(!empty($application['edited'])) {
                        $discount_app = DiscountApplication::updateOrCreate([
                            'discount_id'  => $this->model->id, 
                            'percent_off'  => $application['percent_off'], 
                            'amount_off'   => $application['amount_off'], 
                            'custom_price' => $application['custom_price']
                        ]);
                        if(!empty($application['include_tag_arr'])) {
                            foreach($application['include_tag_arr'] as $include_tag) {
                                DiscountRule::updateOrCreate([
                                    'type'                      => 'App\\Models\\Tag', 
                                    'type_id'                   => $include_tag, 
                                    'discount_id'               => $this->model->id,
                                    'discount_application_id'   => $discount_app['id'],
                                    'include'                   => 1,
                                    'exclude'                   => 0,
                                    'all_products'              => 0, 
                                    'stackable'                 => 0
                                ]);
                            } 
                        }
                        if(!empty($application['exclude_tag_arr'])) {
                            foreach($application['exclude_tag_arr'] as $exclude_tag) {
                                DiscountRule::updateOrCreate([
                                    'type'                      => 'App\\Models\\Tag', 
                                    'type_id'                   => $exclude_tag, 
                                    'discount_id'               => $this->model->id,
                                    'discount_application_id'   => $discount_app['id'],
                                    'include'                   => 0,
                                    'exclude'                   => 1,
                                    'all_products'              => 0, 
                                    'stackable'                 => 0
                                ]);
                            } 
                        }
                        if(!empty($application['include_category_arr'])) {
                            foreach($application['include_category_arr'] as $include_category) {
                                DiscountRule::updateOrCreate([
                                    'type'                      => 'Modules\\Inventory\\Entities\Category', 
                                    'type_id'                   => $include_category, 
                                    'discount_id'               => $this->model->id,
                                    'discount_application_id'   => $discount_app['id'],
                                    'include'                   => 1,
                                    'exclude'                   => 0,
                                    'all_products'              => 0, 
                                    'stackable'                 => 0
                                ]);
                            } 
                        }
                        if(!empty($application['exclude_category_arr'])) {
                            foreach($application['exclude_category_arr'] as $exclude_category) {
                                DiscountRule::updateOrCreate([
                                    'type'                      => 'Modules\\Inventory\\Entities\Category', 
                                    'type_id'                   => $exclude_category, 
                                    'discount_id'               => $this->model->id,
                                    'discount_application_id'   => $discount_app['id'],
                                    'include'                   => 0,
                                    'exclude'                   => 1,
                                    'all_products'              => 0, 
                                    'stackable'                 => 0
                                ]);
                            } 
                        }
                        if(!empty($application['include_brand_arr'])) {
                            foreach($application['include_brand_arr'] as $include_brand) {
                                DiscountRule::updateOrCreate([
                                    'type'                      => 'Modules\\Inventory\\Entities\\Brand', 
                                    'type_id'                   => $include_brand, 
                                    'discount_id'               => $this->model->id,
                                    'discount_application_id'   => $discount_app['id'],
                                    'include'                   => 1,
                                    'exclude'                   => 0,
                                    'all_products'              => 0, 
                                    'stackable'                 => 0
                                ]);
                            } 
                        }
                        if(!empty($application['exclude_brand_arr'])) {
                            foreach($application['exclude_brand_arr'] as $exclude_brand) {
                                DiscountRule::updateOrCreate([
                                    'type'                      => 'Modules\\Inventory\\Entities\\Brand', 
                                    'type_id'                   => $exclude_brand, 
                                    'discount_id'               => $this->model->id,
                                    'discount_application_id'   => $discount_app['id'],
                                    'include'                   => 0,
                                    'exclude'                   => 1,
                                    'all_products'              => 0, 
                                    'stackable'                 => 0
                                ]);
                            } 
                        }
                        if(!empty($application['include_product_arr'])) {
                            foreach($application['include_product_arr'] as $include_product) {
                                DiscountRule::updateOrCreate([
                                    'type'                      => 'Modules\\Inventory\\Entities\\Product', 
                                    'type_id'                   => $include_product, 
                                    'discount_id'               => $this->model->id,
                                    'discount_application_id'   => $discount_app['id'],
                                    'include'                   => 1,
                                    'exclude'                   => 0,
                                    'all_products'              => 0, 
                                    'stackable'                 => 0
                                ]);
                            } 
                        }
                        if(!empty($application['exclude_product_arr'])) {
                            foreach($application['exclude_product_arr'] as $exclude_product) {
                                DiscountRule::updateOrCreate([
                                    'type'                      => 'Modules\\Inventory\\Entities\\Product', 
                                    'type_id'                   => $exclude_product, 
                                    'discount_id'               => $this->model->id,
                                    'discount_application_id'   => $discount_app['id'],
                                    'include'                   => 0,
                                    'exclude'                   => 1,
                                    'all_products'              => 0, 
                                    'stackable'                 => 0
                                ]);
                            } 
                        }
                    }
                    if(!empty($application['new'])) {
                        $discount_app = DiscountApplication::updateOrCreate([
                            'discount_id'  => $this->model->id, 
                            'percent_off'  => $application['percent_off'], 
                            'amount_off'   => $application['amount_off'], 
                            'custom_price' => $application['custom_price']
                        ]);
                        if(!empty($application['include_tag_arr'])) {
                            foreach($application['include_tag_arr'] as $include_tag) {
                                DiscountRule::updateOrCreate([
                                    'type'                      => 'App\\Models\\Tag', 
                                    'type_id'                   => $include_tag, 
                                    'discount_id'               => $this->model->id,
                                    'discount_application_id'   => $discount_app['id'],
                                    'include'                   => 1,
                                    'exclude'                   => 0,
                                    'all_products'              => 0, 
                                    'stackable'                 => 0
                                ]);
                            } 
                        }
                        if(!empty($application['exclude_tag_arr'])) {
                            foreach($application['exclude_tag_arr'] as $exclude_tag) {
                                DiscountRule::updateOrCreate([
                                    'type'                      => 'App\\Models\\Tag', 
                                    'type_id'                   => $exclude_tag, 
                                    'discount_id'               => $this->model->id,
                                    'discount_application_id'   => $discount_app['id'],
                                    'include'                   => 0,
                                    'exclude'                   => 1,
                                    'all_products'              => 0, 
                                    'stackable'                 => 0
                                ]);
                            } 
                        }
                        if(!empty($application['include_category_arr'])) {
                            foreach($application['include_category_arr'] as $include_category) {
                                DiscountRule::updateOrCreate([
                                    'type'                      => 'Modules\\Inventory\\Entities\Category', 
                                    'type_id'                   => $include_category, 
                                    'discount_id'               => $this->model->id,
                                    'discount_application_id'   => $discount_app['id'],
                                    'include'                   => 1,
                                    'exclude'                   => 0,
                                    'all_products'              => 0, 
                                    'stackable'                 => 0
                                ]);
                            } 
                        }
                        if(!empty($application['exclude_category_arr'])) {
                            foreach($application['exclude_category_arr'] as $exclude_category) {
                                DiscountRule::updateOrCreate([
                                    'type'                      => 'Modules\\Inventory\\Entities\Category', 
                                    'type_id'                   => $exclude_category, 
                                    'discount_id'               => $this->model->id,
                                    'discount_application_id'   => $discount_app['id'],
                                    'include'                   => 0,
                                    'exclude'                   => 1,
                                    'all_products'              => 0, 
                                    'stackable'                 => 0
                                ]);
                            } 
                        }
                        if(!empty($application['include_brand_arr'])) {
                            foreach($application['include_brand_arr'] as $include_brand) {
                                DiscountRule::updateOrCreate([
                                    'type'                      => 'Modules\\Inventory\\Entities\\Brand', 
                                    'type_id'                   => $include_brand, 
                                    'discount_id'               => $this->model->id,
                                    'discount_application_id'   => $discount_app['id'],
                                    'include'                   => 1,
                                    'exclude'                   => 0,
                                    'all_products'              => 0, 
                                    'stackable'                 => 0
                                ]);
                            } 
                        }
                        if(!empty($application['exclude_brand_arr'])) {
                            foreach($application['exclude_brand_arr'] as $exclude_brand) {
                                DiscountRule::updateOrCreate([
                                    'type'                      => 'Modules\\Inventory\\Entities\\Brand', 
                                    'type_id'                   => $exclude_brand, 
                                    'discount_id'               => $this->model->id,
                                    'discount_application_id'   => $discount_app['id'],
                                    'include'                   => 0,
                                    'exclude'                   => 1,
                                    'all_products'              => 0, 
                                    'stackable'                 => 0
                                ]);
                            } 
                        }
                        if(!empty($application['include_product_arr'])) {
                            foreach($application['include_product_arr'] as $include_product) {
                                DiscountRule::updateOrCreate([
                                    'type'                      => 'Modules\\Inventory\\Entities\\Product', 
                                    'type_id'                   => $include_product, 
                                    'discount_id'               => $this->model->id,
                                    'discount_application_id'   => $discount_app['id'],
                                    'include'                   => 1,
                                    'exclude'                   => 0,
                                    'all_products'              => 0, 
                                    'stackable'                 => 0
                                ]);
                            } 
                        }
                        if(!empty($application['exclude_product_arr'])) {
                            foreach($application['exclude_product_arr'] as $exclude_product) {
                                DiscountRule::updateOrCreate([
                                    'type'                      => 'Modules\\Inventory\\Entities\\Product', 
                                    'type_id'                   => $exclude_product, 
                                    'discount_id'               => $this->model->id,
                                    'discount_application_id'   => $discount_app['id'],
                                    'include'                   => 0,
                                    'exclude'                   => 1,
                                    'all_products'              => 0, 
                                    'stackable'                 => 0
                                ]);
                            } 
                        }
                    }
                }
            }
        });
    }

    public function update($model, array $data)
    {
        DB::transaction(function () use ($data, $model)
        {
            $order_value = null;
            $order_operation = null;
            if(!empty($data['order_rule_operation']) && $data['order_rule_value'] !== 0) {
                $order_operation = $data['order_rule_operation'];
                $order_value = $data['order_rule_value'];
            }
            
            $update_model = [
                'title'                => $data['title'], 
                'order_rule_operation' => $order_operation, 
                'order_rule_value'     => $order_value,
                'start_date'           => $data['start_date'], 
                'end_date'             => $data['end_date'], 
                'stackable'            => $data['stackable']
            ];

            parent::update($model, $update_model);

            DiscountTag::where('type', '=', 'customer')->where('discount_id', '=', $model->id)->delete();

            if(!empty($data['customer_tags'])){
                foreach($data['customer_tags'] as $tag) {
                    DiscountTag::updateOrCreate([
                        'tag_id'      => $tag,
                        'discount_id' => $model->id,
                        'type'        => 'customer'
                    ]);
                }
            }

            if(!empty($data['stackable_include'])){
                DiscountRule::where('discount_id', '=', $model->id)->where('stackable', '=', 1)->where('include', '=', 1)->delete();
                foreach($data['stackable_include'] as $stackable) {
                    DiscountRule::updateOrCreate([
                        'type'                      => 'App\\Models\\Tag', 
                        'type_id'                   => $stackable, 
                        'discount_id'               => $model->id,
                        'discount_application_id'   => null,
                        'include'                   => true,
                        'exclude'                   => false,
                        'all_products'              => false, 
                        'stackable'                 => true
                    ]);
                }
            }

            if(!empty($data['stackable_exclude'])){
                DiscountRule::where('discount_id', '=', $model->id)->where('stackable', '=', 1)->where('exclude', '=', 1)->delete();

                foreach($data['stackable_exclude'] as $stackable) {
                    DiscountRule::updateOrCreate([
                        'type'                      => 'App\\Models\\Tag', 
                        'type_id'                   => $stackable, 
                        'discount_id'               => $model->id,
                        'discount_application_id'   => null,
                        'include'                   => false,
                        'exclude'                   => true,
                        'all_products'              => false, 
                        'stackable'                 => true
                    ]);
                } 
            }

            if(!empty($data['applications'])){
                foreach($data['applications'] as $application) {
                    DiscountRule::where('discount_application_id', '=', $model->id)->delete();

                    if(!empty($application['edited'])) {
                        $discount_app = DiscountApplication::updateOrCreate([
                            'id'           => $application['id']
                        ],
                        [
                            'discount_id'  => $model->id,
                            'percent_off'  => $application['percent_off'], 
                            'amount_off'   => $application['amount_off'], 
                            'custom_price' => $application['custom_price']
                        ]);
                        if(!empty($application['include_tag_arr'])) {
                            foreach($application['include_tag_arr'] as $include_tag) {
                                DiscountRule::updateOrCreate([
                                    'type'                      => 'App\\Models\\Tag', 
                                    'type_id'                   => $include_tag, 
                                    'discount_id'               => $model->id,
                                    'discount_application_id'   => $discount_app['id'],
                                    'include'                   => 1,
                                    'exclude'                   => 0,
                                    'all_products'              => 0, 
                                    'stackable'                 => 0
                                ]);
                            } 
                        }
                        if(!empty($application['exclude_tag_arr'])) {
                            foreach($application['exclude_tag_arr'] as $exclude_tag) {
                                DiscountRule::updateOrCreate([
                                    'type'                      => 'App\\Models\\Tag', 
                                    'type_id'                   => $exclude_tag, 
                                    'discount_id'               => $model->id,
                                    'discount_application_id'   => $discount_app['id'],
                                    'include'                   => 0,
                                    'exclude'                   => 1,
                                    'all_products'              => 0, 
                                    'stackable'                 => 0
                                ]);
                            } 
                        }
                        if(!empty($application['include_category_arr'])) {
                            foreach($application['include_category_arr'] as $include_category) {
                                DiscountRule::updateOrCreate([
                                    'type'                      => 'Modules\\Inventory\\Entities\Category', 
                                    'type_id'                   => $include_category, 
                                    'discount_id'               => $model->id,
                                    'discount_application_id'   => $discount_app['id'],
                                    'include'                   => 1,
                                    'exclude'                   => 0,
                                    'all_products'              => 0, 
                                    'stackable'                 => 0
                                ]);
                            } 
                        }
                        if(!empty($application['exclude_category_arr'])) {
                            foreach($application['exclude_category_arr'] as $exclude_category) {
                                DiscountRule::updateOrCreate([
                                    'type'                      => 'Modules\\Inventory\\Entities\Category', 
                                    'type_id'                   => $exclude_category, 
                                    'discount_id'               => $model->id,
                                    'discount_application_id'   => $discount_app['id'],
                                    'include'                   => 0,
                                    'exclude'                   => 1,
                                    'all_products'              => 0, 
                                    'stackable'                 => 0
                                ]);
                            } 
                        }
                        if(!empty($application['include_brand_arr'])) {
                            foreach($application['include_brand_arr'] as $include_brand) {
                                DiscountRule::updateOrCreate([
                                    'type'                      => 'Modules\\Inventory\\Entities\\Brand', 
                                    'type_id'                   => $include_brand, 
                                    'discount_id'               => $model->id,
                                    'discount_application_id'   => $discount_app['id'],
                                    'include'                   => 1,
                                    'exclude'                   => 0,
                                    'all_products'              => 0, 
                                    'stackable'                 => 0
                                ]);
                            } 
                        }
                        if(!empty($application['exclude_brand_arr'])) {
                            foreach($application['exclude_brand_arr'] as $exclude_brand) {
                                DiscountRule::updateOrCreate([
                                    'type'                      => 'Modules\\Inventory\\Entities\\Brand', 
                                    'type_id'                   => $exclude_brand, 
                                    'discount_id'               => $model->id,
                                    'discount_application_id'   => $discount_app['id'],
                                    'include'                   => 0,
                                    'exclude'                   => 1,
                                    'all_products'              => 0, 
                                    'stackable'                 => 0
                                ]);
                            } 
                        }
                        if(!empty($application['include_product_arr'])) {
                            foreach($application['include_product_arr'] as $include_product) {
                                DiscountRule::updateOrCreate([
                                    'type'                      => 'Modules\\Inventory\\Entities\\Product', 
                                    'type_id'                   => $include_product, 
                                    'discount_id'               => $model->id,
                                    'discount_application_id'   => $discount_app['id'],
                                    'include'                   => 1,
                                    'exclude'                   => 0,
                                    'all_products'              => 0, 
                                    'stackable'                 => 0
                                ]);
                            } 
                        }
                        if(!empty($application['exclude_product_arr'])) {
                            foreach($application['exclude_product_arr'] as $exclude_product) {
                                DiscountRule::updateOrCreate([
                                    'type'                      => 'Modules\\Inventory\\Entities\\Product', 
                                    'type_id'                   => $exclude_product, 
                                    'discount_id'               => $model->id,
                                    'discount_application_id'   => $discount_app['id'],
                                    'include'                   => 0,
                                    'exclude'                   => 1,
                                    'all_products'              => 0, 
                                    'stackable'                 => 0
                                ]);
                            } 
                        }
                    }
                    if(!empty($application['new'])) {
                        $discount_app = DiscountApplication::updateOrCreate([
                            'discount_id'  => $model->id,
                            'percent_off'  => $application['percent_off'], 
                            'amount_off'   => $application['amount_off'], 
                            'custom_price' => $application['custom_price']
                        ]);
                        if(!empty($application['include_tag_arr'])) {
                            foreach($application['include_tag_arr'] as $include_tag) {
                                DiscountRule::updateOrCreate([
                                    'type'                      => 'App\\Models\\Tag', 
                                    'type_id'                   => $include_tag, 
                                    'discount_id'               => $model->id,
                                    'discount_application_id'   => $discount_app['id'],
                                    'include'                   => 1,
                                    'exclude'                   => 0,
                                    'all_products'              => 0, 
                                    'stackable'                 => 0
                                ]);
                            } 
                        }
                        if(!empty($application['exclude_tag_arr'])) {
                            foreach($application['exclude_tag_arr'] as $exclude_tag) {
                                DiscountRule::updateOrCreate([
                                    'type'                      => 'App\\Models\\Tag', 
                                    'type_id'                   => $exclude_tag, 
                                    'discount_id'               => $model->id,
                                    'discount_application_id'   => $discount_app['id'],
                                    'include'                   => 0,
                                    'exclude'                   => 1,
                                    'all_products'              => 0, 
                                    'stackable'                 => 0
                                ]);
                            } 
                        }
                        if(!empty($application['include_category_arr'])) {
                            foreach($application['include_category_arr'] as $include_category) {
                                DiscountRule::updateOrCreate([
                                    'type'                      => 'Modules\\Inventory\\Entities\Category', 
                                    'type_id'                   => $include_category, 
                                    'discount_id'               => $model->id,
                                    'discount_application_id'   => $discount_app['id'],
                                    'include'                   => 1,
                                    'exclude'                   => 0,
                                    'all_products'              => 0, 
                                    'stackable'                 => 0
                                ]);
                            } 
                        }
                        if(!empty($application['exclude_category_arr'])) {
                            foreach($application['exclude_category_arr'] as $exclude_category) {
                                DiscountRule::updateOrCreate([
                                    'type'                      => 'Modules\\Inventory\\Entities\Category', 
                                    'type_id'                   => $exclude_category, 
                                    'discount_id'               => $model->id,
                                    'discount_application_id'   => $discount_app['id'],
                                    'include'                   => 0,
                                    'exclude'                   => 1,
                                    'all_products'              => 0, 
                                    'stackable'                 => 0
                                ]);
                            } 
                        }
                        if(!empty($application['include_brand_arr'])) {
                            foreach($application['include_brand_arr'] as $include_brand) {
                                DiscountRule::updateOrCreate([
                                    'type'                      => 'Modules\\Inventory\\Entities\\Brand', 
                                    'type_id'                   => $include_brand, 
                                    'discount_id'               => $model->id,
                                    'discount_application_id'   => $discount_app['id'],
                                    'include'                   => 1,
                                    'exclude'                   => 0,
                                    'all_products'              => 0, 
                                    'stackable'                 => 0
                                ]);
                            } 
                        }
                        if(!empty($application['exclude_brand_arr'])) {
                            foreach($application['exclude_brand_arr'] as $exclude_brand) {
                                DiscountRule::updateOrCreate([
                                    'type'                      => 'Modules\\Inventory\\Entities\\Brand', 
                                    'type_id'                   => $exclude_brand, 
                                    'discount_id'               => $model->id,
                                    'discount_application_id'   => $discount_app['id'],
                                    'include'                   => 0,
                                    'exclude'                   => 1,
                                    'all_products'              => 0, 
                                    'stackable'                 => 0
                                ]);
                            } 
                        }
                        if(!empty($application['include_product_arr'])) {
                            foreach($application['include_product_arr'] as $include_product) {
                                DiscountRule::updateOrCreate([
                                    'type'                      => 'Modules\\Inventory\\Entities\\Product', 
                                    'type_id'                   => $include_product, 
                                    'discount_id'               => $model->id,
                                    'discount_application_id'   => $discount_app['id'],
                                    'include'                   => 1,
                                    'exclude'                   => 0,
                                    'all_products'              => 0, 
                                    'stackable'                 => 0
                                ]);
                            } 
                        }
                        if(!empty($application['exclude_product_arr'])) {
                            foreach($application['exclude_product_arr'] as $exclude_product) {
                                DiscountRule::updateOrCreate([
                                    'type'                      => 'Modules\\Inventory\\Entities\\Product', 
                                    'type_id'                   => $exclude_product, 
                                    'discount_id'               => $model->id,
                                    'discount_application_id'   => $discount_app['id'],
                                    'include'                   => 0,
                                    'exclude'                   => 1,
                                    'all_products'              => 0, 
                                    'stackable'                 => 0
                                ]);
                            } 
                        }
                    }
                }
            }

        });
    }
}
