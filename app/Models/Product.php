<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'company_id', 'sku', 'name', 'description', 'cost', 'status', 'barcode', 'sales_chanel', 'brand_id', 'category_id', 'supplier_id', 'width', 'length', 'weight', 'height', 'price', 'family_id', 'launch_date', 'dear'
    ];

    public function getRules($request, $item = null)
    {
        $rules = [
            'name'          => ['string', 'max:100'],
            'brand_id'      => ['nullable', 'exists:brands,id'],
            'category_id'   => ['nullable', 'exists:categories,id'],
            'supplier_id'   => ['nullable', 'exists:suppliers,id'],
            'company_id'    => ['exists:companies,id'],
        ];

        // CREATE
        if (is_null($item))
        {
            $rules['name'][] = 'required';
            // $rules['sku'][]  = 'required';
            // $rules['sku'][]  = 'unique:products';
        }

        return $rules;
    }

    /*public function setNameAttribute($value)
    {
        // $this->attributes['name'] =
    }*/

    public function attributes() {
        return $this->hasMany('App\Models\ProductAttribute');
    }

    public function availability() {
        return $this->hasMany('App\Models\ProductAvailability', 'product_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

}
