<?php

namespace Khronos\MongoDB\Database;

use InvalidArgumentException;
use MongoDB\Client;
use Illuminate\Support\Arr;
use Illuminate\Database\ConnectionResolverInterface;

class DatabaseManager implements ConnectionResolverInterface
{
    /**
     * The application instance.
     *
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * Create a new database manager instance.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    public function __construct($app)
    {
        $this->app = $app;
    }
    /**
     * Get a database connection instance.
     *
     * @param  string  $name
     * @return \Illuminate\Database\ConnectionInterface
     */
    public function connection($name = null)
    {
        $name = $name ?: 'default';

        $connections = $this->app['config']['database.mongodb'];

        if (is_null($config = Arr::get($connections, $name))) {
            throw new InvalidArgumentException("Database [{$name}] not configured.");
        }

        $config = Arr::add(Arr::add($config, 'prefix', ''), 'name', $name);

        return $this->createConnection($config);
    }

    /**
     * Get the default connection name.
     *
     * @return string
     */
    public function getDefaultConnection()
    {
        return $this->app['config']['database.default'];
    }

    /**
     * Set the default connection name.
     *
     * @param  string  $name
     * @return void
     */
    public function setDefaultConnection($name)
    {
        $this->app['config']['database.default'] = $name;
    }

    /**
     * Create a new connection instance.
     *
     * @param  array    $config
     * @return \Illuminate\Database\Connection
     *
     * @throws \InvalidArgumentException
     */
    public function createConnection($config)
    {
        $client = $this->createClient($config);

        return new Connection($client, $config['database'], $config['prefix'], $config);
    }

    /**
     * Create a new client instance.
     *
     * @param  array    $config
     * @return \MongoDB\Client
     *
     * @throws \InvalidArgumentException
     */
    public function createClient($config)
    {
        extract($config, EXTR_SKIP);

        $dsn = "mongodb://{$host}:{$port}"/* . "/{$database}"*/;

        [$username, $password] = [
            $config['username'] ?? null, $config['password'] ?? null,
        ];

        return new Client($dsn, ['username' => $username, 'password' => $password], $config['options'] ?? []);
    }

    /**
     * Dynamically pass methods to the default connection.
     *
     * @param  string  $method
     * @param  array   $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->connection()->$method(...$parameters);
    }
}
