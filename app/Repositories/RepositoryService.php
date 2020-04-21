<?php

namespace App\Repositories;

use App\Exceptions\ConstrainException;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

abstract class RepositoryService implements RepositoryInterface
{

    /**
     * Instance that extends Illuminate\Database\Eloquent\Model
     *
     * @var Model
     */
    public $model;
    public $queryBuilder;

    /**
     * Constructor
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->queryBuilder = $model::query();
        //@todo add all joins if is needed by default
        /*
            eager loading -> $this->queryBuilder::with('last_item')
            latest example ->
            public function last_item(){
                return $this->hasOne(Item::class)->where('some_id', $this->some_id)->latest();
            }
        */
    }

    /**
     * @inheritdoc
     */
    public function findOne($id)
    {
        return $this->model->where('id', $id)->first();
    }

    public function modelCount()
    {
        return $this->model->count();
    }

    /**
     * @inheritdoc
     */
    public function findOneBy(array $criteria)
    {
        return $this->model->where($criteria)->first();
    }

    /**
     * return a list to populate html options
     */
    public function getList(array $searchCriteria = [])
    {

        foreach ($searchCriteria as $key => $value) {
            switch ($key) {
                case 'order_by':
                    $this->queryBuilder->orderBy($searchCriteria['order_by']['field'], $searchCriteria['order_by']['direction']);
                    break;
                case 'per_page':
                    $this->queryBuilder->limit($searchCriteria['per_page']);
                    break;
                case 'filter':
                    foreach ($searchCriteria['filter'] as $filter) {
                        $this->queryBuilder->where($filter['field'], $filter['operator'], $filter['value']);
                    }
                    break;
                    //INCLUDE THE CRITERIA ID IN THE QUERY
                case 'id':
                    $find = $this->model->where('id', $searchCriteria['id']);
                    $this->queryBuilder->union($find);
                    break;
                default:
                    if (trim($value) != '')
                        $this->queryBuilder->where($key, $value);
                    break;
            }
        }

        if(!isset($searchCriteria['per_page']))
            $this->queryBuilder->limit(20);

        $collection = $this->queryBuilder->get();

        return $collection;
    }

    /**
     * apply filters and return a paginated list
     */
    public function findBy(array $searchCriteria = [])
    {
        if (isset($searchCriteria['order_by'])) {
            $this->queryBuilder->orderBy($searchCriteria['order_by']['field'], $searchCriteria['order_by']['direction']);
        }

        $limit = !empty($searchCriteria['per_page']) ? (int) $searchCriteria['per_page'] : 20; // it's needed for pagination

        $this->queryBuilder->where(function ($query) use ($searchCriteria) {
            $this->applySearchCriteriaInQueryBuilder($query, $searchCriteria);
        });

        return $this->queryBuilder->paginate($limit);
    }

    /**
     * Apply condition on query builder based on search criteria
     *
     * @param Object $queryBuilder
     * @param array $searchCriteria
     * @return mixed
     */
    protected function applySearchCriteriaInQueryBuilder($queryBuilder, array $searchCriteria = [])
    {
        foreach ($searchCriteria as $key => $value) {


            $doCriteria = $this->model->isFillable($key) && // only apply criteria if field is present in fillable array
                !in_array($key, ['page', 'per_page', 'order_by', 'with', 'query_type', 'where']) && //reserved keys
                trim($value) != ''; // if is empty, we dont need to filter

            if ($doCriteria) {
                //we can pass multiple params for a filter with commas
                $allValues = explode(',', $value);

                if (count($allValues) > 1) {
                    $queryBuilder->whereIn($key, $allValues);
                } else {
                    $operator = isset($searchCriteria['query_type']) ? $searchCriteria['query_type'] : '=';
                    $join = explode('.', $key);
                    if (isset($join[1])) {
                        if (isset($searchCriteria['where']) && strtoupper($searchCriteria['where']) == 'AND') {
                            $queryBuilder->whereHas($join[0], function ($query) use ($join, $operator, $value) {
                                $query->where(Str::plural($join[0]) . '.' . $join[1], $operator, $value);
                            });
                        } else {
                            $queryBuilder->orWhereHas($join[0], function ($query) use ($join, $operator, $value) {
                                $query->where(Str::plural($join[0]) . '.' . $join[1], $operator, $value);
                            });
                        }
                    } else {
                        if (isset($searchCriteria['where']) && strtoupper($searchCriteria['where']) == 'OR') {
                            $queryBuilder->orWhere($key, $operator, $value);
                        } else {

                            $queryBuilder->where($key, $operator, $value);
                        }
                    }
                }
            }
        }

        return $queryBuilder;
    }

    /**
     * @inheritdoc
     */
    public function store(array $data)
    {
        //ignore id to store a new row
        unset($data['id']);

        foreach ($data as $key => $value) {
            // WHEN ID IS 0 -> SET NULL ON DB
            if (strpos($key, '_id') !== FALSE && $value == 0) {
                $data[$key] = null;
            }
            if (!$this->model->isFillable($key)) {
                unset($data[$key]);
            }
        }
        $this->model = $this->model->create($data);
    }

    /**
     * @inheritdoc
     */
    public function update($model, array $data)
    {
        if (!is_null($model)) {
            $this->model = $model;
            foreach ($data as $key => $value) {
                // WHEN ID IS 0 > SET NULL ON DB
                if (strpos($key, '_id') !== FALSE && $value == 0) {
                    $value = null;
                }
                // update only fillAble properties
                if ($this->model->isFillable($key)) {
                    $this->model->{$key} = $value;
                }
            }
            // update the model
            $this->model->save();
        }
    }


    public function delete($model)
    {
        // we don't need to delete NULL, right?
        if (is_null($model)) {
            $result = FALSE;
        }
        else{
            try {
                $result = $model->delete();
            } catch (QueryException $e) {
                if($e->errorInfo[1] !== 1451){
                    Log::channel('debug')->info($e->errorInfo);
                }
                throw new ConstrainException('delete', $e->errorInfo[1]);
            }
        }

        return $result;

    }

    public function destroy($model)
    {
        // we don't need to delete NULL, right?
        if (is_null($model)) {
            $result = FALSE;
        }
        else{
            try {
                $result = $model->delete();
            } catch (QueryException $e) {
                if($e->errorInfo[1] !== 1451){
                    Log::channel('debug')->info($e->errorInfo);
                }
                throw new ConstrainException('delete', $e->errorInfo[1]);
            }
        }
        return $result;
    }
}
