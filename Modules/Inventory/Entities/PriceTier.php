<?php

namespace Modules\Inventory\Entities;

use Illuminate\Validation\Rule;

use App\Models\ModelService;
use App\Models\User;

class PriceTier extends ModelService
{
    protected $connection = 'tenant';

    protected $table = 'inv_price_tiers';

    protected $fillable = [
        'name', 'markup', 'markup_type', 'is_enabled',
        'custom_price', 'author_id', 'last_updater_id'
    ];

    public function getRules($request, $item = null)
    {
        $rules = [
            'name'          => ['string', 'max:255'],
            'markup'        => ['nullable', 'numeric'],
            'markup_type'   => ['nullable', Rule::in(['cost', 'msrp'])],
            'custom_price'  => ['nullable', 'numeric']
        ];

        // CREATE
        if (is_null($item))
        {
            $rules['name'][] = 'required';
        }

        return $rules;
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function last_updater()
    {
        return $this->belongsTo(User::class, 'last_updater_id');
    }

    public function items(){
        return $this->hasManySync(PriceTierItems::class, 'price_tier_id', 'id');
    }
}
