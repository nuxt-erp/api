<?php

namespace Modules\Inventory\Repositories;

use App\Models\Parameter;
use Illuminate\Support\Arr;
use App\Repositories\RepositoryService;
use Illuminate\Support\Facades\DB;
use Modules\Inventory\Entities\Availability;
use Modules\Inventory\Entities\ProductLog;
use Modules\Inventory\Entities\StockAdjustment;
use Modules\Inventory\Entities\StockAdjustmentDetail;
use Modules\Inventory\Repositories\AvailabilityRepository;
class StockAdjustmentRepository extends RepositoryService
{

    public function findBy(array $searchCriteria = [])
    {
        $searchCriteria['order_by'] = [
            'field'         => 'id',
            'direction'     => 'asc'
        ];


        if (!empty($searchCriteria['id'])) {
            $this->queryBuilder
                ->where('id', $searchCriteria['id']);
        }

        return parent::findBy($searchCriteria);
    }

    public function store($data)
    {
        DB::transaction(function () use ($data) {
            $data['author_id'] = auth()->id();
            // SAVE STOCK ADJUSTMENT
            parent::store($data);
            // SAVE STOCK ADJUSTMENT PRODUCTS
            $this->saveStockAdjustmentDetail($data, $this->model->id);
        });
    }

    public function update($model, array $data)
    {
        DB::transaction(function () use ($data, $model) {
            parent::update($model, $data);
            // UPDATE STOCK ADJUSTMENT PRODUCTS
            $this->saveStockAdjustmentDetail($data, $this->model->id);
        });
    }

    private function saveStockAdjustmentDetail($data, $id)
    {
        if (!empty($data['list_products'])) {
            // DELETE ITEMS TO INSERT THEM AGAIN
            StockAdjustmentDetail::where('stock_adjustment_id', $id)->delete();
            $availabilityRepository = new AvailabilityRepository(new Availability());

            foreach ($data['list_products'] as $product) {
                $qty    = $product['qty'] ?? 0;
                $notes  = $product['notes'] ?? null;

                StockAdjustmentDetail::updateOrCreate([
                    'stock_adjustment_id' => $id,
                    'product_id'       => $product['product_id'],
                    'location_id'      => $product['location_id'],
                    'bin_id'           => $product['bin_id'] ?? null
                ], [
                    'qty'              => $qty,
                    'stock_on_hand'    => $product['on_hand'],
                    'variance'         => ($qty - $product['on_hand']),
                    'abs_variance'     => abs($qty - $product['on_hand']),
                    'adjustment_type'  => strtolower($product['adjustment_type']),
                    'status'           => StockAdjustmentDetail::ADJUSTED,
                    'notes'            => $notes
                ]);

                // UPDATE PRODUCT AVAILABILITY
                if(strtolower($product['adjustment_type']) === StockAdjustment::TYPE_ADD) {
                    $availabilityRepository->updateStock($product['product_id'], $qty,  $product['location_id'], $product['bin_id'] ?? null, '+', 'Stock Adjustment', $id, 0, 0, 'Adjust Stock (add)');

                } else if (strtolower($product['adjustment_type']) === StockAdjustment::TYPE_REPLACE) {
                    $availabilityRepository->updateStock($product['product_id'], 0,  $product['location_id'], $product['bin_id'] ?? null, '+', 'Stock Adjustment', $id, 0, $qty, 'Adjust Stock (replace)');
                    Availability::updateOrCreate(
                        [
                            'product_id'     => $product['product_id'],
                            'location_id'    => $product['location_id'],
                            'bin_id'         => $product['bin_id'] ?? null
                        ],
                        [
                            'on_hand'        => $qty
                        ]
                    );
                } 
                

                // UPDATE PRODUCT LOG
                $type = Parameter::firstOrCreate(
                    ['name'    => 'product_log_type', 'value' => 'Stock Adjustment']
                );
                ProductLog::updateOrCreate(
                    [
                        'ref_code_id' => $id,
                        'product_id'  => $product['product_id'],
                        'location_id' => $product['location_id'],
                        'bin_id'      => $product['bin_id'] ?? null,
                        'type_id'     => $type->id
                    ],
                    [
                        'quantity'    => $qty,
                        'description' => 'Finished stock adjustment - changing quantity'
                    ]
                );
            }
        }
    }

    public function delete($item)
    {
        DB::transaction(function () use ($item) {
            lad($item->id);
            $availability_repository = new AvailabilityRepository(new Availability());
            $stockAdjustment = StockAdjustment::where('id', $item->id)->with('details')->get()[0];
            $details = $stockAdjustment->details;
            lad($stockAdjustment);


            foreach ($details as $value) {   
                lad($value->adjustment_type);
                if(strtolower($value->adjustment_type) === StockAdjustment::TYPE_ADD) {
                    $availability_repository->updateStock($value->product_id, $value->qty, $value->location_id, null, "-", "Stock Adjustment", $item->id, 0, 0, "Remove item");
                } else if (strtolower($value->adjustment_type) === StockAdjustment::TYPE_REPLACE) {
                    $availability_repository->updateStock($value->product_id, 0, $value->location_id, null, "-", "Stock Adjustment", $item->id, 0, $value->qty, "Reset adjustment -back to previous qty");
                    Availability::updateOrCreate(
                        [
                            'product_id'     => $value->product_id,
                            'location_id'    => $value->location_id,
                            'bin_id'         => $value->bin_id 
                        ],
                        [
                            'on_hand'        => $value->stock_on_hand
                        ]
                    );
                } 

            }
            StockAdjustmentDetail::where('stock_adjustment_id', $item->id)->delete();
        });

        return parent::delete($item);
    }

    public function getAdjustmentStatuses()
    {
        $statuses = $this->model->getStatuses();
        $keyValue = [];
        $i = 0;
        foreach ($statuses as $key => $nested) {
            foreach ($nested as $value => $id) {
                $keyValue[$i]['id'] = $id;
                $keyValue[$i]['label'] = ucfirst($value);
                $keyValue[$i]['value'] = ucfirst($value);
                $i++;
            }
        }
        return $keyValue;
    }


    public function exportStockAdjustment($id)
    {
        $details = StockAdjustmentDetail::where('stock_adjustment_id', $id)->with(['product', 'product.brand', 'location', 'bin'])->get();
        $collection = [];

        foreach ($details as $item) {
            $product = [
                'sku'                   => optional($item->product)->sku ?? null,
                'product'               => optional($item->product)->name ?? null,
                'brand'                 => optional($item->product->brand)->name ?? null,
                'location'              => optional($item->location)->name ?? null,
                'bin'                   => optional($item->bin)->name ?? null,
                'on_hand'               => $item->stock_on_hand,
                'new_quantity'          => $item->qty,
                'variance'              => $item->variance,
                'notes'                 => $item->notes
            ];

            array_push($collection, $product);
        }

        return $collection;
    }
}
