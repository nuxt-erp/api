<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ResourceService extends JsonResource
{
    protected $model;

    public function __construct($resource)
    {
        parent::__construct($resource);
        $this->model = (new \ReflectionClass($resource))->getShortName();
    }
}
