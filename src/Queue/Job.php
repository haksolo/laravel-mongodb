<?php

namespace Khronos\MongoDB\Queue;

use Illuminate\Container\Container;
use Illuminate\Queue\Jobs\Job as BaseJob;
use Illuminate\Contracts\Queue\Job as JobContract;

class Job extends BaseJob implements JobContract
{
    /**
     * The IoC container instance.
     *
     * @var \Illuminate\Container\Container
     */
    protected $container;

    /**
     * The database queue instance.
     *
     * @var \Illuminate\Queue\DatabaseQueue
     */
    protected $database;

    /**
     * The database job payload.
     *
     * @var \stdClass
     */
    protected $job;

    /**
     * Create a new job instance.
     *
     * @param  \Illuminate\Container\Container  $container
     * @param  \Khronos\MongoDB\Queue\Queue  $database
     * @param  \stdClass  $job
     * @param  string  $connectionName
     * @param  string  $queue
     * @return void
     */
    // public function __construct(Container $container, Queue $database, $job, $connectionName, $queue)
    public function __construct(Container $container, Model $job)
    {
        $this->container = $container;

        // $this->database = $database;

        $this->job = $job;
    }

    /**
     * Get the job identifier.
     *
     * @return string
     */
    public function getJobId()
    {
        return $this->job->id;
    }

    /**
     * Get the number of times the job has been attempted.
     *
     * @return int
     */
    public function attempts()
    {

    }

    /**
     * Get the raw body string for the job.
     *
     * @return string
     */
    public function getRawBody()
    {
        return $this->job->toArray();
    }

    /**
     * Delete the job from the queue.
     *
     * @return void
     */
    public function delete()
    {
        parent::delete();

        $this->job->delete();

        // $this->database->deleteReserved($this->queue, $this->getJobId());
    }

    /**
     * Get the decoded body of the job.
     *
     * @return array
     */
    public function payload()
    {
        return array_map(function ($value) {
            return is_object($value) ? (array) $value : $value;
        }, $this->getRawBody());
    }
}
