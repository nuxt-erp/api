<?php

namespace App\Repositories;

use App\Repositories\RepositoryService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SettingsImagesRepository extends RepositoryService
{

    public function store(array $data)
    {
        DB::transaction(function () use ($data) {
            foreach ($data['paths'] as $file) {
                parent::store([
                    'type'          => $data['type'],
                    'path'          => $file['path'],
                    'thumb_path'    => $file['thumb_path']
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
