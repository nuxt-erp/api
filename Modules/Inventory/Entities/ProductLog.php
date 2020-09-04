<?php

namespace Modules\Inventory\Entities;

use App\Models\Location;
use App\Models\ModelService;
use Illuminate\Validation\Rule;

class ProductLog extends ModelService
{

    protected $connection = 'tenant';

    protected $table = 'inv_product_logs';

    const TYPE_LOG_SALE         = 'Sale';
    const TYPE_LOG_PURCHASE     = 'Purchase';
    const TYPE_LOG_TRANSFER     = 'Transfer';
    const TYPE_LOG_ADJUSTMENT   = 'Adjustment';
    const TYPE_LOG_STOCK_COUNT  = 'Stock Count';

    protected $fillable = [
        'product_id', 'location_id', 'type_id',
        'ref_code_id', 'quantity', 'description'

    ];

    public function getRules($request, $item = null)
    {
        $rules = [
            'product_id'    => ['exists:tenant.inv_products,id'],
            'location_id'   => ['nullable', 'exists:tenant.locations,id'],
            'type_id'       => ['nullable', 'exists:tenant.parameters,id'],
            'ref_code_id'   => ['nullable', 'integer'],
            'quantity'      => ['nullable', 'number'],
            'description'   => ['nullable', 'string', 'max:255']
        ];

        // CREATE
        if (is_null($item))
        {
            $rules['product_id'][] = 'required';
        } else {
            //update
        }

        return $rules;
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function getSourceAttribute()
    {
        return '';
        // if ($this->type == self::TYPE_LOG_SALE) {
        //     $get = Sale::where('id', $this->ref_code_id)->with('customer')->first();
        //     if ($get) {
        //         return $get->customer->name;
        //     }
        // }elseif ($this->type == self::TYPE_LOG_PURCHASE) {
        //     $get = Purchase::where('id', $this->ref_code_id)->with('supplier')->first();
        //     if ($get) {
        //         return $get->supplier->name;
        //     }
        // }
    }

}

