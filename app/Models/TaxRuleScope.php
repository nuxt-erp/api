<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaxRuleScope extends Model
{    
    protected $connection = 'tenant';

    protected $fillable = [
        'tax_rule_id', 'scope'
    ];

    public function getRules($request, $item = null)
    {
        $rules = [
            'tax_rule_id'       => ['nullable', 'exists:tenant.tax_rules,id'],
            'scope'             => ['string', 'max:255'],
        ];

        // CREATE
        if (is_null($item))
        {
            $rules['tax_rule_id'][]       = 'required';
            $rules['scope'][]             = 'required';
        }

        return $rules;
    }
    public function tax_rule()
    {
        return $this->belongsTo(TaxRule::class, 'tax_rule_id');
    }
}
