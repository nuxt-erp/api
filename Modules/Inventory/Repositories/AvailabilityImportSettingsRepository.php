<?php

namespace Modules\Inventory\Repositories;

use App\Repositories\RepositoryService;

class AvailabilityImportSettingsRepository extends RepositoryService
{

    public function store(array $data)
    {
        foreach ($data['columns'] as $item) {
            $model = $this->findOne($item['id']);
            parent::update($model, $item);
        }
    }
}
