<?php

namespace Modules\Inventory\Entities;

use App\Models\Location;
use App\Models\Parameter;
use Modules\Sales\Entities\Sale;
use Modules\Purchase\Entities\Purchase;
use App\Models\User;

use App\Models\ModelService;
use Illuminate\Validation\Rule;

class ProductLog extends ModelService
{

    protected $connection = 'tenant';

    protected $table = 'inv_product_logs';

    const TYPE_LOG_SALE         = 'Sale';
    const TYPE_LOG_PURCHASE     = 'Purchase';
    const TYPE_LOG_TRANSFER     = 'Transfer';
    const TYPE_LOG_ADJUSTMENT   = 'Adjustment';
    const TYPE_LOG_STOCK_COUNT  = 'Stock Count';

    protected $fillable = [
        'product_id', 'location_id', 'type_id',
        'ref_code_id', 'quantity', 'description',
        'user_id', 'bin_id'

    ];

    public function getRules($request, $item = null)
    {
        $rules = [
            'product_id'    => ['exists:tenant.inv_products,id'],
            'location_id'   => ['nullable', 'exists:tenant.locations,id'],
            'bin_id'        => ['nullable', 'exists:tenant.inv_location_bins,id'],
            'type_id'       => ['nullable', 'exists:tenant.parameters,id'],
            'ref_code_id'   => ['nullable', 'integer'],
            'quantity'      => ['nullable', 'number'],
            'description'   => ['nullable', 'string', 'max:255'],
            'user_id'       => ['nullable', 'exists:tenant.users,id']
        ];

        // CREATE
        if (is_null($item))
        {
            $rules['product_id'][] = 'required';
        } else {
            //update
        }

        return $rules;
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
    public function type()
    {
        return $this->belongsTo(Parameter::class);
    }
    public function getSourceAttribute()
    {

        if ($this->type->value == self::TYPE_LOG_SALE) {
            $get = Sale::where('id', $this->ref_code_id)->with('customer')->first();
            if ($get) {
                return optional($get->customer)->name;
            }
        }elseif ($this->type->value == self::TYPE_LOG_PURCHASE) {
            $get = Purchase::where('id', $this->ref_code_id)->with('supplier')->first();
            if ($get) {
                return optional($get->supplier)->name;
            }
        }

    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function bin()
    {
        return $this->belongsTo(LocationBin::class, 'bin_id', 'id');
    }

}

