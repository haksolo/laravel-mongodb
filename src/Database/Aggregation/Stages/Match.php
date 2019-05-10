<?php

namespace Extended\MongoDB\Database\Aggregation\Stages;

use Extended\MongoDB\Database\Query\FilterBuilder;
// use Extended\MongoDB\Database\Query\FilterExpression;
// use Extended\MongoDB\Database\Query\Operators\AndOperator;
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
        $expression = $this->query;
        if ($expression instanceof FilterBuilder) {
            $expression = $expression->toArray();
        }

        return ['$match' => (object) $expression];

        // $query = new FilterExpression(new AndOperator(null, $this->query));

        // return ['$match' => (object) $query->toArray()];
    }
}
