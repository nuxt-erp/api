<?php

namespace Modules\Inventory\Repositories;

use App\Models\Parameter;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use App\Repositories\RepositoryService;
use Illuminate\Database\Eloquent\Builder;
use Modules\Inventory\Entities\Availability;
use Modules\Inventory\Entities\ProductLog;
use Modules\Inventory\Entities\Receiving;
use Modules\Inventory\Entities\ReceivingDetail;

class ReceivingRepository extends RepositoryService
{
    public function findBy(array $searchCriteria = [])
    {
        if(!empty($searchCriteria['supplier_name']) && $searchCriteria['supplier_name']) {
            $supplier_name = '%' . Arr::pull($searchCriteria, 'supplier_name') . '%';
            $this->queryBuilder->whereHas('supplier', function (Builder $query) use ($supplier_name) {
                $query->where('name', 'ILIKE', $supplier_name);
            });
        }

        if(!empty($searchCriteria['name']) && $searchCriteria['name']) {
            $name = '%' . Arr::pull($searchCriteria, 'name') . '%';
            $this->queryBuilder->where('name', 'ILIKE', $name);
        }

        return parent::findBy($searchCriteria);
    }
    public function store($data)
    {
        DB::transaction(function () use ($data)
        {
            if(empty($data['status_received']) && $data['status_received']) {
                $data['status'] = Receiving::NEW_RECEIVING;
            } else {
                $data['status'] = Receiving::RECEIVED;
            }
            $data['allocation_status'] = Receiving::NEW_RECEIVING;

            // SAVE STOCK TAKE
            parent::store($data);
           
            if(!empty($data['list_products'])) {
                $this->model->details()->sync($data['list_products']);
            }
        });
    }

    public function update($model, array $data)
    {
        DB::transaction(function () use ($data, $model){
            if(empty($data['status_received']) && $data['status_received']) {
                $data['status'] = Receiving::PARTIALLY_RECEIVED;
            }
            parent::update($model, $data);
            if(empty($data['list_products'])) $model->details()->sync($data['list_products']);
        });
    }
    // ADJUST & FINISH STOCK COUNT
    public function finish($receiving_id)
    {
        DB::transaction(function () use ($receiving_id)
        {
        // GET ALL SAVED QTY FROM COUNTING
        $receiving = Receiving::find($receiving_id);
        $receiving_details = ReceivingDetail::where('receiving_id', $receiving_id)->get();
        foreach ($receiving_details as $item){

            // update availability
            if($receiving->location_id){

                $availability_repo = new AvailabilityRepository(new Availability());
                $availability_repo->updateStock($item->product_id, $item->qty_received, $receiving->location_id, null, '+', 'Receiving', $receiving_id, null, null,'Received product - changing quantity');


                // add movement
                $type = Parameter::firstOrCreate(
                    ['name' => 'product_log_type', 'value' => 'Receiving']
                );

                $log                = new ProductLog();
                $log->product_id    = $item->product_id;
                $log->location_id   = $receiving->location_id;
                $log->bin_id        = null;
                $log->quantity      = $item->qty_received;
                $log->ref_code_id   = $receiving_id;
                $log->type_id       = $type->id;
                $log->description   = 'Received product - changing quantity';
                $log->user_id       =  auth()->user()->id;
                $log->save();
            }
        }

        // SAVE STATUS AS FINISHED
        Receiving::where('id', $receiving_id)->update(['status' => Receiving::RECEIVED]);
        return true;
        });
    }


}
