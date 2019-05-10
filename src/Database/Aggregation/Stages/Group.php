<?php

namespace Extended\MongoDB\Database\Aggregation\Stages;

use Extended\MongoDB\Database\Aggregation\Expression\Field as FieldExpression;
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
            $expressions[$field] = $this->newFieldExpression($expression, $field)->toArray();
        };

        return ['$group' => array_merge(['_id' => null], $expressions)];
    }

    protected function newFieldExpression($expression, $field)
    {
        return new FieldExpression($expression, $field);
    }
}
