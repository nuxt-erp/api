<?php
/*
$table->string('dear_id')->nullable()->unique();
$table->string('name')->unique();
$table->mediumText('description')->nullable();
$table->string('sku')->nullable()->unique();
$table->timestamp('launch_at')->nullable();
$table->boolean('is_enabled')->default(1);
$table->timestamp('disabled_at')->nullable();*/

namespace Modules\Inventory\Entities;

use App\Models\Location;
use App\Models\ModelService;
use App\Models\Supplier;
use Illuminate\Validation\Rule;

class Family extends ModelService
{
    public $timestamps = false;

    protected $fillable = [
        'brand_id', 'category_id', 'supplier_id',
        'location_id', 'dear_id', 'name',
        'description', 'sku', 'launch_at',
        'is_enabled', 'disabled_at'
    ];

    public function getRules($request, $item = null)
    {
        $rules = [
            'brand_id'      => ['nullable', 'exists:inv_brands,id'],
            'category_id'   => ['nullable', 'exists:inv_categories,id'],
            'supplier_id'   => ['nullable', 'exists:suppliers,id'],
            'location_id'   => ['nullable', 'exists:locations,id'],
            'dear_id'       => ['nullable', 'string', 'max:255'],
            'name'          => ['string', 'max:100'],
            'description'   => ['nullable', 'string', 'max:500'],
            'sku'           => ['nullable', 'string', 'max:255'],
        ];

        // CREATE
        if (is_null($item))
        {
            $rules['name'][]    = 'required';
            $rules['name'][]    = 'unique:inv_products';
            $rules['sku'][]     = 'unique:inv_products';
        } else {
            //update
            $rules['name'][]    = Rule::unique('inv_products')->ignore($item->id);
            $rules['sku'][]     = Rule::unique('inv_products')->ignore($item->id);
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

