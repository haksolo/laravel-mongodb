<?php

namespace Extended\MongoDB\Database\Aggregation\Operators;

use Closure;
use ReflectionFunction;
use Extended\MongoDB\Database\Aggregation\Expression\Field as FieldExpression;
use Extended\MongoDB\Database\Aggregation\Expression\Operator as OperatorExpression;

class Map extends OperatorExpression
{
    const DEFAULT_AS_VALUE = 'this';

    protected $input;

    protected $as;

    protected $in;

    public function __construct($input, $in, $as = self::DEFAULT_AS_VALUE)
    {
        $this->input = $input;

        $this->in = $in;

        $this->as = $as;
    }

    protected function expression()
    {
        return ['input' => $this->input, 'as' => $this->as(), 'in' => $this->in];
    }

    protected function parameters($parameter = null)
    {
        return new FieldExpression(null, $this->as(), '$$');
    }

    protected function as()
    {
        if (! $this->in instanceof Closure) {
            return $this->as;
        }

        $reflection = new ReflectionFunction($this->in);
        if (count($parameters = $reflection->getParameters())
            && $this->as = self::DEFAULT_AS_VALUE) {
            return $parameters[0]->name;
        }

        return $this->as;
    }
}
