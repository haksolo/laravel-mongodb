<?php

namespace Extended\MongoDB\Database\Aggregation\Operators;

use Extended\MongoDB\Database\Aggregation\Expression\Operator as OperatorExpression;

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
