<?php

namespace App\Resources;


class TaxRuleScopeResource extends ResourceService
{
 
    public function toArray($request)
    {
        return [
            'id'                    => $this->id,
            'tax_rule_id'           => $this->tax_rule_id,
            'tax_rule_name'         => optional($this->tax_rule)->name,
            'created_at'            => optional($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at'            => optional($this->updated_at)->format('Y-m-d H:i:s'),
        ];
    }
}
