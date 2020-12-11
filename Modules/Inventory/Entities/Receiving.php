<?php

namespace Modules\Inventory\Entities;

use App\Models\ModelService;
use App\Models\Supplier;
use App\Models\Location;
use Illuminate\Validation\Rule;

class Receiving extends ModelService
{
    protected $connection = 'tenant';

    protected $table = 'inv_receiving';

    protected $fillable = [
        'name',
        'po_number',
        'invoice_number',
        'status',
        'supplier_id',
        'author_id',
        'location_id'  
    ];
    

    public function getRules($request, $item = null)
    {
        // generic rules
        $rules = [
            'supplier_id'   => ['nullable', 'exists:tenant.suppliers,id'],
            'location_id'   => ['nullable', 'exists:tenant.locations,id']
        ];

        // rules when creating the item
        if (is_null($item)) {
            $rules['name'][] = 'required';
        }

        return $rules;
    }
    public function details()
    {
        return $this->hasManySync(ReceivingDetail::class, 'receiving_id', 'id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function receiving_details()
    {
        return $this->hasMany(ReceivingDetail::class, 'receiving_id', 'id');
    }
}
