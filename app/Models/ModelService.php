<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use ReflectionClass;

class ModelService extends Model implements ModelInterface
{

    protected $dateFormat = 'Y-m-d H:i:s';

    protected $casts = [
        'is_enabled' => 'int',
    ];

    public static function getTableName()
    {
        return with(new static)->getTable();
    }

    public function getRules($request, $item = null)
    {
        return [];
    }

    public static function getConstants()
    {
        // "static::class" here does the magic
        $reflectionClass = new ReflectionClass(static::class);
        return $reflectionClass->getConstants();
    }

    // public static function create(array $attributes = [])
    // {
    //     try {
    //         return static::query()->create($attributes);
    //     } catch (\Throwable $th) {
    //         return null;
    //     }
    // }

    protected function getParam($key, $value){
        $param = Parameter::firstOrCreate(
            ['name' => $key, 'value' => $value]
        );
        return $param;
    }

    protected function getParamId($key, $value){
        $param = $this->getParam($key, $value);
        return $param->id ?? null;
    }
}
