<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaxRuleComponent extends Model
{
    protected $connection = 'tenant';

    protected $fillable = [
        'tax_rule_id', 'component_name', 'rate',
        'compound',  'seq',
    ];

    public function getRules($request, $item = null)
    {
        $rules = [
            'tax_rule_id'       => ['nullable', 'exists:tenant.tax_rules,id'],
            'component_name'    => ['string', 'max:255'],
            'compound'          => ['boolean']
        ];

        // CREATE
        if (is_null($item))
        {
            $rules['tax_rule_id'][]       = 'required';
            $rules['rate'][]              = 'required';
            $rules['component_name'][]    = 'required';
        }

        return $rules;
    }
    public function tax_rule()
    {
        return $this->belongsTo(TaxRule::class, 'tax_rule_id');
    }
}
