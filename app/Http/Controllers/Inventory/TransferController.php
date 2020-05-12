<?php

namespace App\Http\Controllers\Inventory;


use App\Http\Controllers\ControllerService;
use App\Repositories\TransferRepository;
use App\Resources\TransferResource;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Models\TransferDetails;

class TransferController extends ControllerService
{
    protected $repository;
    protected $resource;

    public function __construct(TransferRepository $repository, TransferResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
    }

    public function finish($Transfer_id)
    {
        $status = $this->repository->finish($Transfer_id);
        return $this->setStatusCode(201)->respondWithObject($this->repository->model, $this->resource);
    }

    public function exportPackingSlip($id)
    {
       $details = TransferDetails::where('transfer_id', $id)->with('product')->get();
       $collection = [];

        foreach ($details as $item)
        {
            $product = [
                'SKU'                   => $item->product->sku,
                'Product Name/Variants' => $item->product->getConcatNameAttribute(),
                'Brand'                 => $item->product->brand->name,
                'Planned QTY'           => $item->qty,
                'Sent QTY'              => $item->qty_sent,
                'Received QTY'          => $item->qty_received,
                'Variance'              => $item->variance
            ];

            array_push($collection, $product);
        }

        return $this->setStatusCode(201)->respondWithArray($collection);
    }

}
