<?php

namespace Modules\Inventory\Entities;

use App\Models\Location;
use App\Models\ModelService;
use App\Models\Supplier;
use Illuminate\Validation\Rule;

class Family extends ModelService
{
    protected $connection = 'tenant';

    protected $table = 'inv_families';

    protected $dates = [
        'launch_at', 'disabled_at',
    ];

    protected $fillable = [
        'brand_id', 'category_id', 'supplier_id',
        'location_id', 'dear_id', 'name',
        'description', 'sku', 'launch_at',
        'is_enabled', 'disabled_at'
    ];

    public function getRules($request, $item = null)
    {
        $rules = [
            'brand_id'      => ['nullable', 'exists:tenant.inv_brands,id'],
            'category_id'   => ['nullable', 'exists:tenant.inv_categories,id'],
            'supplier_id'   => ['nullable', 'exists:tenant.suppliers,id'],
            'location_id'   => ['nullable', 'exists:tenant.locations,id'],
            'dear_id'       => ['nullable', 'string', 'max:255'],
            'name'          => ['string', 'max:100'],
            'description'   => ['nullable', 'string', 'max:500'],
            'sku'           => ['nullable', 'string', 'max:255'],
            'launch_at'     => ['nullable', 'date'],
            'is_enabled'    => ['nullable', 'boolean']
        ];

        // CREATE
        if (is_null($item))
        {
            $rules['name'][]    = 'required';
            $rules['dear_id'][] = 'unique:tenant.inv_families';
            $rules['name'][]    = 'unique:tenant.inv_families';
            $rules['sku'][]     = 'unique:tenant.inv_families';
        } else {
            //update
            $rules['dear_id'][] = Rule::unique('tenant.inv_families')->ignore($item->id);
            $rules['name'][]    = Rule::unique('tenant.inv_families')->ignore($item->id);
            $rules['sku'][]     = Rule::unique('tenant.inv_families')->ignore($item->id);
        }

        return $rules;
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function product()
    {
        return $this->hasMany(Product::class, 'family_id', 'id');
    }

}

