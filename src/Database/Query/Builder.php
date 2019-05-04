<?php

namespace Khronos\MongoDB\Database\Query;

use Closure;
use Khronos\MongoDB\Database\Aggregation\Builder as AggregationBuilder;
use Khronos\MongoDB\Database\Query\Operators\AndOperator;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Concerns\BuildsQueries;

class Builder
{
    use BuildsQueries;

    public $connection;

    // public $grammar;

    // public $processor;

    // public $bindings = [];

    /**
     * An aggregate function and column to be run.
     *
     * @var array
     */
    public $aggregate;

    /**
     * The columns that should be returned.
     *
     * @var array
     */
    public $columns;

    // public $distinct = false;

    /**
     * The table which the query is targeting.
     *
     * @var string
     */
    public $collection; // public $from;

    // public $joins;

    /**
     * The where constraints for the query.
     *
     * @var array
     */
    public $filter = []; // public $wheres = [];

    // public $groups;

    // public $havings;

    /**
     * The orderings for the query.
     *
     * @var array
     */
    public $sort; // public $orders;

    /**
     * The maximum number of records to return.
     *
     * @var int
     */
    public $limit;

    /**
     * The number of records to skip.
     *
     * @var int
     */
    public $offset;

    // public $unions;

    // public $unionLimit;

    // public $unionOffset;

    // public $unionOrders;

    // public $lock;

    /**
     * All of the available clause operators.
     *
     * @var array
     */
    protected $operators = [
        '=' => 'eq',
        '>' => 'gt',
        '>=' => 'gte',
        '<' => 'lt',
        '<=' => 'lte',
        '!=' => 'ne',
    ];

    // public $useWritePdo = false;

    /**
     * Create a new query builder instance.
     *
     * @param  \Illuminate\Database\ConnectionInterface  $connection
     * @return void
     */
    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;

        $this->filter = new FilterExpression(new AndOperator); // $connection->getFilterExpression();
    }

    // public function select($columns = ['*'])

    // public function selectSub($query, $as)

    // public function selectRaw($expression, array $bindings = [])

    // public function fromSub($query, $as)

    // public function fromRaw($expression, $bindings = [])

    // protected function createSub($query)

    // protected function parseSub($query)

    /**
     * Add a new select column to the query.
     *
     * @param  array|mixed  $column
     * @return $this
     */
    public function addSelect($column)
    {
        return $this;
    }

    // public function distinct()

    /**
     * Set the table which the query is targeting.
     *
     * @param  string  $collection
     * @return $this
     */
    public function from($collection)
    {
        $this->collection = $collection;

        return $this;
    }

    /**
     * Add a join clause to the query.
     *
     * @param  string  $table
     * @param  \Closure|string  $first
     * @param  string|null  $operator
     * @param  string|null  $second
     * @param  string  $type
     * @param  bool    $where
     * @return $this
     */
    public function join($table, $first, $operator = null, $second = null, $type = 'inner', $where = false)
    {
        $this->aggregate = $this->aggregation()
            ->lookup($table, $first, last(explode('.', $second, 2)), $table)
            ->unwind($table);

        return $this;
    }

    // public function joinWhere($table, $first, $operator, $second, $type = 'inner')

    // public function joinSub($query, $as, $first, $operator = null, $second = null, $type = 'inner', $where = false)

    // public function leftJoin($table, $first, $operator = null, $second = null)

    // public function leftJoinWhere($table, $first, $operator, $second)

    // public function leftJoinSub($query, $as, $first, $operator = null, $second = null)

    // public function rightJoin($table, $first, $operator = null, $second = null)

    // public function rightJoinWhere($table, $first, $operator, $second)

    // public function rightJoinSub($query, $as, $first, $operator = null, $second = null)

    // public function crossJoin($table, $first = null, $operator = null, $second = null)

    // protected function newJoinClause(self $parentQuery, $type, $table)

    // public function mergeWheres($wheres, $bindings)

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
        [$column, $operator, $value] = $this->prepareParameters(
            $column, $operator, $value, $boolean, func_num_args() == 2
        );

        $this->filter->append(FilterExpression::resolve($operator, $column, $value), $boolean);
        // $this->filter->append($operator, $column, $value, $boolean);

        return $this;
    }

    // protected function addArrayOfWheres($column, $boolean, $method = 'where')

    /**
     * Prepare the value and operator for a where clause.
     *
     * @param  string  $value
     * @param  string  $operator
     * @param  bool  $useDefault
     * @return array
     *
     * @throws \InvalidArgumentException
     */
    public function prepareValueAndOperator($value, $operator, $useDefault = false)
    {
        if ($useDefault) {
            return [$operator, '='];
        }/* elseif ($this->invalidOperatorAndValue($operator, $value)) {
            throw new InvalidArgumentException('Illegal operator and value combination.');
        }*/

        return [$value, $operator];
    }

    // protected function invalidOperatorAndValue($operator, $value)

    // protected function invalidOperator($operator)

    // public function orWhere($column, $operator = null, $value = null)

    // public function whereColumn($first, $operator = null, $second = null, $boolean = 'and')

    // public function orWhereColumn($first, $operator = null, $second = null)

    // public function whereRaw($sql, $bindings = [], $boolean = 'and')

    // public function orWhereRaw($sql, $bindings = [])

    /**
     * Add a "where in" clause to the query.
     *
     * @param  string  $column
     * @param  mixed   $values
     * @param  string  $boolean
     * @param  bool    $not
     * @return $this
     */
    public function whereIn($column, $values, $boolean = 'and', $not = false)
    {
        $this->where($column, ['in', 'nin'][$not], $values, $boolean);

        return $this;
    }

    // public function orWhereIn($column, $values)

    // public function whereNotIn($column, $values, $boolean = 'and')

    // public function orWhereNotIn($column, $values)

    // protected function whereInSub($column, Closure $callback, $boolean, $not)

    // protected function whereInExistingQuery($column, $query, $boolean, $not)

    // public function whereIntegerInRaw($column, $values, $boolean = 'and', $not = false)

    // public function whereIntegerNotInRaw($column, $values, $boolean = 'and')

    /**
     * Add a "where null" clause to the query.
     *
     * @param  string  $column
     * @param  string  $boolean
     * @param  bool    $not
     * @return $this
     */
    public function whereNull($column, $boolean = 'and', $not = false)
    {
        $this->where($column, ['eq', 'ne'][$not], null, $boolean);

        return $this;
    }

    // public function orWhereNull($column)

    /**
     * Add a "where not null" clause to the query.
     *
     * @param  string  $column
     * @param  string  $boolean
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function whereNotNull($column, $boolean = 'and')
    {
        return $this->whereNull($column, $boolean, true);
    }

    // public function whereBetween($column, array $values, $boolean = 'and', $not = false)

    // public function orWhereBetween($column, array $values)

    // public function whereNotBetween($column, array $values, $boolean = 'and')

    // public function orWhereNotBetween($column, array $values)

    // public function orWhereNotNull($column)

    // public function whereDate($column, $operator, $value = null, $boolean = 'and')

    // public function orWhereDate($column, $operator, $value = null)

    // public function whereTime($column, $operator, $value = null, $boolean = 'and')

    // public function orWhereTime($column, $operator, $value = null)

    // public function whereDay($column, $operator, $value = null, $boolean = 'and')

    // public function orWhereDay($column, $operator, $value = null)

    // public function whereMonth($column, $operator, $value = null, $boolean = 'and')

    // public function orWhereMonth($column, $operator, $value = null)

    // public function whereYear($column, $operator, $value = null, $boolean = 'and')

    // public function orWhereYear($column, $operator, $value = null)

    // protected function addDateBasedWhere($type, $column, $operator, $value, $boolean = 'and')

    // public function whereNested(Closure $callback, $boolean = 'and')

    // public function forNestedWhere()

    /**
     * Add another query builder as a nested where to the query builder.
     *
     * @param  \Illuminate\Database\Query\Builder|static $query
     * @param  string  $boolean
     * @return $this
     */
    public function addNestedWhereQuery($query, $boolean = 'and')
    {
        $this->filter = $query->filter;

        return $this;
    }

    // protected function whereSub($column, $operator, Closure $callback, $boolean)

    // public function whereExists(Closure $callback, $boolean = 'and', $not = false)

    // public function orWhereExists(Closure $callback, $not = false)

    // public function whereNotExists(Closure $callback, $boolean = 'and')

    // public function orWhereNotExists(Closure $callback)

    // public function addWhereExistsQuery(self $query, $boolean = 'and', $not = false)

    // public function whereRowValues($columns, $operator, $values, $boolean = 'and')

    // public function orWhereRowValues($columns, $operator, $values)

    // public function whereJsonContains($column, $value, $boolean = 'and', $not = false)

    // public function orWhereJsonContains($column, $value)

    // public function whereJsonDoesntContain($column, $value, $boolean = 'and')

    // public function orWhereJsonDoesntContain($column, $value)

    // public function whereJsonLength($column, $operator, $value = null, $boolean = 'and')

    // public function orWhereJsonLength($column, $operator, $value = null)

    // public function dynamicWhere($method, $parameters)

    // protected function addDynamic($segment, $connector, $parameters, $index)

    // public function groupBy(...$groups)

    // public function having($column, $operator = null, $value = null, $boolean = 'and')

    // public function orHaving($column, $operator = null, $value = null)

    // public function havingBetween($column, array $values, $boolean = 'and', $not = false)

    // public function havingRaw($sql, array $bindings = [], $boolean = 'and')

    // public function orHavingRaw($sql, array $bindings = [])

    /**
     * Add an "order by" clause to the query.
     *
     * @param  string  $column
     * @param  string  $direction
     * @return $this
     */
    public function orderBy($column, $direction = 'asc')
    {
        return $this->sort($column, ['asc' => 1, 'desc' => -1][strtolower($direction)] ?? $direction);
    }

    // public function orderByDesc($column)

    // public function latest($column = 'created_at')

    // public function oldest($column = 'created_at')

    // public function inRandomOrder($seed = '')

    // public function orderByRaw($sql, $bindings = [])

    /**
     * Alias to set the "offset" value of the query.
     *
     * @param  int  $value
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function skip($value)
    {
        return $this->offset($value);
    }

    /**
     * Set the "offset" value of the query.
     *
     * @param  int  $value
     * @return $this
     */
    public function offset($value)
    {
        $this->offset = max(0, $value);

        return $this;
    }

    /**
     * Alias to set the "limit" value of the query.
     *
     * @param  int  $value
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function take($value)
    {
        return $this->limit($value);
    }

    /**
     * Set the "limit" value of the query.
     *
     * @param  int  $value
     * @return $this
     */
    public function limit($value)
    {
        $this->limit = $value;

        return $this;
    }

    /**
     * Set the limit and offset for a given page.
     *
     * @param  int  $page
     * @param  int  $perPage
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function forPage($page, $perPage = 15)
    {
        return $this->skip(($page - 1) * $perPage)->take($perPage);
    }

    // public function forPageAfterId($perPage = 15, $lastId = 0, $column = 'id')

    // protected function removeExistingOrdersFor($column)

    // public function union($query, $all = false)

    // public function unionAll($query)

    // public function lock($value = true)

    // public function lockForUpdate()

    // public function sharedLock()

    // public function toSql()

    // public function find($id, $columns = ['*'])

    /**
     * Get a single column's value from the first result of a query.
     *
     * @param  string  $column
     * @return mixed
     */
    public function value($column)
    {
        $result = (array) $this->first([$column => 1]);

        return count($result) > 0 ? $result[$column] : null;
    }

    /**
     * Execute the query as a "select" statement.
     *
     * @param  array  $columns
     * @return \Illuminate\Support\Collection
     */
    public function get($columns = ['*'])
    {
        [$method, $query, $options] = $this->toQueryOptions($columns);

        return collect($this->connection->{$method}($query, $options));
    }

    // protected function runSelect()

    // public function paginate($perPage = 15, $columns = ['*'], $pageName = 'page', $page = null)

    // public function simplePaginate($perPage = 15, $columns = ['*'], $pageName = 'page', $page = null)

    public function getCountForPagination($columns = ['*'])
    {
        return $this->count($columns);
    }

    // protected function runPaginationCountQuery($columns = ['*'])

    // protected function withoutSelectAliases(array $columns)

    // public function cursor()

    // public function chunkById($count, callable $callback, $column = 'id', $alias = null)

    // protected function enforceOrderBy()

    // public function pluck($column, $key = null)

    // protected function stripTableForPluck($column)

    // protected function pluckFromObjectColumn($queryResult, $column, $key)

    // protected function pluckFromArrayColumn($queryResult, $column, $key)

    // public function implode($column, $glue = '')

    // public function exists()

    // public function doesntExist()

    /**
     * Retrieve the "count" result of the query.
     *
     * @param  string  $columns
     * @return int
     */
    public function count($columns = '*')
    {
        // @todo
        return ($this->aggregate ?: $this->aggregation())
            // ->match([])
            ->count('aggregate')
            // ->dd()
            ->first()
            ->aggregate;
             // $this->sum(1);
    }

    /**
     * Retrieve the minimum value of a given column.
     *
     * @param  string  $column
     * @return mixed
     */
    public function min($column)
    {
        return $this->aggregate(__FUNCTION__, [$column]);
    }

    /**
     * Retrieve the maximum value of a given column.
     *
     * @param  string  $column
     * @return mixed
     */
    public function max($column)
    {
        return $this->aggregate(__FUNCTION__, [$column]);
    }

    /**
     * Retrieve the sum of the values of a given column.
     *
     * @param  string  $column
     * @return mixed
     */
    public function sum($column)
    {
        return $this->aggregate(__FUNCTION__, [$column]);
    }

    /**
     * Retrieve the average of the values of a given column.
     *
     * @param  string  $column
     * @return mixed
     */
    public function avg($column)
    {
        return $this->aggregate(__FUNCTION__, [$column]);
    }

    /**
     * Alias for the "avg" method.
     *
     * @param  string  $column
     * @return mixed
     */
    public function average($column)
    {
        return $this->avg($column);
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
        $result = $this->aggregation()
            ->group(['aggregate' => function ($query) use ($function, $columns) {
                return $query->select(current($columns))->{$function}();
            }])->first();

        return $result->aggregate;
    }

    // public function numericAggregate($function, $columns = ['*'])

    // protected function setAggregate($function, $columns)

    // protected function onceWithColumns($columns, $callback)

    // public function insert(array $values)

    /**
     * Insert a new record and get the value of the primary key.
     *
     * @param  array  $values
     * @param  string|null  $sequence
     * @return int
     */
    public function insertGetId(array $values, $sequence = null)
    {
        $query = ['collection' => $this->collection, 'document' => $values];

        return $this->connection->insert($query)->getInsertedId();
    }

    // public function insertUsing(array $columns, $query)

    /**
     * Update a record in the database.
     *
     * @param  array  $values
     * @return int
     */
    public function update(array $values)
    {
        [$query, $options] = $this->toUpdateOptions($values);

        return $this->connection->update($query, $options);
    }

    // public function updateOrInsert(array $attributes, array $values = [])

    // public function increment($column, $amount = 1, array $extra = [])

    // public function decrement($column, $amount = 1, array $extra = [])

    /**
     * Delete a record from the database.
     *
     * @param  mixed  $id
     * @return int
     */
    public function delete()
    {
        $query = ['collection' => $this->collection, 'filter' => $this->filter->toArray()];

        return $this->connection->delete($query, $options = []);
    }

    // public function truncate()

    // public function newQuery()

    // protected function forSubQuery()

    // public function raw($value)

    // public function getBindings()

    // public function getRawBindings()

    // public function setBindings(array $bindings, $type = 'where')

    // public function addBinding($value, $type = 'where')

    // public function mergeBindings(self $query)

    // protected function cleanBindings(array $bindings)

    /**
     * Get the database connection instance.
     *
     * @return \Illuminate\Database\ConnectionInterface
     */
    public function getConnection()
    {
        return $this->connection;
    }

    // public function getProcessor()

    // public function getGrammar()

    /**
     * Use the write pdo for query.
     *
     * @return $this
     */
    public function useWritePdo()
    {
        $this->useWritePdo = true;

        return $this;
    }

    // public function cloneWithout(array $properties)

    // public function cloneWithoutBindings(array $except)

    // public function __call($method, $parameters)

    protected function prepareParameters($column, $operator, $value, $boolean = 'and', $useDefault = true)
    {
        if (is_array($column)) {
            return ['$and', $boolean, $column];
        }

        if ($useDefault) {
            return [$column, $this->operators['='], $operator];
        }

        return [$column, $this->operators[$operator] ?? $operator, $value];
    }

    /**
     * Alias to set the "sort" value of the query.
     *
     * @param  int  $value
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function sort($field, $order = 1)
    {
        $this->sort[$field] = $order;

        return $this;
    }

    public function toQueryOptions($columns = [])
    {
        if ($this->aggregate instanceof AggregationBuilder) {
            return $this->aggregate
                ->from($this->collection)
                ->match($this->filter->implicit(true))
                // ->project($this->columns($columns))
                // ->dd('match')
                ->toQueryOptions($columns);
        }

        return ['select', [
            'collection' => $this->collection,
            'filter' => $this->filter->toArray()
        ], [
            'projection' => $this->columns($columns),
            'limit' => $this->limit,
            'skip' => $this->offset,
            'sort' => $this->sort,
        ]];
    }

    public function toUpdateOptions($values)
    {
        return [[
            'collection' => $this->collection,
            'filter' => $this->filter->toArray(),
            'update' => ['$set' => $values]
        ], []];
    }

    protected function columns($columns = [])
    {
        return $columns == ['*'] ? [] : $columns;
    }

    protected function aggregation()
    {
        return (new AggregationBuilder($this->connection))->from($this->collection);
    }
}
