<?php

namespace Modules\Inventory\Entities;

use App\Models\ModelService;
/*
$table->foreignId('brand_id')->nullable()->constrained('inv_brands')->onDelete('set null');
$table->foreignId('category_id')->constrained('inv_categories')->onDelete('set null');
$table->foreignId('supplier_id')->nullable()->constrained()->onDelete('set null');
$table->foreignId('family_id')->nullable()->constrained('inv_families')->onDelete('set null');
$table->foreignId('location_id')->nullable()->constrained('locations')->onDelete('set null');

$table->string('dear_id')->nullable()->unique();
$table->string('name');
$table->string('sku')->unique();
$table->mediumText('description')->nullable();
$table->float('cost', 10, 4)->nullable();
$table->float('price', 10, 4)->nullable();
$table->string('barcode')->nullable()->unique();
$table->double('length', 10, 4)->nullable();
$table->double('width', 10, 4)->nullable();
$table->double('height', 10, 4)->nullable();
$table->double('weight', 10, 4)->nullable();
$table->timestamp('launch_at')->nullable();
$table->boolean('is_enabled')->default(1);
$table->timestamp('disabled_at')->nullable();
*/

class Product extends ModelService
{

    protected $table = 'inv_products';

    protected $fillable = [
        'brand_id', 'category_id', 'supplier_id',
        'family_id', 'location_id', 'dear_id',
        'name', 'sku', 'description',
        'cost', 'price', 'barcode',
        'length', 'width', 'weight',
        'launch_at', 'is_enabled', 'disabled_at'
    ];

    public function getRules($request, $item = null)
    {
        $rules = [
            'name'          => ['string', 'max:100'],
            'brand_id'      => ['nullable', 'exists:inv_brands,id'],
            'category_id'   => ['nullable', 'exists:inv_categories,id'],
            'supplier_id'   => ['nullable', 'exists:suppliers,id'],
            'family_id'     => ['nullable', 'exists:inv_families,id'],
        ];

        // CREATE
        if (is_null($item)) {
            $rules['name'][] = 'required';
        }

        return $rules;
    }

    public function getFullDescriptionAttribute()
    {
        return $this->name . ' ' . $this->getFirstAttribute();
    }

    // GET ALL ATTRIBUTES FROM PRODUCT
    public function getFirstAttribute()
    {
        $string = '';
        $attributes = $this->attributes()->get();
        if ($attributes) {
            foreach ($attributes as $key => $value) {
                if ($string == '') {
                    $string = Attribute::where('id', $value->attribute_id)->pluck('name')->first() . ': ' . $value->value;
                } else {
                    $string .= ', ' . Attribute::where('id', $value->attribute_id)->pluck('name')->first() . ': ' . $value->value;
                }
            }
        }
        return $string;
    }

    // // GET TOTAL QTY IN TRANSIT (COMING FROM SUPPLIER - PURCHASE)
    // public function getInTransitAttribute($product_id)
    // {
    //     $data = PurchaseDetails::where('product_id', $product_id)
    //         ->selectRaw('SUM(qty) as tot')
    //         ->with('purchase')
    //         ->whereHas('purchase', function ($query) {
    //             $query->where('status', '=', 0); // NOT RECEIVED YET
    //         })->get();

    //     if ($data) {
    //         return ($data[0]->tot);
    //     }
    // }

    // // GET TOTAL QTY IN TRANSIT (TRANSFERS)
    // public function getInTransitTransferAttribute($product_id)
    // {
    //     $data = TransferDetails::where('product_id', $product_id)
    //         ->selectRaw('SUM(qty_sent) as tot')
    //         ->with('transfer')
    //         ->whereHas('transfer', function ($query) {
    //             $query->where('status', '=', 0); // NOT RECEIVED YET
    //         })->get();

    //     if ($data) {
    //         return ($data[0]->tot);
    //     }
    // }

    public function attributes()
    {
        return $this->hasMany(Attribute::class, 'product_id');
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

    public function getOnlyAttribute()
    {
        return $this->getFirstAttribute();
    }
}
