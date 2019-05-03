<?php

namespace Khronos\MongoDB\Database\Aggregation\Stages;

use Khronos\MongoDB\Database\Aggregation\FieldExpression;
use Illuminate\Contracts\Support\Arrayable;

class GraphLookup implements Arrayable
{
    protected $from;

    protected $startWith;

    protected $connectFromField;

    protected $connectToField;

    protected $as;

    protected $maxDepth;

    protected $depthField;

    protected $restrictSearchWithMatch;

    public function __construct($from, $startWith, $connectFromField, $connectToField, $as, $maxDepth = null, $depthField = null, $restrictSearchWithMatch = null)
    {
        $this->from = $from;

        $this->startWith = $startWith;

        $this->connectFromField = $connectFromField;

        $this->connectToField = $connectToField;

        $this->as = $as;

        $this->maxDepth = $maxDepth;

        $this->depthField = $depthField;

        $this->restrictSearchWithMatch = $restrictSearchWithMatch;
    }

    public function toArray()
    {
        return ['$graphLookup' => array_filter([
            'from' => $this->from,
            'startWith' => (new FieldExpression($this->startWith, $this->connectToField))->toArray(),
            'connectFromField' => $this->connectFromField,
            'connectToField' => $this->connectToField,
            'as' => $this->as,
            'maxDepth' => $this->maxDepth,
            'depthField' => $this->depthField,
            'restrictSearchWithMatch' => $this->restrictSearchWithMatch,
        ])];
    }
}
