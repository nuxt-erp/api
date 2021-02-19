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
            'name'          => $this->name ?? '',
            'label'         => !empty($this->description) ? $this->description : ($this->name ?? ''),
            'description'   => $this->description,
            'is_default'    => isset($this->is_default) ? $this->is_default : 0
        ];
        lad($this->model);
        switch ($this->model) {
            case 'Product':
                $resource['label']             = $this->sku . ' - ' . $this->name;
                $resource['sku']               = $this->sku;
                $resource['cost']              = $this->cost;
                $resource['complete_name']     = $this->sku . ' - ' . $this->name;
                $resource['display_name']      = $this->getDetailsAttributeValue();
                break;
            case 'Parameter':
                $resource['name']           = $this->name;
                $resource['description']    = $this->description ?? $this->name;
                $resource['value']          = $this->value;
                break;
            case 'ParameterType':
                $resource['value']  = $this->value;
                $resource['id']     = $this->value;
                break;
            case 'Phase':
                $resource['name']   = ucwords($this->name);
                $resource['value']  = $this->name;
                break;
            case 'Recipe':
                $resource['name']    = $this->type ? ($this->type->value . '-' . $this->id . ' - ' . $this->name) : $this->name;
                break;
            case 'Purchase':
                $resource['name']   = $this->po_number;
                $resource['label']  = $this->po_number;
                break;
        }

        return $resource;
    }
}
