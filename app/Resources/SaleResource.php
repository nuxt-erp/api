<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SaleResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                    => $this->id,
            'order_number'          => $this->order_number,
            'sales_date'            => $this->sales_date,
            'company_id'            => $this->company_id,
            'customer_id'           => $this->customer_id,
            'customer_name'         => optional($this->customer)->name,
            'financial_status'      => $this->financial_status,
            'fulfillment_status'    => $this->fulfillment_status,
            'fulfillment_date'      => $this->fulfillment_date,
            'payment_date'          => $this->payment_date,
            'user_id'               => $this->user_id,
            'total'                 => $this->total,
            'subtotal'              => $this->subtotal,
            'taxes'                 => $this->taxes,
            'discount'              => $this->discount,
            'shipping'              => $this->shipping,
            'order_status_label'    => $this->order_status_label,
            'can_be_deleted'        => false
        ];
    }
}
