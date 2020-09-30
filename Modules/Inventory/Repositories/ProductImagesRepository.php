<?php

namespace Modules\Inventory\Repositories;

use App\Repositories\RepositoryService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ProductImagesRepository extends RepositoryService
{

    public function store(array $data)
    {
        lad('data', $data);
        DB::transaction(function () use ($data) {
            foreach ($data['paths'] as $file) {
                lad('file repo', $file);
                parent::store([
                    'product_id'    => $data['product_id'],
                    'path'          => $file
                ]);
            }
        });
    }

}
