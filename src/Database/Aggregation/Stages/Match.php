<?php

namespace Extended\MongoDB\Database\Aggregation\Stages;

use Extended\MongoDB\Database\Query\FilterBuilder;
use Illuminate\Contracts\Support\Arrayable;

class Match implements Arrayable
{
    protected $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function toArray()
    {
        return ['$match' => (object) $this->query];
    }
}
