<?php

namespace Modules\Inventory\Entities;

use App\Models\ModelService;

class PriceTierItems extends ModelService
{

    protected $connection = 'tenant';

    protected $table = 'inv_price_tier_items';

    protected $fillable = [
        'product_id', 'price_tier_id', 'custom_price'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function price_tier()
    {
        return $this->belongsTo(PriceTier::class, 'price_tier_id');
    }

}

