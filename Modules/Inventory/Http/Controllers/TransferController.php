<?php

namespace Modules\Inventory\Http\Controllers;

use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use Modules\Inventory\Repositories\TransferRepository;
use Modules\Inventory\Transformers\StockLocatorResource;

class TransferController extends ControllerService implements CheckPolicies
{

    protected $repository;
    protected $resource;

    public function __construct(TransferRepository $repository, StockLocatorResource $resource)
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

    public function remove(Request $request) {
        $this->repository->remove($request->id);
        return $this->respond(['ok' => true]);
    }
}