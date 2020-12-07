<?php

namespace Modules\Inventory\Transformers;

use App\Resources\ResourceService;

class StockCountResource extends ResourceService
{
    public function toArray($request)
    {
        $filters = $this->stock_filters;
        $tag_ids = [];
        $brand_ids = [];
        $category_ids = [];
        $stock_locator_ids = [];
        $bin_ids = [];
        
        foreach($filters as $filter) {
            if($filter->type === 'App\\Models\\Tag') {
                array_push($tag_ids, $filter->type_id);
            }
            else if($filter->type === 'Modules\\Inventory\\Entities\\StockLocator') {
                array_push($stock_locator_ids, $filter->type_id);
            }
            else if($filter->type === 'Modules\\Inventory\\Entities\\LocationBin') {
                array_push($bin_ids, $filter->type_id);
            }
            else if($filter->type === 'Modules\\Inventory\\Entities\\Category') {
                array_push($category_ids, $filter->type_id);
            }
            else if($filter->type === 'Modules\\Inventory\\Entities\\Brand') {
                array_push($brand_ids, $filter->type_id);
            }
        }

        return [
            'id'                        => $this->id,
            'name'                      => $this->name,
            'date'                      => optional($this->date)->format('Y-m-d'),
            'target'                    => $this->target,
            'count_type_id'             => $this->count_type_id,
            'is_enabled'                => $this->add_discontinued,
            'status'                    => $this->status,
            'status_name'               => $this->status ? 'Done' : 'In Progress',
            'brand_id'                  => $this->brand_id,
            'brand_name'                => optional($this->brand)->name,
            'category_id'               => $this->category_id,
            'category_name'             => optional($this->category)->name,
            'location_id'               => $this->location_id,
            'location_name'             => optional($this->location)->name,
            'tag_ids'                   => $tag_ids,
            'stock_locator_ids'         => $stock_locator_ids,
            'bin_ids'                   => $bin_ids,
            'category_ids'              => $category_ids,
            'brand_ids'                 => $brand_ids,
            'net_variance'              => $this->net_variance,
            'abs_variance'              => $this->abs_variance,
            'success_rate'              => $this->success_rate,
            'can_be_deleted'            => true
        ];
    }
}
