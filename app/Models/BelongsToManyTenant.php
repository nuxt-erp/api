<?php

namespace App\Models;

use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class BelongsToManyTenant extends BelongsToMany
{
    public $connection;

    public function __construct(Builder $query, Model $parent, ConnectionInterface $connection, $table, $foreignPivotKey,
                                $relatedPivotKey, $parentKey, $relatedKey, $relationName = null)
    {
        $this->connection = $connection;
        parent::__construct($query, $parent, $table, $foreignPivotKey,
        $relatedPivotKey, $parentKey, $relatedKey, $relationName);
    }

    public function newPivotStatement()
    {
        if($this->using){
            $using = new $this->using;
            return $using::query();
        }
        else{
            return $this->query->getQuery()->newQuery()->from($this->table);
        }
    }

    protected function performJoin( $query = null )
    {
        $schema = '';
        if($this->using){
            $using = new $this->using;
            $schema = $using->getConnection()->getConfig()['schema'].'.';
        }
        elseif(!empty($this->connection->getConfig()['schema'])){
            $schema = $this->connection->getConfig()['schema'].'.';
        }
        else{
            $schema = config('database.connections.'.$this->connection->getName().'.schema').'.';
        }

        $query = $query ?: $this->query;
        // We need to join to the intermediate table on the related model's primary
        // key column with the intermediate table's foreign key for the related
        // model instance. Then we can set the "where" for the parent models.
        $baseTable = $this->related->getTable();
        $key = $baseTable.'.'.$this->relatedKey;

        $query->join($schema.$this->table, $key, '=', $this->getQualifiedRelatedPivotKeyName());

        return $this;

    }

    public function getRelationExistenceQuery(Builder $query, Builder $parentQuery, $columns = ['*'])
    {
        if ($parentQuery->getQuery()->from == $query->getQuery()->from) {
            return $this->getRelationExistenceQueryForSelfJoin($query, $parentQuery, $columns);
        }

        $this->performJoin($query);

        $schema = '';
        if(!empty($this->connection->getConfig()['schema'])){
            $schema = $this->connection->getConfig()['schema'].'.';
        }
        $query->getQuery()->from = $schema.$query->getQuery()->from;

        return get_parent_class(get_parent_class($this))::getRelationExistenceQuery($query, $parentQuery, $columns);
    }


}
