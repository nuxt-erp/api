<?php

namespace Modules\Inventory\Repositories;

use App\Repositories\RepositoryService;

class ProductImportSettingsRepository extends RepositoryService
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
