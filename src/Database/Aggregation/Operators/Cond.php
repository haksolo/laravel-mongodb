<?php

namespace Khronos\MongoDB\Database\Aggregation\Operators;

use Khronos\MongoDB\Database\Aggregation\OperatorExpression;

class Cond extends OperatorExpression
{
    protected $if;

    protected $then;

    protected $else;

    public function __construct($if, $then, $else)
    {
        $this->if = $if;

        $this->then = $then;

        $this->else = $else;
    }

    protected function expression()
    {
        return [$this->if, $this->then, $this->else];
    }
}
