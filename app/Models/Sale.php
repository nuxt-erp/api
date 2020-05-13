<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'order_number', 'customer_id', 'sales_date', 'financial_status', 'fulfillment_status', 'fulfillment_date', 'payment_date', 'user_id', 'company_id', 'subtotal', 'discount', 'taxes', 'shipping', 'total', 'order_status_label'
    ];

    public function getRules($request, $item = null)
    {
        $rules = [
            'customer_id'   => ['nullable', 'exists:customers,id'],
            'company_id'    => ['exists:companies,id'],
        ];
        return $rules;
    }

    public function details()
    {
        return $this->hasMany(SaleDetails::class, 'purchase_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

}
