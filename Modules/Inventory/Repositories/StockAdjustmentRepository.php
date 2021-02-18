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

            foreach ($data['list_products'] as $product) {
                $qty    = $product['qty'] ?? 0;
                $notes  = $product['notes'] ?? null;

                StockAdjustmentDetail::updateOrCreate([
                    'stock_adjustment_id' => $id,
                    'product_id'    => $product['product_id'],
                    'location_id'   => $product['location_id'],
                    'bin_id'        => $product['bin_id'] ?? null
                ], [
                    'qty'           => $qty,
                    'stock_on_hand' => $product['on_hand'],
                    'variance'      => ($qty - $product['on_hand']),
                    'abs_variance'  => abs($qty - $product['on_hand']),
                    'status'        => StockAdjustmentDetail::ADJUSTED,
                    'notes'         => $notes
                ]);

                // UPDATE PRODUCT AVAILABILITY
                Availability::updateOrCreate(
                    [
                        'product_id'  => $product['product_id'],
                        'location_id' => $product['location_id'],
                        'bin_id'      => $product['bin_id'] ?? null
                    ],
                    [
                        'on_hand'   => $qty
                    ]
                );

                // UPDATE PRODUCT LOG
                $type = Parameter::firstOrCreate(
                    ['name' => 'product_log_type', 'value' => 'Stock Adjustment']
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

    public function destroy($item)
    {
        DB::transaction(function () use ($item) {
            $availability_repository = new AvailabilityRepository(new Availability());
            $stockAdjustment = StockAdjustment::find($item->id)->with('details')->first();
            lad($stockAdjustment->details);
            foreach ($stockAdjustment->details as $value) {
                $availability_repository->updateStock($value->product_id, $value->qty, $value->location_id, null, "-", "Stock Adjustment", $item->id, 0, 0, "Remove item");
            }
            StockAdjustmentDetail::where('stock_adjustment_id', $item->id)->delete();
        });

        return parent::delete($item);
    }

    public function exportStockAdjustment($id)
    {
        $details = StockAdjustmentDetail::where('stock_adjustment_id', $id)->with(['product', 'product.brand', 'location', 'bin'])->get();
        $collection = [];

        foreach ($details as $item) {
            $product = [
                'sku'                   => $item->product->sku,
                'product'               => $item->product->name,
                'brand'                 => $item->product->brand,
                'location'              => $item->location->name,
                'bin'                   => $item->bin,
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
