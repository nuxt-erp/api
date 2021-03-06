<?php

namespace Modules\Inventory\Repositories;

use App\Repositories\RepositoryService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductImagesRepository extends RepositoryService
{

    public function store(array $data)
    {
        DB::transaction(function () use ($data) {
            foreach ($data['paths'] as $file) {
                parent::store([
                    'product_id'    => $data['product_id'],
                    'path'          => $file['path'],
                    'thumb_path'    => $file['thumb_path'],
                    'is_default'    => $file['is_default'] ?? 0
                ]);
            }
        });
    }

    public function delete($model)
    {
        $result = true;
        DB::transaction(function () use ($model, &$result) {
            // image path
            $path       = $model->path;
            $thumb_path = $model->thumb_path;

            $result = parent::delete($model);
            if ($result) {
                $result = Storage::disk('s3')->delete($path);
                $result = Storage::disk('s3')->delete($thumb_path);
            }
        });
        return $result;
    }
}
