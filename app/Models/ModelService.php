<?php

namespace App\Models;

//use Hoyvoy\CrossDatabase\Eloquent\Model;

use Illuminate\Database\Eloquent\Model;
use ReflectionClass;
use Illuminate\Support\Str;

class ModelService extends Model implements ModelInterface
{

    protected $dateFormat = 'Y-m-d H:i:s';

    protected $casts = [
        'is_enabled' => 'int',
    ];

    public function getRules($request, $item = null)
    {
        return [];
    }

    public static function getConstants()
    {
        $reflectionClass    = new ReflectionClass(static::class);
        $allConstants       = $reflectionClass->getConstants();

        $rc                 = new \ReflectionClass(get_parent_class(static::class));
        $parentConstants    = $rc->getConstants();

        return array_diff($allConstants, $parentConstants);
    }

}
