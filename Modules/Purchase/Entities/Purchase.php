<?php

namespace Modules\Purchase\Entities;

use App\Models\ModelService;
use App\Models\Location;
use App\Models\Supplier;
use Illuminate\Validation\Rule;

class Purchase extends ModelService
{
    protected $connection = 'tenant';

    protected $table = 'pur_purchases';

    const DRAFT_ORDER           = 'draft order';
    const PARTIALLY_RECEIVED    = 'partially received';
    const AWAITING_PAYMENT      = 'awaiting payment';
    const RECEIVED              = 'received';
    const AWAITING_DELIVERY     = 'awaiting delivery';
    const VOIDED                = 'voided';

    protected $dates = [
        'purchase_date',
    ];
    protected $fillable = [
        'supplier_id', 'author_id', 'location_id',
        'status', 'ref_code', 'invoice_number', 'tracking_number',
        'notes', 'discount', 'taxes',
        'shipping', 'subtotal', 'total',
        'purchase_date', 'po_number'
    ];

    public function getRules($request, $item = null)
    {
        // generic rules
        $rules = [
            'supplier_id'   => ['nullable', 'exists:tenant.suppliers,id'],
            'location_id'   => ['nullable', 'exists:tenant.locations,id'],
            'status'        => ['string', 'max:50'],

        ];

        // CREATE
        if (is_null($item))
        {
            $rules['po_number'][] = 'unique:tenant.pur_purchases';
            $rules['status'][]    = 'required';
        }
        else{
            $rules['po_number'][] = Rule::unique('tenant.pur_purchases')->ignore($item->id);
        }


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
        if(isset($data[0]->estimated_date)){
            return $data[0]->estimated_date;
        }
    else{
        return null;
    }
    }

}
