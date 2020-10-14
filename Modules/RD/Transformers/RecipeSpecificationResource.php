<?php

namespace Modules\RD\Transformers;

use App\Resources\ResourceService;

class RecipeSpecificationResource extends ResourceService
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
            'id'                       => $this->id,
            'project_sample_id'        => $this->project_sample_id,
            'approver_id'              => $this->approver_id,
            'recipe_id'                => optional($this->project_sample->recipe)->id,
            'approver_name'            => optional($this->approver)->name,
            'description'              => optional($this->project_sample)->name,
            'external_code'            => optional($this->project_sample)->external_code,
            'storage_conditions'       => $this->storage_conditions,
            'shelf_life'               => $this->shelf_life,
            'appearance'               => $this->appearance,
            'aroma'                    => $this->aroma,
            'flavor'                   => $this->flavor,
            'viscosity'                => $this->viscosity,
            'specific_gravity'         => $this->specific_gravity,
            'flash_point'              => $this->flash_point,
            'created_at'               => optional($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at'               => optional($this->updated_at)->format('Y-m-d H:i:s'),
        ];
    }
}
