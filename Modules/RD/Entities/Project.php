<?php

namespace Modules\RD\Entities;

use App\Models\ModelService;
use Illuminate\Validation\Rule;

class Project extends ModelService
{
    protected $connection = 'tenant';

    protected $table = 'rd_projects';

    protected $fillable = ['customer_id', 'user_id', 'is_enable', 'comments', 'closed_at'];

    public function getRules($request, $item = null)
    {
        // generic rules
        $rules = [
            'author_id'             => ['exists:users,id'],
            'customer_id'           => ['nullable', 'exists:customers,id'],
            'status'                => ['max:255'],
            'code'                  => ['max:255'],
            'comments'              => ['max:255'],
            'start_at'              => ['nullable', 'date'],
            'closed_at'             => ['nullable', 'date']
        ];

        // rules when creating the item
        if (is_null($item)) {
            $rules['author_id'][] = 'required';
            $rules['status'][] = 'required';
            $rules['code'][] = 'required';
            $rules['comments'][] = 'required';

        }
        // rules when updating the item
        else{

        }

        return $rules;
    }
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }
}
