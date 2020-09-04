<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use ReflectionClass;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;

class ModelService extends Model implements ModelInterface
{

    protected function newBaseQueryBuilder()
    {
        $user = auth()->user();
        if($user && empty(config('database.connections.tenant.schema'))){
            $company = DB::table('companies')->find($user->company_id);
            lad('new schema');
            lad($company->schema);
            config(['database.connections.tenant.schema' => $company->schema]);
            DB::reconnect('tenant');
        }

        return parent::newBaseQueryBuilder();
    }

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
