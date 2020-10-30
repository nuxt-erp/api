<?php

namespace App\Models;

class CustomerTag extends ModelService
{
    protected $connection = 'tenant';

    protected $table = 'customer_tags';

    protected $fillable = [
        'customer_id', 'tag_id'
    ];

    public function getRules($request, $item = null)
    {
        $rules = [
            'customer_id'   => ['exists:tenant.customers,id'],
            'tag_id'        => ['exists:tenant.tags,id'],
        ];

        // CREATE
        if (is_null($item))
        {
            $rules['customer_id'][]     = 'required';
            $rules['tag_id'][]          = 'required';
        }        

        return $rules;
    }
   
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function tag()
    {
        return $this->belongsTo(Tag::class, 'tag_id');
    }
}
