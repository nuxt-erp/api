<?php

namespace Modules\Inventory\Entities;

use App\Models\ModelService;

class Product extends ModelService
{
    public $timestamps = false;

    protected $fillable = [
        'sku', 'name', 'description',
        'cost', 'status', 'barcode',
        'sales_chanel', 'brand_id', 'category_id',
        'supplier_id', 'width', 'length',
        'weight', 'height', 'price',
        'family_id', 'launch_date', 'dear'
    ];

    public function getRules($request, $item = null)
    {
        $rules = [
            'name'          => ['string', 'max:100'],
            'brand_id'      => ['nullable', 'exists:brands,id'],
            'category_id'   => ['nullable', 'exists:categories,id'],
            'supplier_id'   => ['nullable', 'exists:suppliers,id'],
        ];

        // CREATE
        if (is_null($item))
        {
            $rules['name'][] = 'required';
            // $rules['sku'][]  = 'required';
            // $rules['sku'][]  = 'unique:products';
        }

        return $rules;
    }


    public function attributes() {
        return $this->hasMany(Attribute::class, 'product_id');
    }

    public function availability() {
        return $this->hasMany(Availability::class, 'product_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function getDescriptionAttribute()
    {
        return $this->name . ' ' . $this->getFirstAttribute();
    }

    public function getOnlyAttribute()
    {
        return $this->getFirstAttribute();
    }

    // GET ALL ATTRIBUTES FROM PRODUCT
    public function getFirstAttribute()
    {
        $string = '';
        $attributes = $this->attributes()->get();
        if ($attributes)
        {
            foreach ($attributes as $key => $value)
            {
                if ($string == '') {
                    $string = Attribute::where('id', $value->attribute_id)->pluck('name')->first() . ': ' . $value->value;
                } else {
                    $string .= ', ' . Attribute::where('id', $value->attribute_id)->pluck('name')->first() . ': ' . $value->value;
                }
            }
        }
        return $string;
    }

    // GET TOTAL QTY IN TRANSIT (COMING FROM SUPPLIER - PURCHASE)
    public function getInTransitAttribute($product_id)
    {
        $data = PurchaseDetails::where('product_id', $product_id)
        ->selectRaw('SUM(qty) as tot')
        ->with('purchase')
        ->whereHas('purchase', function ($query) {
            $query->where('status', '=', 0); // NOT RECEIVED YET
        })->get();

        if($data) {
            return ($data[0]->tot);
        }
    }

    // GET TOTAL QTY IN TRANSIT (TRANSFERS)
    public function getInTransitTransferAttribute($product_id)
    {
        $data = TransferDetails::where('product_id', $product_id)
        ->selectRaw('SUM(qty_sent) as tot')
        ->with('transfer')
        ->whereHas('transfer', function ($query) {
            $query->where('status', '=', 0); // NOT RECEIVED YET
        })->get();

        if($data) {
            return ($data[0]->tot);
        }
    }

}