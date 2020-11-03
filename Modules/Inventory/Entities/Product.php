<?php

namespace Modules\Inventory\Entities;

use App\Models\Location;
use App\Models\ModelService;
use App\Models\Supplier;
use Modules\Purchase\Entities\PurchaseDetail;

class Product extends ModelService
{

    protected $connection = 'tenant';

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
        'disabled_at', 'sales_channel','stock_locator',
        'measure_id', 'carton_length', 'carton_width',
        'carton_height', 'carton_weight', 'msrp',
        'carton_barcode', 'carton_qty','taxable'
    ];


    public function getRules($request, $item = null)
    {
        $rules = [
            'name'          => ['string', 'max:100'],
            'brand_id'      => ['nullable', 'exists:tenant.inv_brands,id'],
            'category_id'   => ['nullable', 'exists:tenant.inv_categories,id'],
            'supplier_id'   => ['nullable', 'exists:tenant.suppliers,id'],
            'family_id'     => ['nullable', 'exists:tenant.inv_families,id'],
            'stock_locator' => ['nullable', 'exists:tenant.inv_stock_locator,id'],
            'measure_id'    => ['nullable', 'exists:tenant.inv_measure,id'],
            'location_id'   => ['nullable', 'exists:tenant.locations,id'],
            'carton_barcode'=> ['nullable', 'string', 'max:255'],
            'carton_qty'    => ['nullable', 'numeric']
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
            $string .= ($key == 0  ? '' : ', ') . $p_attribute->attribute->name . ': ' . $p_attribute->value;
        }
        return $string;
    }

    // GET TOTAL QTY IN TRANSIT (COMING FROM SUPPLIER - PURCHASE)
    public function getInTransitAttribute($product_id)
    {
        $data = PurchaseDetail::where('product_id', $product_id)
            ->selectRaw('SUM(qty) as tot')
            ->with('purchase')
            ->whereHas('purchase', function ($query) {
                $query->where('pur_purchases.status', '=', 0); // NOT RECEIVED YET
            })->first();

        if ($data) {
            return ($data->tot);
        }
        return 0;
    }

    // GET TOTAL QTY IN TRANSIT (TRANSFERS)
    public function getInTransitTransferAttribute($product_id)
    {
        $data = TransferDetails::where('product_id', $product_id)
            ->selectRaw('SUM(qty_sent) as tot')
            ->with('transfer')
            ->whereHas('transfer', function ($query) {
                $query->where('inv_transfers.is_enable', '=', 0); // NOT RECEIVED YET
            })->first();

        if ($data) {
            return ($data->tot);
        }
        return 0;
    }

    public function product_attributes(){
        return $this->hasMany(ProductAttributes::class, 'product_id', 'id');
    }

    public function availabilities()
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

    public function stockLocator()
    {
        return $this->belongsTo(StockLocator::class);
    }
    public function measure()
    {
        return $this->belongsTo(Measure::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImages::class, 'product_id', 'id');
    }

    public function reorderLevels()
    {
        return $this->hasMany(ProductReorderLevel::class, 'product_id', 'id');
    }

    public function tags()
    {
        return $this->hasMany(ProductTag::class, 'product_id', 'id');
    }


}
