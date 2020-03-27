<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubSpecification extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name', 'spec_id', 'company_id'
    ];

    public function getRules($request, $item = null)
    {
        $rules = [
            'name'      => ['string', 'max:45'],
            'spec_id'   => ['exists:specifications,id'],
        ];

        // CREATE
        if (is_null($item))
        {
            $rules['name'][]    = 'required';
            $rules['spec_id'][] = 'required';
        }

        return $rules;
    }

    public function specification()
    {
        return $this->belongsTo(Specification::class, 'spec_id');
    }
}
