<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopifySync extends Model
{
    public $timestamps  = false;
    public $table       = "shopify_sync";

    protected $fillable = [
        'company_id', 'shopify_api_key', 'shopify_api_pwd', 'shopify_store_name', 'shopify_location_id', 'shopify_api_version', 'sync_user_id'
    ];

    public function getRules($request, $item = null)
    {
        $rules = [
            'company_id'    => ['exists:companies,id'],
        ];

        return $rules;
    }
}
