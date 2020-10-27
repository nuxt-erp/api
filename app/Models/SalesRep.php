<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesRep extends Model
{
    protected $connection = 'tenant';

    protected $fillable = [
        'name', 'email', 'phone_number',
        'mobile', 'comission',  'is_default',
        'user_id'
    ];

    public function getRules($request, $item = null)
    {
        $rules = [
            'name'              => ['string', 'max:255'],
            'email'             => ['string', 'max:255'],
            'phone_number'      => ['string', 'max:20'],
            'mobile'            => ['string', 'max:20'],
            'user_id'           => ['nullable', 'exists:tenant.users,id']
        ];
        // CREATE
        if (is_null($item))
        {
            $rules['name'][]    = 'required';
            $rules['email'][]   = 'unique';
        }
        else{
            $rules['name'][]    = Rule::unique('tenant.suppliers')->ignore($item->id);
        }

        return $rules;
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }


}
