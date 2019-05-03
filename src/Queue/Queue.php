<?php

namespace Khronos\MongoDB\Queue;

use Khronos\MongoDB\Database\Connection;
use Illuminate\Queue\Queue as BaseQueue;
use Illuminate\Contracts\Queue\Queue as QueueContract;

class Queue extends BaseQueue implements QueueContract
{
    protected $connection;

    protected $collection;

    public function __construct(Connection $connection, $collection = 'default')
    {
        $this->connection = $connection;

        $this->collection = $collection;
    }

    /**
     * Get the size of the queue.
     *
     * @param  string  $queue
     * @return int
     */
    public function size($queue = null)
    {
        //
    }

    /**
     * Push a new job onto the queue.
     *
     * @param  string|object  $job
     * @param  mixed   $data
     * @param  string  $queue
     * @return mixed
     */
    public function push($job, $data = '', $queue = null)
    {
        /*
        static::createPayloadUsing(function () use ($job) {
            // dd(get_class($job), $job->getIdentifier());
            return ['entity' => $job->getIdentifier()];
        });
        */

        $payload = $this->createPayload($job, $this->getQueue($queue), $data);

        return $this->pushRaw($payload, $this->getQueue($queue));
    }

    /**
     * Push a raw payload onto the queue.
     *
     * @param  string  $payload
     * @param  string  $queue
     * @param  array   $options
     * @return mixed
     */
    public function pushRaw($payload, $queue = null, array $options = [])
    {
        return Model::unguarded(function () use ($payload, $queue) {
            return Model::on($this->connection->getName())
                ->getQuery()
                ->from($queue)
                ->insertGetId($payload);
        });
    }

    /**
     * Create a payload string from the given job and data.
     *
     * @param  string  $job
     * @param  string  $queue
     * @param  mixed   $data
     * @return string
     *
     * @throws \Illuminate\Queue\InvalidPayloadException
     */
    protected function createPayload($job, $queue, $data = '')
    {
        return $this->createPayloadArray($job, $queue, $data);
    }

    /**
     * Push a new job onto the queue after a delay.
     *
     * @param  \DateTimeInterface|\DateInterval|int  $delay
     * @param  string|object  $job
     * @param  mixed   $data
     * @param  string  $queue
     * @return mixed
     */
    public function later($delay, $job, $data = '', $queue = null)
    {
        //
    }

    /**
     * Pop the next job off of the queue.
     *
     * @param  string  $queue
     * @return \Illuminate\Contracts\Queue\Job|null
     */
    public function pop($queue = null)
    {
        $queue = $this->getQueue($queue);

        $model = Model::on($this->connection->getName())->from($queue)->first();
        // $model = (new Model)->setConnection();
        dd($model->job($this->container));

        return $model;

        // return $this->asJob(static::first(), $this->getQueue($queue));
    }

    /**
     * Get the queue or return the default.
     *
     * @param  string|null  $queue
     * @return string
     */
    public function getQueue($queue)
    {
        return 'tasks';

        return $queue ?: $this->collection;
    }

    /**
     * Delete a reserved job from the queue.
     *
     * @param  string  $queue
     * @param  string  $id
     * @return void
     */
    public function deleteReserved($queue, $id)
    {
        // $this->database->delete(['collection' => $this->collection, 'filter' => ['_id' => $id]]);
    }

    public function get($id, $queue = null)
    {
        return $this->asJob(static::find($id), $this->getQueue($queue));
    }

    public function asJob($model, $queue = null)
    {
        return new Job($this->container, $this, $model, $this->connectionName, $queue);
    }
}
