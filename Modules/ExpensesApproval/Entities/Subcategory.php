<?php

namespace Modules\ExpensesApproval\Entities;

use App\Models\ModelService;
use App\Models\User;
use Illuminate\Validation\Rule;

class Subcategory extends ModelService
{
    protected $connection = 'tenant';

    protected $table = 'exp_ap_subcategories';

    protected $fillable = [
        'name', 'expenses_category_id'
    ];

    public function getRules($request, $item = null)
    {
        $rules = [
            'expenses_category_id'  => ['exists:tenant.exp_ap_categories,id'],
            'name'                  => ['string', 'max:255'],            
        ];

        // CREATE
        if (is_null($item))
        {
            $rules['name'][]                    = 'required';
            $rules['expenses_category_id'][]    = 'required';

        } 
        
        return $rules;
    }   

    public function category()
    {
        return $this->belongsTo(Category::class, 'expenses_category_id');
    }
}
