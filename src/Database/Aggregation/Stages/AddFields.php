<?php

namespace Extended\MongoDB\Database\Aggregation\Stages;

use Extended\MongoDB\Database\Aggregation\Expression\Field as FieldExpression;
use Illuminate\Contracts\Support\Arrayable;

class AddFields implements Arrayable
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
            $specifications[$field] = $this->newFieldExpression($specification, $field)->toArray();;
        }

        return ['$addFields' => $specifications];
    }

    protected function newFieldExpression($expression, $field)
    {
        return new FieldExpression($expression, $field);
    }
}
