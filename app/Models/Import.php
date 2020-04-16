<?php

namespace App\Models;

class Import extends ModelService
{
    const DEAR_SYNC_LOCATIONS    = 'DEAR_SYNC_LOCATIONS';
    const DEAR_SYNC_AVAILABILITY = 'DEAR_SYNC_AVAILABILITY';
    const DEAR_SYNC_PRODUCTS     = 'DEAR_SYNC_PRODUCTS';
    const DEAR_SYNC_BRANDS       = 'DEAR_SYNC_BRANDS';
    const DEAR_SYNC_CATEGORIES   = 'DEAR_SYNC_CATEGORIES';
    const XLS_SYNC_RECIPES       = 'XLS_SYNC_RECIPES';
    const XLS_INSERT_MO          = 'XLS_INSERT_MO';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'author_id', 'rows', 'status'
    ];
}
