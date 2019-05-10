<?php

namespace Extended\MongoDB\Database\Query;

class FilterBuilder
{
    protected $filter;

    protected static $operators = [
        '=' =>  '$eq',
        '>' => '$gt',
        '>=' => '$gte',
        '<' => '$lt',
        '<=' => '$lte',
        '!=' => '$ne',
    ];

    public function __construct($filter = [])
    {
        $this->filter = $filter;
    }

    public function toArray()
    {
        // dd($this->filter);
        return $this->build($this->filter ?: []);
    }

    protected function build($filters, $current = 'and')
    {
        $result = array_reduce($filters, function ($carry, $filter) use (&$current) {

            [$column, $operator, $value, $boolean] = $this->prepare(...$filter);

            if ($current !== $boolean) {
                if (! empty($carry)) {
                    $carry = [['$'.$current => $carry]];
                }
                $current = $boolean;
            }

            if (is_array($column)) {
                return array_merge($carry, array_filter([
                    $this->build($this->normalize($column, $boolean), $boolean)
                ]));
            }

            return array_merge($carry, [
                [$column => [$operator => $value]]
            ]);
        }, []);

        return array_filter(['$'.$current => $result]);
    }

    protected function normalize($filters, $boolean)
    {
        return array_map(function ($key) use ($filters, $boolean) {
            if (is_numeric($key) && is_array($filters[$key])) {
                return $filters[$key];
            }

            return [$key, '=', $filters[$key], $boolean];
        }, array_keys($filters));
    }

    public static function prepare($column, $operator, $value = null, $boolean = 'and', $default = false)
    {
        if (func_num_args() == 2 || $default) {
            return [$column, static::$operators['='], $operator, $boolean];
        }

        return [$column, static::$operators[$operator] ?? $operator, $value, $boolean];
    }
}
