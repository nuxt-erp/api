<?php

namespace Modules\Sales\Entities;

use App\Models\ModelService;
use Illuminate\Validation\Rule;

class DiscountTag extends ModelService
{
    protected $connection = 'tenant';

    protected $table = 'sal_discount_tags';

    protected $fillable = [
        'discount_id', 'tag_id', 'type'
    ];

    public function getRules($request, $item = null)
    {
        // generic rules
        $rules = [
            
            'tag_id'                => ['exists:tenant.tags,id'],
            'discount_id'           => ['nullable', 'exists:tenant.sal_discounts,id'],
            'type'                  => ['string', 'max:255']
 
        ];

        // rules when creating the item
        if (is_null($item)) {
            $rules['tag_id'][] = 'required';
            $rules['discount_id'][] = 'required';
            $rules['type'][] = 'required';
        }

        return $rules;
    }
    public function tag()
    {
        return $this->belongsTo(Tag::class, 'tag_id');
    }
    public function discount()
    {
        return $this->belongsTo(Discount::class, 'discount_id');
    }
}
