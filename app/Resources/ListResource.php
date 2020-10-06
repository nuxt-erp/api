<?php

namespace App\Resources;

class ListResource extends ResourceService
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // $this->model has the name of the model. e.g. Role, User
        $resource = [
            'id'            => $this->id,
            'value'         => $this->id,
            'name'          => $this->description ?? $this->name ?? '',
            'is_default'    => isset($this->is_default) ? $this->is_default : 0
        ];
        lad($this->model);
        switch ($this->model) {
            case 'Product':
                $resource['name']   = $this->sku . ' - ' . $this->name;
                $resource['sku']    = $this->sku;
                break;
            case 'Parameter':
                $resource['name']   = $this->name;
                $resource['value']  = $this->value;
            case 'Phase':
                $resource['name']   = ucwords($this->name);
                $resource['value']  = $this->name;

                break;
        }

        return $resource;
    }
}
