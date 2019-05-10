<?php

namespace Extended\MongoDB\Database;

use Closure;
use Extended\MongoDB\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Arr;
use Illuminate\Database\ConnectionInterface;

class Connection implements ConnectionInterface
{
    protected $client;

    protected $database;

    protected $tablePrefix = '';

    protected $config = [];

    protected $defaultTypeMap = [
        // 'root' => 'array',
        // 'document' => BSON\Document::class, // 'array'
        // 'array' => BSON\Arr::class, //'array'
    ];

    /**
     * Create a new database connection instance.
     *
     * @param  \PDO|\Closure     $client
     * @param  string   $database
     * @param  string   $tablePrefix
     * @param  array    $config
     * @return void
     */
    public function __construct($client, $database = '', $tablePrefix = '', array $config = [])
    {
        $this->client = $client;

        $this->database = $database;

        $this->tablePrefix = $tablePrefix;

        $this->config = $config;
    }

    protected function options($options)
    {
        return array_merge(['typeMap' => $this->defaultTypeMap], $options);
    }

    /**
     * Begin a fluent query against a database table.
     *
     * @param  string  $table
     * @return \Illuminate\Database\Query\Builder
     */
    public function table($table)
    {
        return (new QueryBuilder($this))->from($table);
    }

    /**
     * Get a new raw query expression.
     *
     * @param  mixed  $value
     * @return \Illuminate\Database\Query\Expression
     */
    public function raw($value)
    {

    }

    /**
     * Run an aggregate statement against the database.
     *
     * @param  string  $query
     * @param  array   $bindings
     * @param  bool  $useReadPdo
     * @return mixed
     */
    public function aggregate($query, $options = [], $useReadPdo = true)
    {
        return $this->collection($query['collection'])->aggregate($query['pipeline'], $query['options'] ?? []);

        /*$collection = $this->client->selectCollection($this->database, $query['collection']);

        return $collection->aggregate($query['pipeline'], $this->options($options));*/
    }

    /**
     * Run a select statement and return a single result.
     *
     * @param  string  $query
     * @param  array   $bindings
     * @param  bool  $useReadPdo
     * @return mixed
     */
    public function selectOne($query, $options = [], $useReadPdo = true)
    {
        return $this->collection($query['collection'])->findOne($query['filter']);
    }

    /**
     * Run a select statement against the database.
     *
     * @param  string  $query
     * @param  array   $bindings
     * @param  bool  $useReadPdo
     * @return array
     */
    public function select($query, $options = [], $useReadPdo = true)
    {
        return $this->collection($query['collection'])->find($query['filter'], $query['options'] ?? []);

        /*
        $collection = $this->client->selectCollection($this->database, $query['collection']);

        return $collection->find($query['filter'], $this->options($options));
        */

        // return $collection->{$bindings['method']}($query['filter'], $bindings['options']);
    }

    protected function collection($collection, $database = null)
    {
        return $this->client()->selectCollection($database ?: $this->database, $collection);
    }

    /**
     * Run a select statement against the database and returns a generator.
     *
     * @param  string  $query
     * @param  array  $bindings
     * @param  bool  $useReadPdo
     * @return \Generator
     */
    public function cursor($query, $bindings = [], $useReadPdo = true)
    {
        // return $this->select($query, $bindings, $useReadPdo);
    }

    /**
     * Run an insert statement against the database.
     *
     * @param  string  $query
     * @param  array   $bindings
     * @return bool
     */
    public function insert($query, $bindings = [])
    {
        return $this->collection($query['collection'])->insertOne($query['document']);

        /*
        $collection = $this->client->selectCollection($this->database, $query['collection']);

        return $collection->insertOne($query['document'], $options);
        */
    }

    /**
     * Run an update statement against the database.
     *
     * @param  string  $query
     * @param  array   $bindings
     * @return int
     */
    public function update($query, $options = [])
    {
        $collection = $this->client->selectCollection($this->database, $query['collection']);

        return $collection->updateOne($query['filter'], $query['update'], $options);
    }

    /**
     * Run a delete statement against the database.
     *
     * @param  string  $query
     * @param  array   $bindings
     * @return int
     */
    public function delete($query, $options = [])
    {
        $collection = $this->client->selectCollection($this->database, $query['collection']);

        return $collection->deleteOne($query['filter'], $options);
    }

    /**
     * Execute an SQL statement and return the boolean result.
     *
     * @param  string  $query
     * @param  array   $bindings
     * @return bool
     */
    public function statement($query, $bindings = [])
    {

    }

    /**
     * Run an SQL statement and get the number of rows affected.
     *
     * @param  string  $query
     * @param  array   $bindings
     * @return int
     */
    public function affectingStatement($query, $bindings = [])
    {

    }

    /**
     * Run a raw, unprepared query against the PDO connection.
     *
     * @param  string  $query
     * @return bool
     */
    public function unprepared($query)
    {

    }

    /**
     * Prepare the query bindings for execution.
     *
     * @param  array  $bindings
     * @return array
     */
    public function prepareBindings(array $bindings)
    {

    }

    /**
     * Execute a Closure within a transaction.
     *
     * @param  \Closure  $callback
     * @param  int  $attempts
     * @return mixed
     *
     * @throws \Throwable
     */
    public function transaction(Closure $callback, $attempts = 1)
    {
        // $callback();

        // return $callback($this);
    }

    /**
     * Start a new database transaction.
     *
     * @return void
     */
    public function beginTransaction()
    {

    }

    /**
     * Commit the active database transaction.
     *
     * @return void
     */
    public function commit()
    {

    }

    /**
     * Rollback the active database transaction.
     *
     * @return void
     */
    public function rollBack()
    {

    }

    /**
     * Get the number of active transactions.
     *
     * @return int
     */
    public function transactionLevel()
    {

    }

    /**
     * Execute the given callback in "dry run" mode.
     *
     * @param  \Closure  $callback
     * @return array
     */
    public function pretend(Closure $callback)
    {

    }

    /**
     * Get the database connection name.
     *
     * @return string|null
     */
    public function getName()
    {
        return Arr::get($this->config, 'name');
    }


    public function client()
    {
        return $this->client;
    }

    public function truncate($collection)
    {
        return $this->collection($collection)->deleteMany([]);
    }

    /*public function __call()
    {

    }*/
}
