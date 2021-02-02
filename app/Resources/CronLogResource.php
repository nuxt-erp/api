<?php

namespace App\Resources;

class CronLogResource extends ResourceService
{
    public function toArray($request)
    {
        return [
            'id'             => $this->id,
            'error'          => $this->error,
            'import_count'   => $this->import_count,
            'execution_time' => $this->execution_time,
            'created_at'     => optional($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at'     => optional($this->updated_at)->format('Y-m-d H:i:s'),
        ];
    }
}
