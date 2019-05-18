<?php

namespace Extended\MongoDB\Database\Aggregation;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Database\ConnectionInterface as Connection;

class Builder
{
    protected $connection;

    public $collection;

    public $pipeline = [];

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function from($collection)
    {
        $this->collection = $collection;

        return $this;
    }

    public function first()
    {
        return $this->get()->first();
    }

    public function get()
    {
        $query = ['collection' => $this->collection, 'pipeline' => $this->pipeline];

        return collect($this->connection->aggregate($query));
    }

    public function stage($stage)
    {
        $this->pipeline[] = $stage->toArray();

        return $this;
    }

    public function __call($method, $parameters)
    {
        $stage = __NAMESPACE__.'\\Stages\\'.Str::studly($method);
        if (class_exists($stage)) {
            return $this->stage(new $stage(...$parameters));
        }

        throw new \Exception(sprintf('Invalid %s stage.', Str::studly($method)));
    }

    /**
     * @codeCoverageIgnore
     */
    public function dump($stages = [])
    {
        $this->debug(__FUNCTION__, $stages);
    }

    /**
     * @codeCoverageIgnore
     */
    public function dd($stages = [])
    {
        $this->debug(__FUNCTION__, $stages);
    }

    /**
     * @codeCoverageIgnore
     */
    public function debug($function, $stages = [])
    {
        $function(array_filter($this->pipeline, function ($pipeline) use ($stages) {
            return $stages ? in_array(ltrim(key($pipeline), '$'), Arr::wrap($stages)) : true;
        }));
    }
}
