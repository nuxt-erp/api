<?php

namespace Modules\Production\Entities;

use App\Models\ModelService;
use Illuminate\Validation\Rule;

class OperationResult extends ModelService
{
    protected $connection = 'tenant';

    protected $table = 'prod_operation_results';

    protected $dates = [
        'start_at', 'end_at'
    ];

    protected $fillable = [
        'production_id', 'operation_id', 'author_id',
        'start_at', 'end_at', 'machine_id', 'handled_qty',
        'to_handle_qty', 'handled_volume',  'to_handle_volume',  
        'process_code', 'comment'
    ];


    public function getRules($request, $item = null)
    {
        // generic rules
        $rules = [
            'production_id'         => ['exists:tenant.prod_productions,id'],
            'operation_id'          => ['nullable', 'exists:tenant.prod_operations,id'],
            'author_id'             => ['exists:users,id'],
            'start_at'              => ['nullable', 'date'],
            'end_at'                => ['nullable', 'date'],
            'machine_id'            => ['nullable', 'exists:tenant.prod_machines,id'],
            'handled_qty'           => ['integer'],
            'to_handle_qty'         => ['integer'],
            'process_code'          => ['nullable', 'string', 'max:255'],
            'comment'               => ['nullable', 'string', 'max:255']

        ];

        // rules when creating the item
        if (is_null($item)) {
            $rules['handled_qty'][] = 'required';
            $rules['to_handle_qty'][] = 'required';
            $rules['handled_volume'][] = 'required';
            $rules['to_handle_volume'][] = 'required';
        }

        return $rules;
    }

    public function production()
    {
        return $this->belongsTo(Production::class, 'production_id');
    }

    public function operation()
    {
        return $this->belongsTo(Operation::class, 'operation_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
