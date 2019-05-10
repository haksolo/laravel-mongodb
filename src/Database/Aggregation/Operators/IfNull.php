<?php

namespace Extended\MongoDB\Database\Aggregation\Operators;

use Extended\MongoDB\Database\Aggregation\Expression\Operator as OperatorExpression;

class IfNull extends OperatorExpression
{
    protected $expression;

    protected $replacement;

    public function __construct($expression, $replacement)
    {
        $this->expression = $expression;

        $this->replacement = $replacement;
    }

    protected function expression()
    {
        return [$this->expression, $this->replacement];
    }
}
