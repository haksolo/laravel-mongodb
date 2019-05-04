<?php

namespace Extended\MongoDB\Database\Eloquent;

use Extended\MongoDB\Database\Aggregation\Builder as AggregationBuilder;
use Extended\MongoDB\Database\Query\Builder as QueryBuilder;

trait ModelTrait
{
    use Concerns\HasRelationships;

    /**
     * Create a new Eloquent query builder for the model.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function newEloquentBuilder($query)
    {
        return new Builder($query);
    }

    /**
     * Get a new query builder instance for the connection.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    protected function newBaseQueryBuilder()
    {
        return new QueryBuilder($this->getConnection());
    }

    /**
     * Qualify the given column name by the model's table.
     *
     * @param  string  $column
     * @return string
     */
    public function qualifyColumn($column)
    {
        return $column;
    }

    /**
     * Get the format for database stored dates.
     *
     * @return string
     */
    public function getDateFormat()
    {
        return $this->dateFormat ?: 'Y-m-d H:i:s';
    }

    /**
     * Get the default id attribute
     *
     * @return mixed
     */
    public function getIdAttribute()
    {
        return $this->attributes['_id'];
    }

    /**
     * Begin aggregating the model.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function aggregate()
    {
        return (new static)->newAggregationBuilder();
    }

    /**
     * Get a new aggregation builder instance for the connection.
     *
     * @return \Extended\MongoDB\Aggregation\Builder
     */
    protected function newAggregationBuilder()
    {
        return (new AggregationBuilder($this->getConnection()))->from($this->getTable());
    }
}
