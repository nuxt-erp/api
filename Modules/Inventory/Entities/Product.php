<?php

namespace Modules\Inventory\Entities;

use App\Models\Location;
use App\Models\ModelService;
use App\Models\Supplier;

class Product extends ModelService
{

    protected $table = 'inv_products';

    protected $dates = [
        'disabled_at', 'launch_at'
    ];

    protected $fillable = [
        'brand_id', 'category_id', 'supplier_id',
        'family_id', 'location_id', 'dear_id',
        'name', 'sku', 'description',
        'cost', 'price', 'barcode',
        'length', 'width', 'height',
        'weight', 'launch_at', 'is_enabled',
        'disabled_at', 'sales_channel'
    ];


    public function getRules($request, $item = null)
    {
        $rules = [
            'name'          => ['string', 'max:100'],
            'brand_id'      => ['nullable', 'exists:inv_brands,id'],
            'category_id'   => ['nullable', 'exists:inv_categories,id'],
            'supplier_id'   => ['nullable', 'exists:suppliers,id'],
            'family_id'     => ['nullable', 'exists:inv_families,id'],
            //@todo add more validation
        ];

        // CREATE
        if (is_null($item)) {
            $rules['name'][] = 'required';
        }

        return $rules;
    }

    public function getFullDescriptionAttribute()
    {
        return $this->name . ' ' . $this->details;
    }

    // GET ALL ATTRIBUTES FROM PRODUCT
    public function getDetailsAttribute()
    {
        $string = '';
        foreach ($this->product_attributes as $key => $p_attribute) {
            //lad($attribute);
            $string .= ($key == 0  ? '' : ', ') . $p_attribute->attribute->name . ': ' . $p_attribute->value;
        }
        return $string;
    }

    // GET TOTAL QTY IN TRANSIT (COMING FROM SUPPLIER - PURCHASE)
    public function getInTransitAttribute($product_id)
    {
        return 0;
        // $data = PurchaseDetails::where('product_id', $product_id)
        //     ->selectRaw('SUM(qty) as tot')
        //     ->with('purchase')
        //     ->whereHas('purchase', function ($query) {
        //         $query->where('status', '=', 0); // NOT RECEIVED YET
        //     })->get();

        // if ($data) {
        //     return ($data[0]->tot);
        // }
    }

    // GET TOTAL QTY IN TRANSIT (TRANSFERS)
    public function getInTransitTransferAttribute($product_id)
    {
        return 0;
        // $data = TransferDetails::where('product_id', $product_id)
        //     ->selectRaw('SUM(qty_sent) as tot')
        //     ->with('transfer')
        //     ->whereHas('transfer', function ($query) {
        //         $query->where('status', '=', 0); // NOT RECEIVED YET
        //     })->get();

        // if ($data) {
        //     return ($data[0]->tot);
        // }
    }

    public function product_attributes(){
        return $this->hasMany(ProductAttributes::class, 'product_id', 'id');
    }

    public function availability()
    {
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

}
