<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSpecification extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'value', 'product_id', 'spec_id', 'sub_spec_id'
    ];

    public function getRules($request, $item = null)
    {
        $rules = [
            'value'         => ['string', 'max:60'],
            'spec_id'       => ['exists:specifications,id'],
            'sub_spec_id'   => ['nullable', 'exists:sub_specifications,id'],
            'product_id'    => ['exists:products,id'],
        ];

        // CREATE
        if (is_null($item))
        {
            $rules['value'][]           = 'required';
            $rules['product_id'][]      = 'required';
            $rules['spec_id'][]    = 'required';
        }

        return $rules;
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function specification()
    {
        return $this->belongsTo(Specification::class);
    }

}

