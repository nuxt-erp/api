<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class TaxRuleConstants {
    const COMPUTATIONS = [ 
        'PERCENT_ON_PRICE'    => 'Percent on Price', 
        'FIXED'               => 'Fixed',
        'INCLUDED'            => 'Included' 
    ];
    const SCOPES = [ 
        'SALES'               => 'Sales',
        'PURCHASES'           => 'Puchases'
    ];
}
class TaxRule extends Model
{
    protected $connection = 'tenant';

    protected $fillable = [
        'name', 'short_name', 'computation',
        'status',  'province_id',
    ];

    public function getRules($request, $item = null)
    {
        $rules = [
            'province_id'       => ['nullable', 'exists:tenant.provinces,id'],
            'name'              => ['string', 'max:255'],
            'short_name'        => ['string', 'max:50'],
            'computation'       => ['string', 'max:255'],
            'status'            => ['boolean']
        ];

        // CREATE
        if (is_null($item))
        {
            $rules['name'][]              = 'required';
            $rules['short_name'][]        = 'required';
            $rules['computation'][]       = 'required';
            $rules['status'][]            = 'required';
        }

        return $rules;
    }
    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id');
    }
    public function scopes()
    {
        return $this->hasMany(TaxRuleScope::class, 'tax_rule_id', 'id');
    }
    public function tax_details()
    {
        return $this->hasMany(TaxRuleComponent::class, 'tax_rule_id', 'id');
    }
    static function getStatuses() {
        $oClass = new \ReflectionClass(TaxRuleConstants::class);
        return $oClass->getConstants();
    }
}
