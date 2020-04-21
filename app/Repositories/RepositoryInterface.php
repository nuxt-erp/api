<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface RepositoryInterface
{
    /**
     * Find a resource by id
     *
     * @param $id
     * @return Model|null
     */
    public function findOne($id);

    /**
     * Find a resource by criteria
     *
     * @param array $criteria
     * @return Model|null
     */
    public function findOneBy(array $criteria);

    /**
     * Search All resources by criteria
     *
     * @param array $searchCriteria
     * @return Collection
     */
    public function findBy(array $searchCriteria = []);

    /**
     * Save a resource
     *
     * @param array $data
     * @return Model
     */
    public function store(array $data);

    /**
     * Update a resource
     *
     * @param integer $id
     * @param array $data
     * @return Model
     */
    public function update($id, array $data);

    /**
     * Delete a resource
     *
     * @param integer $id
     * @return mixed
     */
    public function delete($id);

}
