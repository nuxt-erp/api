<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Attribute;

class Purchase extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'purchase_date', 'company_id', 'supplier_id', 'status', 'tracking_number', 'invoice_number', 'notes', 'location_id', 'ref_code', 'total', 'subtotal', 'taxes', 'discount'
    ];

    public function getRules($request, $item = null)
    {
        $rules = [
            'location_id'   => ['nullable', 'exists:locations,id'],
            'supplier_id'   => ['nullable', 'exists:suppliers,id'],
            'company_id'    => ['exists:companies,id'],
        ];
        return $rules;
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
