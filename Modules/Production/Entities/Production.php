<?php

namespace Modules\Production\Entities;

use App\Models\ModelService;
use Illuminate\Validation\Rule;

class Production extends ModelService
{
    protected $connection = 'tenant';

    protected $table = 'prod_productions';

    protected $fillable = [
        'machine_id', 'phase_id', 'previous_phase_id',
        'location_id', 'product_id', 'author_id', 
        'last_updater_id','relation_id', 'relation_type', 
        'requester_id', 'requester_type', 'status', 
        'code', 'reference_code', 'sequence', 'scheduled',
        'requested_qty', 'requested_volume','scheduled_qty', 
        'scheduled_volume', 'finished_qty', 'finished_volume', 
        'started_at', 'finished_at', 'expected_start_date', 
        'expected_finish_date'
    ];


    public function getRules($request, $item = null)
    {
        // generic rules
        $rules = [
            'machine_id'           => ['nullable', Rule::exists('tenant.prod_machines', 'id')->where(function ($query) {
                $query->whereNotNull('flow_id');
            })],
            'phase_id'             => ['nullable', 'exists:tenant.prod_phases,id'],
            'previous_phase_id'    => ['nullable', 'exists:tenant.prod_phases,id'],
            'location_id'          => ['nullable', 'exists:locations,id'],
            'product_id'           => ['exists:inv_products,id'],
            'author_id'            => ['nullable', 'exists:users,id'],
            'last_updater_id'      => ['nullable', 'exists:users,id'],
            'relation_id'          => ['nullable'],
            'relation_type'        => ['nullable', 'max:255'],
            'requester_id'         => ['nullable', 'exists:users,id'],
            'requester_type'       => ['nullable', 'max:255'],
            'status'               => ['string', 'max:255'],
            'code'                 => ['string', 'max:255'],
            'reference_code'       => ['nullable', 'max:255'],
            'sequence'             => ['nullable', 'integer'],
            'scheduled'            => ['nullable', 'boolean'],
            'requested_qty'        => ['integer'],
            'requested_volume'     => ['nullable', 'decimal'],
            'scheduled_qty'        => ['integer'],
            'scheduled_volume'     => ['nullable', 'decimal'],
            'finished_qty'         => ['nullable', 'integer'],
            'finished_volume'      => ['nullable', 'decimal'],
            'started_at'           => ['nullable', 'date'],
            'finished_at'          => ['nullable', 'date'],
            'expected_start_date'  => ['nullable', 'date'],
            'expected_finish_date' => ['nullable', 'date']
        ];

        // rules when creating the item
        if (is_null($item)) {
            $rules['product_id'][]      = 'required';
            $rules['status'][]          = 'required';
            $rules['code'][]            = 'required';
            $rules['digit'][]           = 'required';
            $rules['requested_qty'][]   = 'required';
            $rules['scheduled_qty'][]   = 'required';
        }
        // rules when updating the item
        else{

        }

        return $rules;
    }
    public function phase()
    {
        return $this->belongsTo(Machine::class, 'phase_id');
    }

    public function previous_phase()
    {
        return $this->belongsTo(Machine::class, 'previous_phase_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function flow()
    {
        return $this->machine && $this->machine->flow;
    }

    public function machine()
    {
        return $this->belongsTo(Machine::class, 'machine_id');
    }
    
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function requester()
    {
        return $this->belongsTo(Employee::class, 'requester_id');
    }

    public function last_updater()
    {
        return $this->belongsTo(User::class, 'last_updater_id');
    }

    

}
