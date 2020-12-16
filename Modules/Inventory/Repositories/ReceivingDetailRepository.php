<?php

namespace Modules\Inventory\Repositories;

use App\Models\Parameter;
use Illuminate\Support\Arr;
use App\Repositories\RepositoryService;
use Illuminate\Support\Facades\DB;
use Modules\Inventory\Entities\Availability;
use Modules\Inventory\Entities\ProductLog;
use Modules\Inventory\Entities\Receiving;
use Modules\Inventory\Entities\ReceivingDetail;
use Modules\Purchase\Entities\Purchase;
use Modules\Purchase\Entities\PurchaseDetail;

class ReceivingDetailRepository extends RepositoryService
{
    public function findBy(array $searchCriteria = [])
    {
        return parent::findBy($searchCriteria);
    }

    public function store($data)
    {
        DB::transaction(function () use ($data)
        {
            $data['item_status'] = ReceivingDetail::NEW_RECEIVING;

            // SAVE RECEIVING DETAIL
            parent::store($data);
        });
    }

    public function update($model, array $data)
    {
        DB::transaction(function () use ($data, $model){
            $data['item_status'] = $this->updateItemStatus($model, $data);

            parent::update($model, $data);

            if($this->model) {
                $receiving = Receiving::find($this->model->receiving_id);
                $receiving_repo = new ReceivingRepository(new Receiving());
                $receiving->allocation_status = $receiving_repo->updateAllocationStatus($receiving, $this->model);
                $receiving->save();
            } 
        });
    }

    public function updateItemStatus($model, array $data)
    {
        // HANDLE ITEM STATUS
        if(!empty($data['qty_allocated']) && $data['qty_allocated']) {
            if(!empty($data['qty_received']) && $data['qty_received']) {
                if($data['qty_received'] === $data['qty_allocated']) {
                    $data['item_status'] = ReceivingDetail::ALLOCATED;
                } else {
                    $data['item_status'] = ReceivingDetail::PARTIALLY_ALLOCATED;
                }
            } else {
                if($model->qty_received === $data['qty_allocated']) {
                    $data['item_status'] = ReceivingDetail::ALLOCATED;
                } else {
                    $data['item_status'] = ReceivingDetail::PARTIALLY_ALLOCATED;
                }
            }
        } else {
            $data['item_status'] = ReceivingDetail::NEW_RECEIVING;
        }

        return $data['item_status'];
    }

    
}
