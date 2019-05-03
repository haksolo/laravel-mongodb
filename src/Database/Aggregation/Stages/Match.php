<?php

namespace Khronos\MongoDB\Database\Aggregation\Stages;

use Khronos\MongoDB\Database\Query\FilterExpression;
use Khronos\MongoDB\Database\Query\Operators\AndOperator;
use Illuminate\Contracts\Support\Arrayable;

class Match implements Arrayable
{
    protected $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function toArray()
    {
        $query = new FilterExpression(new AndOperator(null, $this->query));

        return ['$match' => $query->toArray()];
    }
}
