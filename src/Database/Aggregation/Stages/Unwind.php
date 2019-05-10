<?php

namespace Extended\MongoDB\Database\Aggregation\Stages;

use Extended\MongoDB\Database\Aggregation\Expression\Field as FieldExpression;
use Illuminate\Contracts\Support\Arrayable;

class Unwind implements Arrayable
{
    protected $path;

    public function __construct($path)
    {
        $this->path = $path;
    }

    public function toArray()
    {
        // return ['$unwind' => ['path' => (string) new FieldExpression(null, $this->path)]];
        return ['$unwind' => ['path' => (string) $this->newExpression($this->path)]];
    }

    protected function newExpression($field)
    {
        return new FieldExpression(null, $field);
    }
}
