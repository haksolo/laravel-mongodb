<?php

namespace Khronos\MongoDB\Database\Query\Operators;

use Khronos\MongoDB\Database\Query\OperatorExpression;
use Khronos\MongoDB\Contracts\Database\LogicalOperator;

class AndOperator extends OperatorExpression implements LogicalOperator
{
    protected $operator = '$and';

    protected $expressions = [];

    protected $implicit = false;

    public function __construct($field = null, ...$expressions)
    {
        $this->field = $field;

        $this->expressions = $expressions;
    }

    public function append($expression)
    {
        $this->expressions[] = $expression;

        return $this;
    }

    protected function expression()
    {
        if ($this->implicit) {
            return array_reduce($this->expressions, function ($carry, $item) {
                return array_merge($carry, $this->parse($item));
            }, []);
        }

        return array_filter([$this->operator() => $this->expressions]);
    }

    public function __invoke(...$parameters)
    {
        [$implicit] = array_pad($parameters, 1, false);

        $this->implicit = $implicit;

        return $this;
    }
}
