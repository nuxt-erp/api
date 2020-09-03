<?php

namespace Modules\RD\Entities;

use App\Models\ModelService;
use App\Models\Parameter;
use Illuminate\Validation\Rule;

class Recipe extends ModelService
{
    protected $connection = 'tenant';

    protected $table = 'rd_recipes';

    protected $fillable = ['name', 'code', 'cost', 'status', 'version'];

    public function getRules($request, $item = null)
    {
        
        // generic rules
        $rules = [
            'author_id'             => ['nullable', 'exists:users,id'],
            'last_updater_id'       => ['nullable', 'exists:users,id'],
            'approver_id'           => ['nullable', 'exists:users,id'],
            'type_id'               => ['nullable', 'exists:parameters,id'],
            'product_id'            => ['nullable', 'exists:tenant.inv_products,id'],
            'status'                => ['max:255'],
            'name'                  => ['max:255'],
            'total'                 => ['max:255'],
            'code'                  => ['nullable', 'max:255'],
            'cost'                  => ['float'],
            'version'               => ['integer'],
            'approved_at'           => ['nullable', 'date']

        ];

        // rules when creating the item
        if (is_null($item)) {

            $rules['status'][] = 'required';
            $rules['name'][] = 'required';
            $rules['total'][] = 'required';
            $rules['version'][] = 'required';
            $rules['category'][] = 'required';
        }
        // rules when updating the item
        else{
            // $rules['version'][]     =
            // function ($attribute, $value, $fail) use ($item) {
            //     $current_version = $item->version;
            //     if ($value < $current_version) {
            //         $fail('new version < old version');
            //     }
            // };
        }
        return $rules;

    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }
    
    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id', 'id');
    }

    public function lastUpdater()
    {
        return $this->belongsTo(User::class, 'last_updater_id', 'id');
    }

    public function type()
    {
        return $this->belongsTo(Parameters::class, 'type_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
  
}
