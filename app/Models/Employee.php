<?php

namespace App\Models;

use Illuminate\Validation\Rule;

class Employee extends ModelService
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'address',
        'city', 'state', 'postal_code',
        'phone_number', 'mobile_number', 'image',
        'contact_name', 'user_id', 'type_id'
    ];

    public function getRules($request, $item = null)
    {

        $rules = [
            'user_id'       => ['nullable', 'exists:users,id'],
            'type_id'       => ['nullable', 'exists:parameters,id'],
            'name'          => ['string', 'max:255'],
            'email'         => ['nullable', 'max:255', 'email'],
            'address'       => ['max:50'],
            'city'          => ['max:30'],
            'state'         => ['max:20'],
            'postal_code'   => ['max:10'],
            'phone_number'  => ['max:15'],
            'mobile_number' => ['max:15'],
            'image'         => ['nullable', 'string', 'max:255'],
            'contact_name'  => ['max:255']

        ];
        //create
        if (is_null($item)) {
            $rules['name'][]        = 'required';
            $rules['email'][]       = 'unique:employees';
        } else {
            //update
            $rules['email'][]       = Rule::unique('employees')->ignore($item->id);
        }

        return $rules;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function type()
    {
        return $this->belongsTo(Parameter::class, 'type_id');
    }
}
