<?php

namespace Modules\Inventory\Repositories;

use App\Repositories\RepositoryService;
use Illuminate\Support\Arr;

class ProductSuppliersRepository extends RepositoryService
{
    public function findBy(array $searchCriteria = [])
    {        
        return parent::findBy($searchCriteria);
    }
    
    public function skuSuppliers(array $searchCriteria = [])
    {
        if (!empty($searchCriteria['supplier_id'])) {
         
            $this->queryBuilder->where('supplier_id', $searchCriteria['supplier_id']);
            
            unset($searchCriteria['id']);
        } 
        if (!empty($searchCriteria['product_sku'])) {
         
            $this->queryBuilder->where('product_sku', $searchCriteria['product_sku']);
            
            unset($searchCriteria['product_sku']);
        }    
        return parent::findBy($searchCriteria); 
    }
}
