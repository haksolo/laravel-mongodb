<?php

namespace Extended\MongoDB\Tests;

// use Mockery;
use PHPUnit\Framework\TestCase;
use Extended\MongoDB\Database\Query\FilterBuilder;

class DatabaseQueryFilterBuilderTest extends TestCase
{
    public function test_preparing_of_parameters_basic_usage()
    {
        $this->assertEquals(['name', '$eq', 'Ronald', 'and'], FilterBuilder::prepare('name', '$eq', 'Ronald'));
        $this->assertEquals(['name', 'null', 'Ronald', 'and'], FilterBuilder::prepare('name', 'null', 'Ronald'));
    }

    public function test_preparing_of_parameters_other_boolean()
    {
        $this->assertEquals(['name', '$eq', 'Ronald', 'or'], FilterBuilder::prepare('name', '$eq', 'Ronald', 'or'));
    }

    public function test_preparting_of_parameters_coversion_of_operator()
    {
        $this->assertEquals(['name', '$eq', 'Ronald', 'and'], FilterBuilder::prepare('name', '=', 'Ronald'));
        // other variation
        $this->assertEquals(['name', '$gt', 'Ronald', 'and'], FilterBuilder::prepare('name', '>', 'Ronald'));
    }

    public function test_preparing_of_parameters_using_default()
    {
        $this->assertEquals(['name', '$eq', 'Ronald', 'and'], FilterBuilder::prepare('name', 'Ronald'));
    }

    public function test_building_of_filters()
    {
        $this->assertEquals([], (new FilterBuilder)->toArray());

        $this->assertEquals([
            '$and' => [
                ['name' => ['$eq' => 'Ronald']],
            ]
        ], (new FilterBuilder([
            ['name', '$eq', 'Ronald', 'and'],
        ]))->toArray());

        $this->assertEquals([
            '$and' => [
                ['name' => ['$eq' => 'Ronald']],
                ['age' => ['$gt' => 25]],
            ]
        ], (new FilterBuilder([
            ['name', '$eq', 'Ronald', 'and'],
            ['age', '$gt', 25, 'and'],
        ]))->toArray());

        $this->assertEquals([
            '$or' => [
                ['$and' => [
                    ['name' => ['$eq' => 'Ronald']],
                    ['age' => ['$gt' => 25]],
                ]],
                ['body' => ['$eq' => 'lean']]
            ]
        ], (new FilterBuilder([
            ['name', '$eq', 'Ronald', 'and'],
            ['age', '$gt', 25, 'and'],
            ['body', '$eq', 'lean', 'or'],
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
        ], (new FilterBuilder([
            ['name', '$eq', 'Ronald', 'and'],
            ['age', '$gt', 25, 'and'],
            ['body', '$eq', 'lean', 'or'],
            ['height', '$eq', 5.7, 'and'],
        ]))->toArray());
    }

    public function test_building_of_filters_with_nested_array()
    {
        $this->assertEquals([
            '$and' => [
                ['$and' => [
                    ['first' => ['$eq' => 1]],
                ]]
            ]
        ], (new FilterBuilder([
            [['first' => 1], null, null, 'and'],
        ]))->toArray());

        $this->assertEquals([
            '$and' => [
                ['$and' => [
                    ['first' => ['$eq' => 1]],
                    ['second' => ['$eq' => 2]],
                    ['third' => ['$eq' => 3]]
                ]]
            ]
        ], (new FilterBuilder([
            [['first' => 1, 'second' => 2, ['third', '=', 3]], null, null, 'and'],
        ]))->toArray());

        $this->assertEquals([
            '$or' => [
                ['$or' => [
                    ['first' => ['$eq' => 1]],
                    ['second' => ['$eq' => 2]],
                    ['third' => ['$eq' => 3]]
                ]]
            ]
        ], (new FilterBuilder([
            [['first' => 1, 'second' => 2, ['third', '=', 3, 'or']], null, null, 'or'],
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
        ], (new FilterBuilder([
            [['first' => 1, 'second' => 2, ['third', '=', 3, 'or']], null, null, 'and'],
        ]))->toArray());
    }
}
