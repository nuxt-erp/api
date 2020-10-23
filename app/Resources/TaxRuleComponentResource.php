<?php

namespace App\Resources;


class TaxRuleComponentResource extends ResourceService
{
 
    public function toArray($request)
    {
        return [
            'id'                    => $this->id,
            'tax_rule_id'           => $this->tax_rule_id,
            'component_name'        => $this->component_name,
            'rate'                  => $this->rate,
            'compound'              => $this->compound,
            'seq'                   => $this->seq,
            'tax_rule_name'         => optional($this->tax_rule)->name,
            'created_at'            => optional($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at'            => optional($this->updated_at)->format('Y-m-d H:i:s'),
        ];
    }
}
