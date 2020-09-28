<?php

namespace Modules\Inventory\Repositories;
use App\Repositories\RepositoryService;
use Illuminate\Support\Arr;
use Auth;
use Illuminate\Support\Facades\DB;
use Modules\Inventory\Entities\TransferDetails;
use Modules\Inventory\Entities\Transfer;
use Modules\Inventory\Entities\Availability;


class TransferRepository extends RepositoryService
{


    protected $availabilityRepository;

    public function findBy(array $searchCriteria = [])
    {
        $searchCriteria['order_by'] = [
            'field'         => 'id',
            'direction'     => 'asc'
        ];

        if (!empty($searchCriteria['id'])) {
            $this->queryBuilder
            ->where('id', Arr::pull($searchCriteria, 'id'));
        }

     //   $this->queryBuilder->where('company_id', Auth::user()->company_id);
        return parent::findBy($searchCriteria);
    }

    public function store($data)
    {
        DB::transaction(function () use ($data)
        {

            parent::store($data);
            // SAVE Transfer DETAILS
            $this->saveTransferDetails($data, $this->model->id);
        });
    }
    public function update($model, array $data)
    {
        DB::transaction(function () use ($data, $model)
        {
            parent::update($model, $data);
            // UPDATE STOCK TAKE PRODUCTS
            $this->saveTransferDetails($data, $this->model->id);
        });
    }

    private function saveTransferDetails($data, $id)
    {
        lad('data', $data);
        DB::transaction(function () use ($data, $id){
            $availability_repository = new AvailabilityRepository(new Availability());
            if (!empty($data['list_products']))
            {
                // DELETE ITEMS TO INSERT THEM AGAIN
                TransferDetails::where('transfer_id', $id)->delete();
                $list_products = $data['list_products'];
                lad($list_products);
                foreach ($list_products as $key => $item) // EACH PRODUCT
                {
                    $qty            = $item['qty'] ?? 0;
                    $qty_sent       = $item['qty_sent'] ?? 0;
                    $qty_received   = $item['qty_received'] ?? 0;
                    $product_id     = $item['product_id'] ?? $item['name'] ?? null;
                    if ($product_id) {
                        

                        TransferDetails::updateOrCreate([
                            'transfer_id'    => $id,
                            'product_id'     => $product_id],
                        [
                            'transfer_id'    => $id,
                            'product_id'     => $product_id,
                            'qty'            => $qty,
                            'qty_sent'       => $qty_sent,
                            'qty_received'   => $qty_received,
                            'variance'       => ($qty_sent - $qty_received)
                        ]);

                        // Transfer received, update stock levels on both locations
                        if ($qty_received > 0) {
                            // Increment stock from receiver location
                            $availability_repository->updateStock($product_id, $qty_received, $data['location_to_id'], '+', 'Transfer', $id,0,0, 'Receiving quantity');

                            // Decrement stock from sender
                            $availability_repository->updateStock($product_id, $qty_received, $data['location_from_id'], '-', 'Transfer', $id, 0,0, 'Sending quantity');

                            Transfer::where('id', $id)->update(['is_enable' => 1]); // Transfer status
                        }
                    }
                }
            }
        });
    }

    public function remove($id)
    {
        DB::transaction(function () use ($id)
        {
            $availability_repository = new AvailabilityRepository(new Availability());
            $getItem = Transfer::find('id', $id)->first();
            if(isset($getItem[0]->details)){
                foreach ($getItem[0]->details as $value)
                {
                    if ($value->qty_received > 0) { // IF ALREADY RECEIVED
                       $availability_repository->updateStock($value->product_id, $value->qty_received, $getItem[0]->location_to->id, '-', 'Transfer', $id, 0,0, 'Undo transfer item - receiver');    // DECREMENT STOCK FROM RECEIVER LOCATION
                       $availability_repository->updateStock($value->product_id, $value->qty_received, $getItem[0]->location_from->id, '+', 'Transfer', $id, 0,0, 'Undo transfer item - sender');  // INCREMENT STOCK FROM SENDER LOCATION
                    }
                }
            }

            // parent::delete($id);
            Transfer::where('id', $id)->delete();
        });
    }

}
