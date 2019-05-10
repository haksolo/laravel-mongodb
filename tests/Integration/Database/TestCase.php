<?php

namespace Extended\MongoDB\Tests\Integration\Database;

use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.mongodb.default', [
            'host' => 'mongodb',
            'port' => 27017,
            'database' => 'dev',
            'username' => 'root',
            'password' => 'password',
        ]);
    }

    protected function getPackageProviders($app)
    {
        return [
            \Extended\MongoDB\Database\DatabaseServiceProvider::class
        ];
    }
}
