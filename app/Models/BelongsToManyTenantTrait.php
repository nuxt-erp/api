<?php

namespace App\Models;

use Illuminate\Database\ConnectionInterface;

trait BelongsToManyTenantTrait
{
    /**
     * Define a many-to-many relationship.
     *
     * @param  string              $related
     * @param  string              $table
     * @param  ConnectionInterface $connection
     * @param  string              $foreignKey
     * @param  string              $relatedKey
     * @param  string              $relation
     *
     * @return BelongsToManyTenant
     */
    public function belongsToManyTenant($related, $table = null, ConnectionInterface $connection = null, $foreignPivotKey = null, $relatedPivotKey = null,
                                  $parentKey = null, $relatedKey = null, $relation = null)
    {
        if (is_null($relation)) {
            $relation = $this->guessBelongsToManyRelation();
        }

        $instance = $this->newRelatedInstance($related);

        $foreignPivotKey = $foreignPivotKey ?: $this->getForeignKey();

        $relatedPivotKey = $relatedPivotKey ?: $instance->getForeignKey();

        if (is_null($table)) {
            $table = $this->joiningTable($related, $instance);
        }

        if (is_null( $connection )) {
            $connection = $this->getConnection();
        }

        return new BelongsToManyTenant(
            $instance->newQuery(), $this, $connection, $table, $foreignPivotKey,
            $relatedPivotKey, $parentKey ?: $this->getKeyName(),
            $relatedKey ?: $instance->getKeyName(), $relation
        );
    }
}
