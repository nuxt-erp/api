<?php

namespace Modules\Sales\Transformers;

use App\Resources\ResourceService;

class SaleResource extends ResourceService
{
    public function toArray($request)
    {
        return [
            'id'                        => $this->id,
            'customer_id'               => $this->customer_id,
            'customer_name'             => optional($this->customer)->name,
            'financial_status_id'       => $this->financial_status_id,
            'financial_status_name'     => ucfirst(optional($this->financial_status)->value),
            'fulfillment_status_id'     => $this->fulfillment_status_id,
            'fulfillment_status_name'   => ucfirst(optional($this->fulfillment_status)->value),
            'author_id'                 => $this->author_id,
            'author_name'               => optional($this->author)->name,
            'order_number'              => $this->order_number,
            'discount'                  => $this->discount,
            'taxes'                     => $this->taxes,
            'shipping'                  => $this->shipping,
            'subtotal'                  => $this->subtotal,
            'total'                     => $this->total,
            'fulfillment_date'          => optional($this->fulfillment_date)->format('Y-m-d H:i:s'),
            'sales_date'                => optional($this->sales_date)->format('Y-m-d H:i:s'),
            'payment_date'              => optional($this->payment_date)->format('Y-m-d H:i:s')
        ];
    }
}
