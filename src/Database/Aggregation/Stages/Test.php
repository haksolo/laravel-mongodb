<?php

namespace Extended\MongoDB\Database\Aggregation\Stages;

use Illuminate\Contracts\Support\Arrayable;

/**
 * @codeCoverageIgnore
 */
class Test implements Arrayable
{
    protected $attributes = [];

    public function __construct($attributes)
    {
        $this->attributes = $attributes;
    }

    public function toArray()
    {
        return $this->attributes;
    }
}
