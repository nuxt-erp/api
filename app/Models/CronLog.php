<?php

namespace App\Models;

class CronLog extends ModelService
{
    protected $connection = 'tenant';

    protected $table = 'cron_logs';

    protected $fillable = [
        'error', 'import_count', 'execution_time'
    ];

    protected $dates = [
        'created_at', 'updated_at'
    ];

    public function getRules($request, $item = null)
    {
        $rules = [];

        // CREATE
        if (is_null($item)) {
        }

        return $rules;
    }
}
