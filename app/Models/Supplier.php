<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name', 'company_id', 'lead_time', 'ordering_cycle', 'brand_id', 'supplier_type_id', 'date_last_order'
    ];

    public function getRules($request, $item = null)
    {
        $rules = [
            'name' => ['string', 'max:60'],
        ];

        // CREATE
        if (is_null($item))
        {
            $rules['name'][] = 'required';
        }

        return $rules;
    }

    public function supplier_type()
    {
        return $this->belongsTo(SystemParameter::class, 'supplier_type_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }
}
