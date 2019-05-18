<?php

namespace Extended\MongoDB\Support;

/**
 * @codeCoverageIgnore
 */
class CustomExpression
{
    public function then()
    {
        return function ($then, $else = null) {
            return $this->cond($then, $else);
        };
    }

    public function objectAt()
    {
        return function ($path) {
            return $this->objectToArray()
                ->filter(function ($item) use ($path) {
                    return $item->k->eq($path->toString());
                })
                ->map(function ($item) {
                    return $item->v;
                })
                ->arrayElemAt(0);
        };

        /*
        '$arrayElemAt' => [
            ['$map' => [
                'input' => [
                    '$filter' => [
                        'input' => [ '$objectToArray' => '$plan.groups' ],
                        'as' => 'item',
                        'cond' => ['$eq' => ['$$item.k', ['$toString' => '$group']]]
                    ]
                ],
                'as' => 'item',
                'in' => '$$item.v'
            ]], 0
        ]
        */
    }

    public function diffDays()
    {
        return function ($date, $inclusive = true) {
            return $this->toDate()
                ->add($inclusive ? 86400000 : 0)
                ->subtract($date->toDate())
                ->divide(86400000);
        };
    }
}
