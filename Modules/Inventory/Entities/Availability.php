<?php

namespace Modules\Inventory\Entities;

use App\Models\Location;
use App\Models\ModelService;
use Illuminate\Validation\Rule;

class Availability extends ModelService
{

    protected $connection = 'tenant';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'inv_availabilities';

    protected $fillable = [
        'product_id', 'location_id', 'available', 'on_hand', 'on_order', 'allocated'
    ];

    public function getRules($request, $item = null)
    {

        $rules = [
            'product_id'            => ['exists:products,id'],
            'location_id'           => ['nullable', 'exists:locations,id']
        ];
        //create
        if (is_null($item)) {
            $rules['product_id'][] = 'required';
        }

        return $rules;
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

}
