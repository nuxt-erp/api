<?php

namespace Modules\Inventory\Entities;

use App\Models\Location;
use App\Models\ModelService;

class LocationBin extends ModelService
{
    protected $connection = 'tenant';

    protected $table = 'inv_location_bins';

    protected $fillable = [
        'name', 'location_id', 'is_enabled'
    ];

    public function getRules($request, $item = null)
    {
        $rules = [
            'name'          => ['string', 'max:255'],
            'location_id'   => ['exists:tenant.locations,id'],
            'is_enabled'    => ['nullable', 'boolean']
        ];

        // CREATE
        if (is_null($item))
        {
            $rules['name'][]        = 'required';
            $rules['location_id'][] = 'required';
        }

        return $rules;
    }


    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

}
