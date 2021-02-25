<?php

namespace Modules\Inventory\Repositories;

use App\Repositories\RepositoryService;
use Illuminate\Support\Arr;
use Auth;
use Illuminate\Support\Facades\DB;
use Modules\Inventory\Entities\TransferDetails;
use Modules\Inventory\Entities\Transfer;
use Modules\Inventory\Entities\Availability;
use Modules\Inventory\Entities\ProductLog;

use App\Models\Parameter;

class TransferRepository extends RepositoryService
{


    protected $availabilityRepository;

    public function findBy(array $searchCriteria = [])
    {

        lad($searchCriteria);

        if (!empty($searchCriteria['filter_received'])) {
            if ($searchCriteria['filter_received'] == 1) {
                $this->queryBuilder->where('is_enable', false);
            }
        }

        if (!empty($searchCriteria['shipment_type_name'])) {
            $value = '%' . Arr::pull($searchCriteria, 'shipment_type_name') . '%';
            $this->queryBuilder->whereHas('parameter_shipment', function ($query) use ($value) {
                $query->where('name', 'ILIKE', 'shipment_type')->where('value', 'ILIKE', $value);
            });
        }

        if (!empty($searchCriteria['carrier_name'])) {
            $value = '%' . Arr::pull($searchCriteria, 'carrier_name') . '%';
            $this->queryBuilder->whereHas('parameter_carrier', function ($query) use ($value) {
                $query->where('name', 'ILIKE', 'carrier')->where('value', 'ILIKE', $value);
            });
        }

        if (!empty($searchCriteria['location_name'])) {
            $location = '%' . Arr::pull($searchCriteria, 'location_name') . '%';
            $this->queryBuilder->whereHas('location_from', function ($query) use ($location) {
                $query->where('name', 'ILIKE', $location);
            })
                ->orWhereHas('location_to', function ($query) use ($location) {
                    $query->where('name', 'ILIKE', $location);
                });
        }

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
        DB::transaction(function () use ($data) {
            parent::store($data);
            // SAVE Transfer DETAILS
            $this->saveTransferDetails($data, $this->model->id);
        });
    }
    public function update($model, array $data)
    {
        DB::transaction(function () use ($data, $model) {
            parent::update($model, $data);
            // UPDATE STOCK TAKE PRODUCTS
            $this->saveTransferDetails($data, $this->model->id);
        });
    }

    private function saveTransferDetails($data, $id)
    {
        DB::transaction(function () use ($data, $id) {
            $availability_repository = new AvailabilityRepository(new Availability());

            if (!empty($data['list_products'])) {
                // DELETE ITEMS TO INSERT THEM AGAIN
                TransferDetails::where('transfer_id', $id)->delete();
                $list_products = $data['list_products'];

                foreach ($list_products as $key => $item) // EACH PRODUCT
                {
                    $qty            = $item['qty'] ?? 0;
                    $qty_sent       = $item['qty_sent'] ?? 0;
                    $qty_received   = $item['qty_received'] ?? 0;
                    $product_id     = $item['product_id'] ?? $item['name'] ?? null;
                    if ($product_id) {

                        TransferDetails::updateOrCreate(
                            [
                                'transfer_id'    => $id,
                                'product_id'     => $product_id
                            ],
                            [
                                'transfer_id'    => $id,
                                'product_id'     => $product_id,
                                'qty'            => $qty,
                                'qty_sent'       => $qty_sent,
                                'qty_received'   => $qty_received,
                                'variance'       => ($qty_sent - $qty_received)
                            ]
                        );

                        //only update stock when received
                        if (!$data['is_enable']) {
                            // Transfer received, update stock levels on both locations
                            if ($qty_received > 0) {
                                // Increment stock from receiver location
                                $availability_repository->updateStock($product_id, $qty_received, $data['location_to_id'], null, '+', 'Transfer', $id, 0, 0, 'Receiving quantity');

                                // Decrement stock from sender
                                $availability_repository->updateStock($product_id, $qty_received, $data['location_from_id'], null, '-', 'Transfer', $id, 0, 0, 'Sending quantity');

                                //Transfer::where('id', $id)->update(['is_enable' => 0]); // Transfer status
                            }
                        } else {    //update the in progress transfer statuses
                            $type = Parameter::firstOrCreate(
                                ['name' => 'product_log_type', 'value' => 'Transfer']
                            );

                            // update transfer status for receiving
                            ProductLog::updateOrCreate(
                                [
                                    'ref_code_id' => $id,
                                    'product_id'  => $product_id,
                                    'location_id' => $data['location_to_id'],
                                    'type_id'     => $type->id
                                ],
                                [
                                    'user_id'     => Auth::user()->id,
                                    'quantity'    => $qty_sent,
                                    'description' => '(In Transit) - Receiving Quantity'
                                ]
                            );

                            //update transfer status for sending
                            ProductLog::updateOrCreate(
                                [
                                    'ref_code_id' => $id,
                                    'product_id'  => $product_id,
                                    'location_id' => $data['location_from_id'],
                                    'type_id'     => $type->id
                                ],
                                [
                                    'user_id'     => Auth::user()->id,
                                    'quantity'    => '-' . $qty_sent,
                                    'description' => '(In Transit) - Sending Quantity'
                                ]
                            );
                        }
                    }
                }
            }
        });
    }

    public function delete($item)
    {
        DB::transaction(function () use ($item) {
            $availability_repository = new AvailabilityRepository(new Availability());
            $transfer = Transfer::where('id', $item->id)->first();

            foreach ($transfer->details as $value) {
                if (!$transfer->is_enable) {
                    if ($value->qty_received > 0) { // IF ALREADY RECEIVED
                        $availability_repository->updateStock($value->product_id, $value->qty_received, $transfer->location_to->id, null, '-', 'Transfer', $item->id, 0, 0, 'Undo transfer item - receiver');    // DECREMENT STOCK FROM RECEIVER LOCATION
                        $availability_repository->updateStock($value->product_id, $value->qty_received, $transfer->location_from->id, null, '+', 'Transfer', $item->id, 0, 0, 'Undo transfer item - sender');  // INCREMENT STOCK FROM SENDER LOCATION
                    }
                }
            }
            TransferDetails::where('transfer_id', $item->id)->delete();
        });

        return parent::delete($item);
    }
}
