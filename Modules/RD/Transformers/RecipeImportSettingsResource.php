<?php

namespace Modules\RD\Transformers;

use App\Resources\ResourceService;

class RecipeImportSettingsResource extends ResourceService
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'column_name'   => $this->column_name,
            'custom_name'   => $this->custom_name,
            'entity'        => $this->entity
        ];
    }
}
