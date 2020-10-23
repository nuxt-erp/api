<?php

namespace Modules\Inventory\Transformers;

use App\Resources\ResourceService;

class ProductImportSettingsResource extends ResourceService
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        return [
            'id'            => $this->id,
            'column_name'   => $this->column_name,
            'custom_name'   => $this->custom_name
        ];
    }
}
