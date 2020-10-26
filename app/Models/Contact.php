<?php

namespace App\Models;

use Illuminate\Validation\Rule;

class Contact extends ModelService
{

    protected $connection = 'tenant';

    protected $fillable = [
        'name', 'email', 'entity_id',
        'entity_type', 'phone_number',
        'mobile', 'is_default'
    ];

    public function getRules($request, $item = null)
    {
        $rules = [
            'name'              => ['nullable', 'string', 'max:255'],
            'email'             => ['nullable', 'max:255'],
            'phone_number'      => ['nullable', 'string', 'max:20'],
            'mobile'            => ['nullable', 'string', 'max:20']
        ];

        // CREATE
        if (is_null($item))
        {
            // $rules['email'][] = 'required';
            $rules['email'][] = 'unique:tenant.contacts';
        }
        else{
            $rules['email'][]    = Rule::unique('tenant.contacts')->ignore($item->id);
        }

        return $rules;
    }
    public function entity()
    {
        if($this->entity_type === 'customer') {
            return $this->belongsTo(Customer::class);
        }
        else if($this->entity_type === 'supplier') {
            return $this->belongsTo(Supplier::class);
        }
    }
}
