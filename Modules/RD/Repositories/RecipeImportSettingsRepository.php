<?php

namespace Modules\RD\Repositories;

use App\Repositories\RepositoryService;
use Illuminate\Support\Arr;

class RecipeImportSettingsRepository extends RepositoryService
{
    public function store(array $data)
    {
        lad('data', $data);
        foreach ($data['columns'] as $item) {
            lad('item', $item);
            $model = $this->findOne($item['id']);
            parent::update($model, $item);
        }
    }
}
