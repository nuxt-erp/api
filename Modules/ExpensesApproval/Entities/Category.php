<?php

namespace Modules\ExpensesApproval\Entities;

use App\Models\BelongsToManyTenantTrait;
use App\Models\ModelService;
use App\Models\User;
use Illuminate\Validation\Rule;

class Category extends ModelService
{

    use BelongsToManyTenantTrait;

    protected $connection = 'tenant';

    protected $table = 'exp_ap_categories';

    protected $dates = [
        'finished_at',
    ];

    protected $fillable = [
        'name', 'lead_id', 'buyer_id',
        'is_finished', 'finished_at'
    ];

    public function getRules($request, $item = null)
    {
        $rules = [
            'name'              => ['string', 'max:255'],
            'lead_id'           => ['nullable', 'exists:public.users,id'],
            'buyer_id'          => ['nullable', 'exists:public.users,id'],
            'is_finished'       => ['nullable', 'boolean'],
            'finished_at'       => ['nullable', 'date']
        ];

        // CREATE
        if (is_null($item))
        {
            // $rules['name'][]            = 'unique:tenant.exp_ap_categories,id';
            $rules['name'][]            = 'required';
            $rules['lead_id'][]         = 'required';
            $rules['buyer_id'][]        = 'required';

        } else {
            //update
            // $rules['name'][] = Rule::unique('tenant.exp_ap_categories')->ignore($item->id);
        }

        return $rules;
    }

    public function lead()
    {
        return $this->belongsTo(User::class, 'lead_id');
    }

    public function sponsors()
    {
        return $this
        ->setConnection('tenant')
        ->belongsToManyTenant(User::class, 'exp_ap_category_sponsors', null, 'expenses_category_id', 'sponsor_id')
        ->using(CategorySponsors::class)
        ->withTimestamps();
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }
}
