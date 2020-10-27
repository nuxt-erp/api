<?php

namespace Modules\Purchase\Entities;

use App\Models\ModelService;
use App\Models\Location;
use App\Models\Supplier;

class Purchase extends ModelService
{
    protected $connection = 'tenant';

    protected $table = 'pur_purchases';

    protected $fillable = [
        'supplier_id', 'author_id', 'location_id',
        'status', 'ref_code', 'invoice_number', 'tracking_number',
        'notes', 'discount', 'taxes',
        'shipping', 'subtotal', 'total',
        'purchase_date'
    ];

    public function getRules($request, $item = null)
    {
        // generic rules
        $rules = [
            'supplier_id'   => ['nullable', 'exists:tenant.suppliers,id'],
            'location_id'   => ['nullable', 'exists:tenant.locations,id'],
        ];

        return $rules;
    }

    public function details()
    {
        return $this->hasMany(PurchaseDetail::class, 'purchase_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    // Get earliest estimated date
    public function getEarliestEtaAttribute()
    {
        $data = $this->details->sortBy('estimated_date')->all();
        return $data[0]->estimated_date;
    }

}
