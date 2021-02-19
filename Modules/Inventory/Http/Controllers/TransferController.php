<?php

namespace Modules\Inventory\Http\Controllers;

use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use Modules\Inventory\Repositories\TransferRepository;
use Modules\Inventory\Transformers\TransferResource;
use Illuminate\Http\Request;
use Modules\Inventory\Entities\TransferDetails;

class TransferController extends ControllerService implements CheckPolicies
{

    protected $repository;
    protected $resource;

    public function __construct(TransferRepository $repository, TransferResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
        parent::__construct();
    }
    public function finish($Transfer_id)
    {
        $status = $this->repository->finish($Transfer_id);
        return $this->setStatusCode(201)->respondWithObject($this->repository->model, $this->resource);
    }

    public function exportPackingSlip($id)
    {
        $details = TransferDetails::where('transfer_id', $id)->with(['product', 'product.brand'])->get();
        $collection = [];

        foreach ($details as $item) {
            $product = [
                'SKU'                   => optional($item->product)->sku ?? null,
                'Product Name/Variants' => optional($item->product)->name ?? null,
                'Brand'                 => optional($item->product->brand)->name ?? null,
                'Planned QTY'           => $item->qty,
                'Sent QTY'              => $item->qty_sent,
                'Received QTY'          => $item->qty_received,
                'Variance'              => $item->variance
            ];

            array_push($collection, $product);
        }

        return $this->setStatusCode(201)->sendArray($collection);
    }
}
