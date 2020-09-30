<?php
namespace Modules\Inventory\Entities;

use App\Models\ModelService;
use Illuminate\Validation\Rule;
use App\Models\Location;
use App\Models\Parameter;

class Transfer extends ModelService
{
    protected $connection = 'tenant';

    public $table       = "inv_transfers";

    protected $fillable = [
        'pu_date', 'created_at', 'updated_at',
        'carrier_id', 'eta', 'shipment_type_id',
        'tracking_number', 'location_from_id', 'location_to_id',
        'package_type_id', 'total_qty', 'is_enable'
    ];

    public function getRules($request, $item = null)
    {
        $rules = [
           // 'carrier_id'   => ['nullable', 'exists:parameters,id'],
        ];
        return $rules;
    }

    public function details()
    {
        return $this->hasMany(TransferDetails::class, 'transfer_id');
    }

    public function location_from()
    {
        return $this->belongsTo(Location::class, 'location_from_id', 'id');
    }

    public function location_to()
    {
        return $this->belongsTo(Location::class, 'location_to_id');
    }

    public function parameter_carrier()
    {
        return $this->belongsTo(Parameter::class, 'carrier_id');
    }

    public function parameter_shipment()
    {
        return $this->belongsTo(Parameter::class, 'shipment_type_id');
    }

    public function parameter_package()
    {
        return $this->belongsTo(Parameter::class, 'package_type_id');
    }
}
