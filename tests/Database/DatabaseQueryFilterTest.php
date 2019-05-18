<?php

namespace Extended\MongoDB\Tests;

// use Mockery;
use PHPUnit\Framework\TestCase;
use Extended\MongoDB\Database\Query\Filter;

class DatabaseQueryFilterTest extends TestCase
{
    public function testBuildingOfFilters()
    {
        $this->assertEquals([], (new Filter)->toArray());

        $this->assertEquals([
            '$and' => [
                ['name' => ['$eq' => 'Ronald']],
            ]
        ], (new Filter([
            ['type' => 'Basic', 'column' => 'name', 'operator' => '$eq', 'value' => 'Ronald', 'boolean' => 'and'],
        ]))->toArray());

        $this->assertEquals([
            '$and' => [
                ['name' => ['$eq' => 'Ronald']],
                ['age' => ['$gt' => 25]],
            ]
        ], (new Filter([
            ['type' => 'Basic', 'column' => 'name', 'operator' => '$eq', 'value' => 'Ronald', 'boolean' => 'and'],
            ['type' => 'Basic', 'column' => 'age', 'operator' => '$gt', 'value' => 25, 'boolean' => 'and'],
        ]))->toArray());

        $this->assertEquals([
            '$or' => [
                ['$and' => [
                    ['name' => ['$eq' => 'Ronald']],
                    ['age' => ['$gt' => 25]],
                ]],
                ['body' => ['$eq' => 'lean']]
            ]
        ], (new Filter([
            ['type' => 'Basic', 'column' => 'name', 'operator' => '$eq', 'value' => 'Ronald', 'boolean' => 'and'],
            ['type' => 'Basic', 'column' => 'age', 'operator' => '$gt', 'value' => 25, 'boolean' => 'and'],
            ['type' => 'Basic', 'column' => 'body', 'operator' => '$eq', 'value' => 'lean', 'boolean' => 'or'],
        ]))->toArray());

        $this->assertEquals([
            '$and' => [
                ['$or' => [
                    ['$and' => [
                        ['name' => ['$eq' => 'Ronald']],
                        ['age' => ['$gt' => 25]],
                    ]],
                    ['body' => ['$eq' => 'lean']]
                ]],
                ['height' => ['$eq' => 5.7]]
            ]
        ], (new Filter([
            ['type' => 'Basic', 'column' => 'name', 'operator' => '$eq', 'value' => 'Ronald', 'boolean' => 'and'],
            ['type' => 'Basic', 'column' => 'age', 'operator' => '$gt', 'value' => 25, 'boolean' => 'and'],
            ['type' => 'Basic', 'column' => 'body', 'operator' => '$eq', 'value' => 'lean', 'boolean' => 'or'],
            ['type' => 'Basic', 'column' => 'height', 'operator' => '$eq', 'value' => 5.7, 'boolean' => 'and'],
        ]))->toArray());

        // whereColumn
        $this->assertEquals([
            '$and' => [
                ['$expr' => ['$eq' => ['$foo', '$bar']]],
            ]
        ], (new Filter([
            ['type' => 'Column', 'first' => 'foo', 'operator' => '$eq', 'second' => 'bar', 'boolean' => 'and'],
        ]))->toArray());
    }

    public function testBuildingOfFiltersWithNestedArray()
    {
        $this->assertEquals([
            '$and' => [
                ['$and' => [
                    ['first' => ['$eq' => 1]],
                ]]
            ]
        ], (new Filter([
            ['type' => 'Nested', 'wheres' => [
                ['type' => 'Basic', 'column' => 'first', 'operator' => '=', 'value' => 1, 'boolean' => 'and']
            ], 'boolean' => 'and'],
        ]))->toArray());

        $this->assertEquals([
            '$and' => [
                ['$and' => [
                    ['first' => ['$eq' => 1]],
                    ['second' => ['$eq' => 2]],
                    ['third' => ['$eq' => 3]]
                ]]
            ]
        ], (new Filter([
            ['type' => 'Nested', 'wheres' => [
                ['type' => 'Basic', 'column' => 'first', 'operator' => '=', 'value' => 1, 'boolean' => 'and'],
                ['type' => 'Basic', 'column' => 'second', 'operator' => '=', 'value' => 2, 'boolean' => 'and'],
                ['type' => 'Basic', 'column' => 'third', 'operator' => '=', 'value' => 3, 'boolean' => 'and'],
            ], 'boolean' => 'and'],
        ]))->toArray());

        $this->assertEquals([
            '$or' => [
                ['$or' => [
                    ['first' => ['$eq' => 1]],
                    ['second' => ['$eq' => 2]],
                    ['third' => ['$eq' => 3]]
                ]]
            ]
        ], (new Filter([
            ['type' => 'Nested', 'wheres' => [
                ['type' => 'Basic', 'column' => 'first', 'operator' => '=', 'value' => 1, 'boolean' => 'or'],
                ['type' => 'Basic', 'column' => 'second', 'operator' => '=', 'value' => 2, 'boolean' => 'or'],
                ['type' => 'Basic', 'column' => 'third', 'operator' => '=', 'value' => 3, 'boolean' => 'or'],
            ], 'boolean' => 'or'],
        ]))->toArray());

        $this->assertEquals([
            '$and' => [
                ['$or' => [
                    ['$and' => [
                        ['first' => ['$eq' => 1]],
                        ['second' => ['$eq' => 2]],
                    ]],
                    ['third' => ['$eq' => 3]]
                ]]
            ]
        ], (new Filter([
            ['type' => 'Nested', 'wheres' => [
                ['type' => 'Basic', 'column' => 'first', 'operator' => '=', 'value' => 1, 'boolean' => 'and'],
                ['type' => 'Basic', 'column' => 'second', 'operator' => '=', 'value' => 2, 'boolean' => 'and'],
                ['type' => 'Basic', 'column' => 'third', 'operator' => '=', 'value' => 3, 'boolean' => 'or'],
            ], 'boolean' => 'and'],
        ]))->toArray());
    }
}
