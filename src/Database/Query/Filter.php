<?php

namespace Extended\MongoDB\Database\Query;

class Filter
{
    protected $wheres = [];

    protected $filter = [];

    protected $boolean = '$and';

    protected static $operators = [
        '=' =>  '$eq',
        '>' => '$gt',
        '>=' => '$gte',
        '<' => '$lt',
        '<=' => '$lte',
        '!=' => '$ne',
    ];

    public function __construct($wheres = [])
    {
        $this->wheres = $wheres;
    }

    public function toArray()
    {
        return $this->build();
    }

    protected function build()
    {
        foreach ($this->wheres as $where) {
            if ($this->compareBoolean($where['boolean'])) {
                $this->switchBoolean($where['boolean']);
            }

            $this->append($this->{'where'.$where['type']}($where));
        }

        return array_filter($this->filter);
    }

    protected function whereBasic($where)
    {
        return $this->default($where['column'], $where['operator'], $where['value']);
    }

    protected function whereNested($where)
    {
        $filter = new static(
            $where['query'] instanceof Builder
                ? $where['query']->wheres
                : $where['wheres']
        );

        return $filter->toArray();
    }

    protected function whereColumn($where)
    {
        return $this->default('$expr', $where['operator'],
            $this->prefix([$where['first'], $where['second']])
        );
    }

    protected function whereIn($where)
    {
        return $this->default($where['column'], '$in', $where['values']);
    }

    protected function compareBoolean($boolean)
    {
        return $this->boolean !== $this->prefix($boolean);
    }

    protected function switchBoolean($boolean)
    {
        $this->boolean = $this->prefix($boolean);

        if (! empty($this->filter)) {
            $this->filter = [$this->boolean => [$this->filter]];
        }

        return $this;
    }

    protected function append($filter)
    {
        $this->filter[$this->boolean][] = $filter;

        return $this;
    }

    protected function default($column, $operator, $value)
    {
        return [$column => [static::$operators[$operator] ?? $operator => $value]];
    }

    protected function prefix($value)
    {
        return is_array($value)
            ? array_map([$this, 'prefix'], $value)
            : '$'.$value;
    }

    /*
    protected function whereRaw(Builder $query, $where)
    #protected function whereBasic(Builder $query, $where)
    #protected function whereIn(Builder $query, $where)
    protected function whereNotIn(Builder $query, $where)
    protected function whereNotInRaw(Builder $query, $where)
    protected function whereInRaw(Builder $query, $where)
    protected function whereNull(Builder $query, $where)
    protected function whereNotNull(Builder $query, $where)
    protected function whereBetween(Builder $query, $where)
    protected function whereDate(Builder $query, $where)
    protected function whereTime(Builder $query, $where)
    protected function whereDay(Builder $query, $where)
    protected function whereMonth(Builder $query, $where)
    protected function whereYear(Builder $query, $where)
    #protected function whereColumn(Builder $query, $where)
    #protected function whereNested(Builder $query, $where)
    protected function whereSub(Builder $query, $where)
    protected function whereExists(Builder $query, $where)
    protected function whereNotExists(Builder $query, $where)
    protected function whereRowValues(Builder $query, $where)
    protected function whereJsonBoolean(Builder $query, $where)
    protected function whereJsonContains(Builder $query, $where)
    protected function whereJsonLength(Builder $query, $where)
    */
}
