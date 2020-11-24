<?php

namespace Modules\Inventory\Repositories;

use App\Models\Parameter;
use Illuminate\Support\Arr;
use App\Repositories\RepositoryService;
use Illuminate\Support\Facades\DB;
use Modules\Inventory\Entities\Availability;
use Modules\Inventory\Entities\ProductLog;
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
        DB::transaction(function () use ($data)
        {
            // SAVE STOCK ADJUSTMENT
            parent::store($data);
            // SAVE STOCK ADJUSTMENT PRODUCTS
            $this->saveStockAdjustmentDetail($data, $this->model->id);
        });
    }

    public function update($model, array $data)
    {
        DB::transaction(function () use ($data, $model){
            parent::update($model, $data);
            // UPDATE STOCK ADJUSTMENT PRODUCTS
            $this->saveStockAdjustmentDetail($data, $this->model->id);
        });
    }

    private function saveStockAdjustmentDetail($data, $id)
    {
        if (!empty($data['list_products']))
        {
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
                ],[
                    'qty'           => $qty,
                    'stock_on_hand' => $product['on_hand'],
                    'variance'      => ($qty - $product['on_hand']),
                    'abs_variance'  => abs($qty - $product['on_hand']),
                    'notes'         => $notes
                ]);

                // UPDATE PRODUCT AVAILABILITY
                Availability::updateOrCreate([
                    'product_id'  => $product['product_id'],
                    'location_id' => $product['location_id'],
                    'bin_id'      => $product['bin_id'] ?? null
                ],
                [
                    'on_hand'   => $qty
                ]);

                // ADD MOVEMENT TO PRODUCT LOG
                $type = Parameter::firstOrCreate(
                    ['name' => 'product_log_type', 'value' => 'Stock Adjustment']
                );
                $log                = new ProductLog();
                $log->product_id    = $product['product_id'];
                $log->location_id   = $product['location_id'];
                $log->bin_id        = $product['bin_id'] ?? null;
                $log->quantity      = $qty;
                $log->ref_code_id   = $id;
                $log->type_id       = $type->id;
                $log->description   = 'Finished stock adjustment - changing quantity';

                $log->save();
            }
        }
    }

}
