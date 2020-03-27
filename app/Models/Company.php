<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name', 'address1', 'address2', 'email', 'website', 'postal_code', 'country_id', 'province_id', 'status', 'city', 'phone_number', 'contact'
    ];
}
