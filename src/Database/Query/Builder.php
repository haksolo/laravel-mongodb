<?php

namespace Extended\MongoDB\Database\Query;

// use Closure;
use Extended\MongoDB\Database\Aggregation\Builder as AggregationBuilder;
// use Extended\MongoDB\Database\Query\Operators\AndOperator;
use Illuminate\Database\ConnectionInterface as Connection;
// use Illuminate\Database\Concerns\BuildsQueries;
use Illuminate\Database\Query\Builder as BaseBuilder;
use Illuminate\Support\Arr;

class Builder extends BaseBuilder
{
    public $filter;

    public function __construct(Connection $connection, Grammar $grammar = null, Processor $processor = null)
    {
        $this->connection = $connection;

        $this->grammar = $grammar ?: $connection->getQueryGrammar();

        $this->processor = $processor ?: $connection->getPostProcessor();
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

        /*
        $sql = $this->grammar->compileInsertGetId($this, $values, $sequence);

        $values = $this->cleanBindings($values);

        return $this->processor->processInsertGetId($this, $sql, $values, $sequence);
        */
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
     * Run a pagination count query.
     *
     * @param  array  $columns
     * @return array
     */
    protected function runPaginationCountQuery($columns = ['*'])
    {
        return [['aggregate' => $this->count($columns)]];
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
