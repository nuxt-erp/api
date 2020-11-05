<?php

namespace Modules\Sales\Entities;

use App\Models\Customer;
use App\Models\ModelService;
use App\Models\Parameter;
use App\Models\User;
use Illuminate\Validation\Rule;

class Sale extends ModelService
{
    protected $connection = 'tenant';

    protected $table = 'sal_sales';

    protected $dates = [
        'fulfillment_date', 'sales_date', 'payment_date'
    ];

    protected $fillable = [
        'customer_id', 'financial_status_id', 'fulfillment_status_id',
        'author_id', 'order_number', 'discount',
        'taxes', 'shipping', 'subtotal',
        'total', 'fulfillment_date', 'sales_date',
        'payment_date'
    ];

    public function getRules($request, $item = null)
    {
        $rules = [
            'customer_id'           => ['nullable', 'exists:tenant.customers,id'],
            'financial_status_id'   => ['nullable', 'exists:tenant.parameters,id'],
            'fulfillment_status_id' => ['nullable', 'exists:tenant.parameters,id'],
            'author_id'             => ['nullable', 'exists:tenant.users,id'],
            //@todo add more validation
        ];
        // CREATE
        if (is_null($item)){
            $rules['order_number'][] = 'unique:tenant.sal_sales';
            $rules['order_number'][] = 'required';
        } else {
            //update
            $rules['order_number'][] = Rule::unique('tenant.sal_sales')->ignore($item->id);
        }

        return $rules;
    }

    public function details()
    {
        return $this->hasMany(SaleDetails::class, 'sale_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class,'customer_id');
    }

    public function financial_status()
    {
        return $this->belongsTo(Parameter::class, 'financial_status_id');
    }

    public function fulfillment_status()
    {
        return $this->belongsTo(Parameter::class, 'fulfillment_status_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class);
    }
}
