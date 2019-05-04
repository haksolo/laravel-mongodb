<?php

namespace Extended\MongoDB\Database\Query;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Contracts\Support\Arrayable;

class OperatorExpression implements Arrayable
{
    const DEFAULT_PREFIX = '$';

    protected $operator;

    protected $field;

    protected $value;

    public function __construct($field, $value)
    {
        $this->field = $field;

        $this->value = $value;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        if (is_null($this->field)) {
            return $this->parse($this->expression());
        }

        return [$this->field => $this->parse($this->expression())];
    }

    protected function expression()
    {
        if (is_callable($this->value)) {
            return $this->value;
        }

        return [$this->operator() => $this->value];
    }

    protected function parse($expression)
    {
        if ($expression instanceof \Closure) {
            $expression = call_user_func_array(
                $expression, Arr::wrap($this->parameters())
            );
        }

        if (is_array($expression)) {
            return $this->parseArray($expression);
        }

        if ($expression instanceof Arrayable) {
            $expression = $expression->toArray();
        }

        return $expression;
    }

    protected function parseArray($expressions)
    {
        foreach ($expressions as $prop => &$expression) {
            $expression = $this->parse($expression, $prop);
        }

        return $expressions;
    }

    protected function parameters()
    {
        return new FilterExpression(new Operators\AndOperator);
    }

    public function operator()
    {
        if (! is_null($this->operator)) {
            return $this->operator;
        }

        return static::DEFAULT_PREFIX.Str::camel(class_basename($this));
    }

    public function __invoke(...$parameters)
    {
        return $this;
    }
}
