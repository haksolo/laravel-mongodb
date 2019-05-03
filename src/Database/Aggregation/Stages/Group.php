<?php

namespace Khronos\MongoDB\Database\Aggregation\Stages;

use Khronos\MongoDB\Database\Aggregation\FieldExpression;
use Illuminate\Contracts\Support\Arrayable;

class Group implements Arrayable
{
    protected $expressions;

    public function __construct($expressions)
    {
        $this->expressions = $expressions;
    }

    public function toArray()
    {
        $expressions = [];
        foreach ($this->expressions as $field => $expression) {
            $expressions[$field] = (new FieldExpression($expression, $field))->toArray();
        };

        return ['$group' => array_merge(['_id' => null], $expressions)];
    }
}
