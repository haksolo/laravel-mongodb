<?php

namespace Khronos\MongoDB\Database\Eloquent\Concerns;

use Khronos\MongoDB\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

trait HasRelationships
{
    protected function newBelongsTo(Builder $query, Model $child, $foreignKey, $ownerKey, $relation)
    {
        return new BelongsTo($query, $child, $foreignKey, $ownerKey, $relation);
    }
}
