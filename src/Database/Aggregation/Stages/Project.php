<?php

namespace Extended\MongoDB\Database\Aggregation\Stages;

use Extended\MongoDB\Database\Aggregation\Expression\Field as FieldExpression;
use Illuminate\Contracts\Support\Arrayable;

class Project implements Arrayable
{
    protected $specifications;

    public function __construct($specifications)
    {
        $this->specifications = $specifications;
    }

    public function toArray()
    {
        $specifications = [];
        foreach ($this->specifications as $field => $specification) {
            $specifications[$field] = $this->newFieldExpression($specification, $field)->toArray();
        }

        return ['$project' => $specifications];
    }

    protected function newFieldExpression($expression, $field)
    {
        return new FieldExpression($expression, $field);
    }
}
