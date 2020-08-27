<?php

namespace Modules\ExpensesApproval\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class ExpensesProposalResource extends JsonResource
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
            'id'                        => $this->id,
            'expenses_category_id'      => $this->expenses_category_id,
            'expenses_category_name'    => $this->category->name, 
            'author_id'                 => $this->author_id,
            'author_name'               => $this->author->name,
            'item'                      => $this->item,
            'reason'                    => $this->reason,
            'supplier_link'             => $this->supplier_link,
            'subtotal'                  => $this->subtotal,
            'hst'                       => $this->hst,                   
            'ship'                      => $this->ship,
            'total_cost'                => $this->total_cost,            
            'status'                    => $this->status->description,
            'approvers'                 => $this->approvers,
            'approvals'                 => $this->approvals,
            'attachments'               => $this->attachments,
            'purchase_date'             => optional($this->purchase_date)->format('Y-m-d H:i:s'),
            'created_at'                => optional($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at'                => optional($this->updated_at)->format('Y-m-d H:i:s'),
        ];
    }
}
