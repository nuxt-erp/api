<?php

namespace Modules\Inventory\Entities;

use App\Models\ModelService;
use Illuminate\Validation\Rule;
use App\Models\Customer;

class CustomerDiscount extends ModelService
{

    protected $connection = 'tenant';

    protected $table = 'customer_discounts';
   
    protected $fillable = [
        'customer_id', 'reason', 'perc_value','start_date','end_date'
    ];

    public function getRules($request, $item = null)
    {
        $rules = [
         
            'customer_id'      => ['nullable', 'exists:tenant.customers,id'],
           

            //@todo add more validation
        ];

        // CREATE
        if (is_null($item))
        {
          //  $rules['customer_id'][]     = 'unique:tenant.customers';         
            $rules['customer_id'][]        = 'required';
        } else {
            //update
            //$rules['customer_id'][]    = Rule::unique('tenant.customers')->ignore($item->id);
           
        }

        return $rules;
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

}
