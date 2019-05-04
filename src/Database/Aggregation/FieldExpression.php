<?php

namespace Extended\MongoDB\Database\Aggregation;

use Illuminate\Support\Traits\Macroable;

class FieldExpression extends Expression
{
    const DEFAULT_FIELD_PREFIX = '$';

    protected $field;

    protected $prefix = self::DEFAULT_FIELD_PREFIX;

    public function __construct($expression, $field = null, $prefix = self::DEFAULT_FIELD_PREFIX)
    {
        parent::__construct($expression);

        $this->field = $field;

        $this->prefix = $prefix;
    }

    public function input($field)
    {
        return new static($this->expression, $field, null);
    }

    public function select($field)
    {
        return new static($this->expression, $field);
    }

    public function root()
    {
        return new static($this->expression, 'ROOT', '$$');
    }

    protected function base()
    {
        return (string) $this;
    }

    protected function parameters()
    {
        return $this;
    }

    /**
     * Dynamically retrieve attributes on the field.
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
        $path = implode('.', array_filter([$this->field, $key]));

        return new static($this->expression, $path, $this->prefix);
    }

    /**
     * Convert the expression to its string representation.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->prefix.$this->field;
    }
}
