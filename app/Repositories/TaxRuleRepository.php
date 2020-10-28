<?php

namespace App\Repositories;

use App\Models\TaxRuleComponent;
use App\Models\TaxRuleScope;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

class TaxRuleRepository extends RepositoryService
{
    public function store(array $data)
    {

        DB::transaction(function () use ($data)
        {
            parent::store($data);
            
            if(!empty($data['tax_details'])) {
                $tax_component = $data['tax_details'];
                $new                    = new TaxRuleComponent();
                $new->tax_rule_id       = $this->model->id;
                $new->component_name    = $tax_component['component_name'];
                $new->seq               = $tax_component['seq'];
                $new->rate              = $tax_component['rate'];
                $new->compound          = $tax_component['compound'];
                $new->save();
            }
            if(!empty($data['scope_names'])) {
                $scope_names = $data['scope_names'];
                foreach ($scope_names as $scope_name) {
                    $new                  = new TaxRuleScope();
                    $new->tax_rule_id     = $this->model->id;
                    $new->scope           = $scope_name;
                    $new->save();
                }
            }
        });
    }
    public function update($model, array $data)
    {

        DB::transaction(function () use ($data, $model)
        {    
            parent::update($model, $data);
            if(!empty($data['tax_details'])) {
                $tax_component = $data['tax_details'];
                TaxRuleComponent::updateOrCreate(
                    ['id' => $tax_component['id']],
                    $tax_component
                );
            }
            if(!empty($data['scope_names'])) {
                TaxRuleScope::where('tax_rule_id', $model->id)->delete();
                
                $scope_names = $data['scope_names'];
                foreach ($scope_names as $scope_name) {
                    $new                  = new TaxRuleScope();
                    $new->tax_rule_id     = $this->model->id;
                    $new->scope           = $scope_name;
                    $new->save();
                }
            }
        });
    }
}
