<?php

namespace App\Resources;


class TaxRuleResource extends ResourceService
{
    public function toArray($request)
    {
        return [
            'id'                    => $this->id,
            'name'                  => $this->name,
            'short_name'            => $this->short_name,
            'computation'           => $this->computation,
            'status'                => $this->status,
            'province_id'           => $this->province_id,
            'province_name'         => optional($this->province)->name,
            'province_code'         => optional($this->province)->code,
            'scopes'                => implode(', ', $this->scopes->pluck('scope')->toArray()),
            'created_at'            => optional($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at'            => optional($this->updated_at)->format('Y-m-d H:i:s'),
        ];
    }
}
