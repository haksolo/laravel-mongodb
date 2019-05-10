<?php

namespace Extended\MongoDB\Database\Aggregation;

use Extended\MongoDB\Database\Aggregation\Expression\Field as FieldExpression;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Traits\Macroable;
use Illuminate\Contracts\Support\Arrayable;

class Expression implements Arrayable
{
    use Macroable {
        __call as macroCall;
    }

    protected $expression;

    public function __construct($expression = null)
    {
        $this->expression = $expression;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->parse($this->expression());
    }

    protected function expression()
    {
        return $this->expression;
    }

    protected function parse($expression, $prop = null)
    {
        if ($expression instanceof \Closure) {
            $expression = call_user_func_array(
                $expression, Arr::wrap($this->parameters($prop))
            );
        }

        if (is_array($expression)) {
            return $this->parseArray($expression);
        }

        if ($expression instanceof FieldExpression) {
            // return (string) $expression;
            return $expression->toValue();
        }

        if ($expression instanceof Arrayable) {
            return $expression->toArray();
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
        return new FieldExpression(null, null);
    }



    const DEFAULT_PREFIX = '$';

    // protected static $operatorResolver;

    protected static $namespace = __NAMESPACE__ . '\\Operators';

    public static function factory(...$parameters)
    {
        return new static(...$parameters);
    }

    protected function base()
    {
        return $this->expression();
    }

    /**
     * Handle dynamic method calls into the expression.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @throws \BadMethodCallException
     */
    public function __call($method, $parameters)
    {
        if (static::hasMacro($method)) {
            return $this->macroCall($method, $parameters);
        }

        $operator = static::$namespace.'\\'.Str::studly($method);
        if (class_exists($operator.'Operator')) {
            $operator .= 'Operator';
        }

        if (class_exists($operator)) {
            return $operator::factory($this->base(), ...$parameters);
        }

        throw new \Exception(sprintf('Invalid expression method %s.', $method));

        /*if (method_exists($this, 'custom'.Str::studly($method))) {
            return $this->{'custom'.Str::studly($method)}(...$parameters);
        }*/
    }
}
