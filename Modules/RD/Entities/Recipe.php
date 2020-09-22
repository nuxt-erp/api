<?php

namespace Modules\RD\Entities;
use App\Models\ModelService;
use App\Models\User;
use App\Models\Parameter;
use Modules\Inventory\Entities\Product;
use Modules\Inventory\Entities\Category;
use Illuminate\Validation\Rule;

class Recipe extends ModelService
{
    protected $connection = 'tenant';

    protected $table = 'rd_recipes';

    protected $fillable = [
        'author_id', 'last_updater_id', 'approver_id',
        'type_id', 'product_id', 'status',
        'name', 'category', 'total',
        'code', 'cost', 'version',
        'approved_at'
    ];

    public function getRules($request, $item = null)
    {

        // generic rules
        $rules = [

            'author_id'             => ['nullable', 'exists:public.users,id'],
            'last_updater_id'       => ['nullable', 'exists:public.users,id'],
            'approver_id'           => ['nullable', 'exists:public.users,id'],
            'type_id'               => ['nullable', 'exists:tenant.parameters,id'],
            'product_id'            => ['nullable', 'exists:tenant.inv_products,id'],
            'category_id'           => ['exists:tenant.inv_categories,id'],
            'status'                => ['string', 'max:255'],
            'name'                  => ['string', 'max:255'],
            'code'                  => ['nullable', 'max:255'],
            'cost'                  => ['nullable'],
            'version'               => ['integer'],
            'approved_at'           => ['nullable', 'date']

        ];

        // rules when creating the item
        if (is_null($item)) {

            $rules['status'][] = 'required';
            $rules['name'][] = 'required';
            $rules['version'][] = 'required';
            $rules['category_id'][] = 'required';
        }
        // rules when updating the item
        else{
        }
        return $rules;

    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }
    public function attributes()
    {
        return $this->belongsToMany(Parameter::class, 'rd_recipe_attributes', 'recipe_id', 'attribute_id');

    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function lastUpdater()
    {
        return $this->belongsTo(User::class, 'last_updater_id');
    }

    public function type()
    {
        return $this->belongsTo(Parameter::class, 'type_id');

    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

}
