<?php
/*
namespace Modules\Inventory\Entities;

use App\Models\ModelService;

class ProductFamilyAttribute extends ModelService
{

    protected $connection = 'tenant';
    protected $table = 'inv_family_attributes';

    protected $fillable = [
        'value', 'family_id', 'attribute_id'
    ];

    public function getRules($request, $item = null)
    {
        $rules = [
            'value'         => ['string', 'max:60'],
            'attribute_id'  => ['exists:tenant.inv_attributes,id'],
            'family_id'    => ['exists:tenant.inv_families,id'],

        ];

        // CREATE
        if (is_null($item))
        {
            $rules['value'][]           = 'required';
            $rules['family_id'][]       = 'required';
            $rules['attribute_id'][]    = 'required';
        }

        return $rules;
    }

    public function family()
    {
        return $this->belongsTo(Family::class);
    }

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

}*/


namespace Modules\Inventory\Entities;

use App\Models\ModelService;

class ProductFamilyAttribute extends ModelService
{

    protected $connection = 'tenant';

    protected $table = 'inv_family_attributes';

    protected $fillable = [
        'value', 'family_id', 'attribute_id'
    ];

    public function getRules($request, $item = null)
    {
        $rules = [
            'value'         => ['string', 'max:60'],
            'attribute_id'  => ['exists:tenant.inv_attributes,id'],
            'family_id'    => ['exists:tenant.inv_family,id'],
        ];

        // CREATE
        if (is_null($item))
        {
            $rules['value'][]           = 'required';
            $rules['family_id'][]      = 'required';
            $rules['attribute_id'][]    = 'required';
        }

        return $rules;
    }

    public function family()
    {
        return $this->belongsTo(Family::class);
    }

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

}