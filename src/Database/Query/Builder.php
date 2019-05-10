<?php

namespace Extended\MongoDB\Database\Query;

// use Closure;
use Extended\MongoDB\Database\Aggregation\Builder as AggregationBuilder;
// use Extended\MongoDB\Database\Query\Operators\AndOperator;
use Illuminate\Database\ConnectionInterface as Connection;
// use Illuminate\Database\Concerns\BuildsQueries;
use Illuminate\Database\Query\Builder as BaseBuilder;

class Builder extends BaseBuilder
{
    public $filter;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Add a basic where clause to the query.
     *
     * @param  string|array|\Closure  $column
     * @param  mixed   $operator
     * @param  mixed   $value
     * @param  string  $boolean
     * @return $this
     */
    public function where($column, $operator = null, $value = null, $boolean = 'and')
    {
        $this->filter[] = func_get_args();

        // return parent::where(...func_get_args());

        return $this;
    }

    /**
     * Execute the query as a "select" statement.
     *
     * @param  array|string  $columns
     * @return \Illuminate\Support\Collection
     */
    public function get($columns = ['*'])
    {
        $filter = new FilterBuilder($this->filter);

        $query = ['collection' => $this->from, 'filter' => $filter->toArray(), 'options' => [
            // 'projection' => $this->columns($columns),
            'limit' => $this->limit,
            // 'skip' => $this->offset,
            // 'sort' => $this->sort,
        ]];

        return collect($this->connection->select($query));
    }

    /**
     * Insert a new record and get the value of the primary key.
     *
     * @param  array  $values
     * @param  string|null  $sequence
     * @return int
     */
    public function insertGetId(array $values, $sequence = null)
    {
        $query = ['collection' => $this->from, 'document' => $values];

        return $this->connection->insert($query)->getInsertedId();
    }

    /**
     * Retrieve the "count" result of the query.
     *
     * @param  string  $columns
     * @return int
     */
    public function count($columns = '*')
    {
        return (int) $this->sum(1);
    }

    /**
     * Execute an aggregate function on the database.
     *
     * @param  string  $function
     * @param  array   $columns
     * @return mixed
     */
    public function aggregate($function, $columns = ['*'])
    {
        $results = $this->aggregation()
            ->match(new FilterBuilder($this->filter))
            ->group(['aggregate' => function ($query) use ($function, $columns) {
                return $query->select(current($columns))->{$function}();
            }])
            ->get();

        if (! $results->isEmpty()) {
            return $results[0]->aggregate;
        }
    }

    /**
     * Run a truncate statement on the table.
     *
     * @return void
     */
    public function truncate()
    {
        $this->connection->truncate($this->from);
    }

    public function aggregation()
    {
        return (new AggregationBuilder($this->connection))->from($this->from);
    }
}
