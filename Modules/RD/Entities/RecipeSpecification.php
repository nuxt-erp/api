<?php

namespace Modules\RD\Entities;

use App\Models\ModelService;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Parameter;



class RecipeSpecification extends ModelService
{
    protected $connection = 'tenant';

    protected $table = 'rd_recipe_specification';

    protected $fillable = [
        'project_sample_id', 'approver_id', 'appearance',
        'aroma', 'flavor', 'viscosity',
        'specific_gravity', 'flash_point', 'storage_conditions',
        'shelf_life', 'ingredient_list'
    ];
    protected $casts = [
        'viscosity' => 'string',
        'flash_point' => 'string',
        'shelf_life' => 'string',
        'specific_gravity' => 'string',
    ];

    public function getRules($request, $item = null)
    {
        //TODO: fix cast, we shouldn't skip the length validation this is a temporary fix.
        // generic rules
        $rules = [
            'project_sample_id'   => ['exists:tenant.rd_project_samples,id'],
            'approver_id'         => ['exists:public.users,id'],
            'appearance'          => ['string', 'max:255'],
            'aroma'               => ['string', 'max:255'],
            'flavor'              => ['string', 'max:255'],
            // 'viscosity'           => ['string', 'max:255'],
            // 'specific_gravity'    => ['string', 'max:255'],
            //'flash_point'         => ['string', 'max:255'],
            // 'shelf_life'          => ['string', 'max:255'],
        ];

        // rules when creating the item
        if (is_null($item)) {
            $rules['project_sample_id'][]   = 'required';
            $rules['appearance'][]          = 'required';
            $rules['aroma'][]               = 'required';
            $rules['flavor'][]              = 'required';
            $rules['viscosity'][]           = 'required';
            $rules['specific_gravity'][]    = 'required';
            $rules['flash_point'][]         = 'required';
            $rules['storage_conditions'][]  = 'required';
            $rules['shelf_life'][]          = 'required';
            $rules['ingredient_list'][]     = 'required';
        }
        // rules when updating the item
        else{ }

        return $rules;
    }
    public function project_sample()
    {
        return $this->belongsTo(ProjectSamples::class, 'project_sample_id', 'id');
    }
    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id', 'id');
    }
    public function attributes()
    {
        return $this->belongsToMany(Parameter::class, 'rd_recipe_specification_attributes', 'recipe_specification_id', 'attribute_id');
    }
    public function packing(){
        return $this->attributes()->where('name', '=', 'recipe_spec_packing');
    }
    public function spec_attributes(){
        return $this->attributes()->where('name', '=', 'recipe_spec_attributes');
    }

}
