<?php

namespace Modules\Sales\Http\Controllers;

use Illuminate\Http\Request;
use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use Modules\Sales\Entities\Discount;
use Modules\Sales\Entities\DiscountApplication;
use Modules\Sales\Entities\DiscountRule;
use Modules\Sales\Entities\DiscountTag;
use Modules\Sales\Repositories\DiscountRepository;
use Modules\Sales\Transformers\DiscountResource;

class DiscountController extends ControllerService implements CheckPolicies
{

    protected $repository;
    protected $resource;

    public function __construct(DiscountRepository $repository, DiscountResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
        parent::__construct();
    }

    public function getDiscountWithInfo($id)
    {
        $discount = Discount::find($id);
        $customer_tags = $discount->customer_tags;
        $discount_applications = $discount->discount_applications;
        $stackable_rules = DiscountRule::where('discount_id', $discount->id)->where('discount_application_id', '=', NULL)->where('stackable', true)->get();
        $stackable_include          = [];
        $stackable_exclude          = [];
        $stackable_include_label    = '';
        $stackable_exclude_label    = '';
        lad($stackable_rules);
        foreach($stackable_rules as $stackable_rule) {
            if($stackable_rule->exclude) {
                array_push($stackable_exclude, $stackable_rule->type_id);
                if(strlen($stackable_exclude_label) > 0) {
                    $stackable_exclude_label .= ', ';
                }
                $stackable_exclude_label .= $stackable_rule->type_entity->name;
            }
            if($stackable_rule->include) {
                array_push($stackable_include, $stackable_rule->type_id);
                if(strlen($stackable_include_label) > 0) {
                    $stackable_include_label .= ', ';
                }
                $stackable_include_label .= $stackable_rule->type_entity->name;
            } 
        }

        // lad($discount_applications);
        $keyValue   = [
            'id'                         => $discount->id,
            'title'                      => $discount->title,
            'start_date'                 => $discount->start_date,
            'end_date'                   => $discount->end_date,
            'stackable'                  => $discount->stackable,
            'order_rule_operation'       => $discount->order_rule_operation,
            'order_rule_value'           => $discount->order_rule_value,
            'customer_tags'              => $customer_tags->pluck('tag')->pluck('id')->toArray(),
            'customer_tag_names'         => implode(', ', $customer_tags->pluck('tag')->pluck('name')->toArray()),
            'stackable_include'       => $stackable_include,
            'stackable_exclude'       => $stackable_exclude,
            'stackable_include_label' => $stackable_include_label,
            'stackable_exclude_label' => $stackable_exclude_label
            
        ];
       
        
        $applications = [];
        foreach ($discount_applications as $application) {
            if(!array_key_exists(strval($application->id), $applications)) {

                $applications[strval($application->id)] = [
                    'id'                   => $application->id,
                    'amount_off'           => $application->amount_off,
                    'custom_price'         => $application->custom_price,
                    'percent_off'          => $application->percent_off,
                    'include_tag_arr'      => [],
                    'exclude_tag_arr'      => [],
                    'include_category_arr' => [],
                    'exclude_category_arr' => [],
                    'include_brand_arr'    => [],
                    'exclude_brand_arr'    => [],
                    'include_product_arr'  => [],
                    'exclude_product_arr'  => [],
                    'include_tag'          => '',
                    'exclude_tag'          => '',
                    'include_category'     => '',
                    'exclude_category'     => '',
                    'include_brand'        => '',
                    'exclude_brand'        => '',
                    'include_product'      => '',
                    'exclude_product'      => ''
                ];
            }
            foreach ($application->discount_rules as $rule) {
                
                if(!empty($rule->discount_application_id)) {
                    
                    switch ($rule->type) {
                        case 'App\\Models\\Tag':
                            if ($rule->include) {
                                array_push($applications[strval($rule->discount_application_id)]['include_tag_arr'], $rule->type_id);
                                if(strlen($applications[strval($rule->discount_application_id)]['include_tag']) > 0) {
                                    $applications[strval($rule->discount_application_id)]['include_tag'] .= ', ';
                                }
                                $applications[strval($rule->discount_application_id)]['include_tag'] .= $rule->type_entity->name;
        
                            } else if($rule->exclude) {
                                array_push($applications[strval($rule->discount_application_id)]['exclude_tag_arr'], $rule->type_id);
                                if(strlen($applications[strval($rule->discount_application_id)]['exclude_tag']) > 0) {
                                    $applications[strval($rule->discount_application_id)]['exclude_tag'] .= ', ';
                                }
                                $applications[strval($rule->discount_application_id)]['exclude_tag'] .= $rule->type_entity->name;
                            }
                            break;
                        case 'Modules\\Inventory\\Entities\Category':
                            if ($rule->include) {
                                array_push($applications[strval($rule->discount_application_id)]['include_category_arr'], $rule->type_id);
                                if(strlen($applications[strval($rule->discount_application_id)]['include_category']) > 0) {
                                    $applications[strval($rule->discount_application_id)]['include_category'] .= ', ';
                                }
                                $applications[strval($rule->discount_application_id)]['include_category'] .= $rule->type_entity->name;
                            } else if($rule->exclude) {
                                array_push($applications[strval($rule->discount_application_id)]['exclude_category_arr'], $rule->type_id);
                                if(strlen($applications[strval($rule->discount_application_id)]['exclude_category']) > 0) {
                                    $applications[strval($rule->discount_application_id)]['exclude_category'] .= ', ';
                                }
                                $applications[strval($rule->discount_application_id)]['exclude_category'] .= $rule->type_entity->name;
                            }
                            break;
                        case 'Modules\\Inventory\\Entities\\Brand':
                            if ($rule->include) {
                                array_push($applications[strval($rule->discount_application_id)]['include_brand_arr'], $rule->type_id);
                                if(strlen($applications[strval($rule->discount_application_id)]['include_brand']) > 0) {
                                    $applications[strval($rule->discount_application_id)]['include_brand'] .= ', ';
                                }
                                $applications[strval($rule->discount_application_id)]['include_brand'] .= $rule->type_entity->name;
                            } else if($rule->exclude) {
                                array_push($applications[strval($rule->discount_application_id)]['exclude_brand_arr'], $rule->type_id);
                                if(strlen($applications[strval($rule->discount_application_id)]['exclude_brand']) > 0) {
                                    $applications[strval($rule->discount_application_id)]['exclude_brand'] .= ', ';
                                }
                                $applications[strval($rule->discount_application_id)]['exclude_brand'] .= $rule->type_entity->name;
        
                            }                    
                            break;
                        case 'Modules\\Inventory\\Entities\\Product':
                            if ($rule->include) {
                                array_push($applications[strval($rule->discount_application_id)]['include_product_arr'], $rule->type_id);
                                if(strlen($applications[strval($rule->discount_application_id)]['include_product']) > 0) {
                                    $applications[strval($rule->discount_application_id)]['include_product'] .= ', ';
                                }
                                $applications[strval($rule->discount_application_id)]['include_product'] .= $rule->type_entity->name;
                            } else if($rule->exclude) {
                                array_push($applications[strval($rule->discount_application_id)]['exclude_product_arr'], $rule->type_id);
                                if(strlen($applications[strval($rule->discount_application_id)]['exclude_product']) > 0) {
                                    $applications[strval($rule->discount_application_id)]['exclude_product'] .= ', ';
                                }
                                $applications[strval($rule->discount_application_id)]['exclude_product'] .= $rule->type_entity->name;
                            }
                            break;
                        }
                }

            
        }
    }
        
        
        $keyValue['applications']            = $applications;

        return $this->sendArray($keyValue);
    }
    
}
