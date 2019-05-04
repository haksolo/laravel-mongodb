<?php

namespace Extended\MongoDB\Database\Eloquent;

use Extended\MongoDB\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Builder as BaseBuilder;

class Builder extends BaseBuilder
{
    /**
     * Create a new Eloquent query builder instance.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return void
     */
    public function __construct(QueryBuilder $query)
    {
        $this->query = $query;
    }
}
