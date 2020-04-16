<?php

namespace App\Models;

use Illuminate\Validation\Rule;

class ProductAvailability extends ModelService
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

     public $timestamps = false;

    protected $fillable = [
        'product_id', 'company_id', 'location_id', 'available', 'on_hand'
    ];

    public function getRules($request, $item = null)
    {

        $rules = [
            'product_id'            => ['exists:products,id'],
            'company_id'            => ['exists:companies,id'],
            'location_id'           => ['nullable', 'exists:locations,id']
        ];
        //create
        if (is_null($item)) {
            $rules['product_id'][] = 'required';
            $rules['company_id'][] = 'required';
        }

        return $rules;
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

}
