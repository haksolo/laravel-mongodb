<?php

namespace Extended\MongoDB\Database\Query;

use Extended\MongoDB\Database\Aggregation\Builder as AggregationBuilder;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\Grammars\Grammar as BaseGrammar;
// use Illuminate\Support\Arr;

class Grammar extends BaseGrammar
{
    protected $filter;

    protected $aggregationComponents = [
        'aggregate',
        'joins',
        // 'groups',
        // 'havings',
    ];

    protected $selectComponents = [
        // 'aggregate',
        'columns',
        'from',
        // 'joins',
        'wheres',
        // 'groups',
        // 'havings',
        'orders',
        'limit',
        'offset',
        // 'lock',
    ];

    /**
     * Compile a select query into SQL.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return string
     */
    public function compileSelect(Builder $query)
    {
        if ($this->shouldUseAggregation($query)) {
            return array_merge(['method' => 'aggregate'],
                $this->compileAggregation($query, $query->aggregation())
            );
        }

        return [
            'collection' => $query->from,
            'filter' => $this->compileWheres($query),
            'options' => [
                'projection' => $this->compileColumns($query, $query->columns),
                'limit' => $this->compileLimit($query, $query->limit),
                'skip' => $this->compileOffset($query, $query->offset),
                // 'sort' => $query->sort,
            ]
        ];
    }

    /**
     * Compile a aggregation query into SQL.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return string
     */
    public function compileAggregation(Builder $query, $aggregation)
    {
        if (! empty($query->joins)) {
            $this->compileJoinsAggregation($aggregation, $query->joins);
        }

        $aggregation->match($this->compileWheres($query));

        if (! empty($query->aggregate)) {
            $this->compileAggregateAggregation($aggregation, $query->aggregate);
        }

        return [
            'collection' => $aggregation->collection,
            'pipeline' => $aggregation->pipeline,
        ];
    }

    /**
     * Compile an insert statement into SQL.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  array  $values
     * @return string
     */
    public function compileInsert(Builder $query, array $values)
    {
        return ['collection' => $query->from, 'document' => $values];

        /*
        // Essentially we will force every insert to be treated as a batch insert which
        // simply makes creating the SQL easier for us since we can utilize the same
        // basic routine regardless of an amount of records given to us to insert.
        $table = $this->wrapTable($query->from);

        if (! is_array(reset($values))) {
            $values = [$values];
        }

        $columns = $this->columnize(array_keys(reset($values)));

        // We need to build a list of parameter place-holders of values that are bound
        // to the query. Each insert should have the exact same amount of parameter
        // bindings so we will loop through the record and parameterize them all.
        $parameters = collect($values)->map(function ($record) {
            return '('.$this->parameterize($record).')';
        })->implode(', ');

        return "insert into $table ($columns) values $parameters";
        */
    }

    /**
     * Compile an update statement into SQL.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  array  $values
     * @return string
     */
    public function compileUpdate(Builder $query, $values)
    {
        return [
            'collection' => $query->from,
            'filter' => $this->compileWheres($query),
            'update' => ['$set' => $values]
        ];
    }

    /**
     * Compile the "where" portions of the query.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return string
     */
    protected function compileWheres(Builder $query)
    {
        return (new Filter($query->wheres))->toArray();
    }

    /**
     * Compile the "select *" portion of the query.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  array  $columns
     * @return string|null
     */
    protected function compileColumns(Builder $query, $columns)
    {
        return in_array('*', $columns) ? [] : $columns;
    }

    /**
     * Compile the "limit" portions of the query.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  int  $limit
     * @return string
     */
    protected function compileLimit(Builder $query, $limit)
    {
        return (int) $limit;
    }

    /**
     * Compile the "offset" portions of the query.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  int  $offset
     * @return string
     */
    protected function compileOffset(Builder $query, $offset)
    {
        return (int) $offset;
    }

    /**
     * Compile the "order by" portions of the query.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  array  $orders
     * @return string
     */
    protected function compileOrders(Builder $query, $orders)
    {
        //
    }

    /**
     * Compile an aggregated select clause.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  array  $aggregate
     * @return string
     */
    protected function compileAggregate(Builder $query, $aggregate)
    {
        //
    }

    protected function compileAggregateAggregation(AggregationBuilder $aggregation, $aggregate)
    {
        return tap($aggregation, function ($aggregation) use ($aggregate) {
            $aggregation->group(['aggregate' => function ($field) use ($aggregate) {
                return $field->select(current($aggregate['columns']))->{$aggregate['function']}();
            }]);
        });
    }

    /**
     * Compile the "join" portions of the query.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  array  $joins
     * @return string
     */
    protected function compileJoins(Builder $query, $joins)
    {
        //
    }

    protected function compileJoinsAggregation(AggregationBuilder $aggregation, $joins)
    {
        return tap($aggregation, function ($aggregation) use ($joins) {
            array_map(function ($join) use ($aggregation) {
                $aggregation->lookup($join->table, '_', '_', $join->table)
                    ->unwind($join->table)
                    ->match($this->compileWheres($join));
            }, $joins);
        });
    }

    protected function shouldUseAggregation(Builder $query)
    {
        foreach ($this->aggregationComponents as $component) {
            if (! empty($query->$component)) {
                return true;
            }
        }

        return false;
    }

    /*
    public function compileSelect(Builder $query)
    public function compileRandom($seed)
    public function compileUpdate(Builder $query, $values)
    public function prepareBindingsForUpdate(array $bindings, array $values)
    public function compileDelete(Builder $query)
    public function prepareBindingsForDelete(array $bindings)
    */

    /*
    public function compileSelect(Builder $query)
    public function prepareBindingForJsonContains($binding)
    public function compileRandom($seed)
    public function compileExists(Builder $query)
    public function compileInsert(Builder $query, array $values)
    public function compileInsertGetId(Builder $query, $values, $sequence)
    public function compileInsertUsing(Builder $query, array $columns, string $sql)
    public function compileUpdate(Builder $query, $values)
    public function prepareBindingsForUpdate(array $bindings, array $values)
    public function compileDelete(Builder $query)
    public function prepareBindingsForDelete(array $bindings)
    public function compileTruncate(Builder $query)
    public function supportsSavepoints()
    public function compileSavepoint($name)
    public function compileSavepointRollBack($name)
    public function wrap($value, $prefixAlias = false)
    public function getOperators()
    */
}
