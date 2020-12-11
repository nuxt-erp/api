<?php

namespace Modules\Inventory\Repositories;

use App\Models\Parameter;
use Illuminate\Support\Facades\DB;
use App\Repositories\RepositoryService;
use Modules\Inventory\Entities\Availability;
use Modules\Inventory\Entities\ProductLog;
use Modules\Inventory\Entities\Receiving;
use Modules\Inventory\Entities\ReceivingDetail;

class ReceivingRepository extends RepositoryService
{
    public function findBy(array $searchCriteria = [])
    {
        return parent::findBy($searchCriteria);
    }
    public function store($data)
    {
        DB::transaction(function () use ($data)
        {
            $data['status'] = 'new';

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
            $data['status'] = 'partially received';
            parent::update($model, $data);
            $model->details()->sync($data['list_products']);
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
 
                     Availability::updateOrCreate([
                         'product_id'    => $item->product_id,
                         'location_id'   => $receiving->location_id,
                         'bin_id'        => null
                     ],
                     [
                         'on_hand'       => $item->qty_received
                     ]);
 
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
             Receiving::where('id', $receiving_id)->update(['status' => 'received']);
             return true;
         });
     }


}
