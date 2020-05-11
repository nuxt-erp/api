<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    public $timestamps = true;

    protected $fillable = [
        'pu_date', 'company_id', 'created_at', 'updated_at', 'carrier_id', 'eta', 'shipment_type_id', 'tracking_number', 'location_from_id', 'location_to_id', 'package_type_id', 'total_qty', 'status'
    ];

    public function getRules($request, $item = null)
    {
        $rules = [
            'carrier_id'   => ['nullable', 'exists:system_parameters,id'],
            'company_id'    => ['exists:companies,id'],
        ];
        return $rules;
    }

    public function details()
    {
        return $this->hasMany(TransferDetails::class, 'purchase_id');
    }

    public function location_from()
    {
        return $this->belongsTo(Location::class, 'location_from_id', 'id');
    }

    public function location_to()
    {
        return $this->belongsTo(Location::class, 'location_to_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function carrier()
    {
        return $this->belongsTo(SystemParameter::class, 'carrier_id');
    }

    public function shipment_type()
    {
        return $this->belongsTo(SystemParameter::class, 'shipment_type_id');
    }

    public function package_type()
    {
        return $this->belongsTo(SystemParameter::class, 'package_type_id');
    }
}
