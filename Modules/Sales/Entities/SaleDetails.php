<?php

namespace Modules\Sales\Entities;

use App\Models\Location;
use App\Models\ModelService;
use App\Models\Parameter;
use Illuminate\Validation\Rule;
use Modules\Inventory\Entities\Product;

class SaleDetails extends ModelService
{
    protected $table = 'sal_sale_details';

    protected $dates = [
        'fulfillment_date'
    ];

    protected $fillable = [
        'sale_id', 'product_id', 'location_id',
        'fulfillment_status_id', 'shopify_id', 'qty',
        'price', 'discount_value', 'discount_percent',
        'total_item', 'qty_fulfilled', 'fulfillment_date'
    ];

    public function getRules($request, $item = null)
    {

        $rules = [
            'sale_id'               => ['exists:sal_sales,id'],
            'product_id'            => ['nullable', 'exists:products,id'],
            'location_id'           => ['nullable', 'exists:locations,id'],
            'fulfillment_status_id' => ['nullable', 'exists:parameters,id'],
            'shopify_id'            => ['nullable'],
            //@todo add more validation
        ];

        // CREATE
        if (is_null($item)){
            $rules['shopify_id'][]  = 'unique:sal_sales';
            $rules['sale_id'][]     = 'required';
        } else {
            //update
            $rules['shopify_id'][]  = Rule::unique('sal_sales')->ignore($item->id);
        }

        return $rules;
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function fulfillment_status()
    {
        return $this->belongsTo(Parameter::class, 'fulfillment_status_id');
    }



}
