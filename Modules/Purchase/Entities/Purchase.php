<?php

namespace Modules\Purchase\Entities;

use App\Models\ModelService;
use App\Models\Location;
use App\Models\Supplier;
use Illuminate\Validation\Rule;
class Constants {
    const DRAFT_ORDER           = [ 'draft order'        => 0 ];
    const PARTIALLY_RECEIVED    = [ 'partially received' => 1 ];
    const AWAITING_PAYMENT      = [ 'awaiting payment'   => 2 ];
    const RECEIVED              = [ 'received'           => 3 ];
    const AWAITING_DELIVERY     = [ 'awaiting delivery'  => 4 ];
    const VOIDED                = [ 'voided'             => 5 ];
    };
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
        'purchase_date', 'invoice_date'
    ];
    protected $fillable = [
        'supplier_id', 'author_id', 'location_id',
        'status', 'ref_code', 'invoice_number', 'tracking_number',
        'notes', 'discount', 'taxes',
        'shipping', 'subtotal', 'total', 'iteration',
        'purchase_date', 'invoice_date', 'po_number'
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
    
    public static function getStatuses() {
        $oClass = new \ReflectionClass(Constants::class);
        return $oClass->getConstants();
    }
    
    public function getStatusId($status)
    {
        $constants = $this->getStatuses();
        foreach ($constants as $constant_assoc) {
            if (!empty($constant_assoc[$status])) {
                return $constant_assoc[$status];
            }            
        }
        return null;
    }

    public function details()
    {
        return $this->hasMany(PurchaseDetail::class, 'purchase_id');
    }

    public function tracking_numbers()
    {
        return $this->hasManySync(PurchaseTrackingNumber::class, 'purchase_id', 'id');
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
