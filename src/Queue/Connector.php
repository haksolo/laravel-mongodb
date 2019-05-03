<?php

namespace Khronos\MongoDB\Queue;

use Illuminate\Database\ConnectionResolverInterface;
use Illuminate\Queue\Connectors\ConnectorInterface;

class Connector implements ConnectorInterface
{
    /**
     * Database connections.
     *
     * @var \Illuminate\Database\ConnectionResolverInterface
     */
    protected $connections;

    /**
     * Create a new connector instance.
     *
     * @param  \Illuminate\Database\ConnectionResolverInterface  $connections
     * @return void
     */
    public function __construct(ConnectionResolverInterface $connections)
    {
        $this->connections = $connections;
    }

    /**
     * Establish a queue connection.
     *
     * @param  array  $config
     * @return \Illuminate\Contracts\Queue\Queue
     */
    public function connect(array $config)
    {
        return new Queue(
            $this->connections->connection($config['connection'] ?? null),
            $config['queue'],
            $config['retry_after'] ?? 60
        );
    }
}
