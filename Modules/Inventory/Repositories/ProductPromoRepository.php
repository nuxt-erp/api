<?php

namespace Modules\Inventory\Repositories;

use App\Repositories\RepositoryService;
use Illuminate\Support\Arr;
use Carbon\Carbon;

class ProductPromoRepository extends RepositoryService
{
    public function findBy(array $searchCriteria = [])
    {   
        $today = Carbon::now()->format('Y-m-d');
        $this->queryBuilder
            ->where(function ($query) use ($today) {
                $query->where('date_from', '>=', $today)
                    ->orWhere( function ($query2) use ($today) {
                        $query2->where('date_from', '<', $today)
                        ->where( function ($query3) use ($today) {
                            $query3->where('date_to', '>=', $today)
                                ->orWhereNull('date_to');
                        });
                    });
            });
        
        return parent::findBy($searchCriteria);
    }
}
