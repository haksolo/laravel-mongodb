<?php

namespace Extended\MongoDB\Tests\Integration\Database;

use Orchestra\Testbench\TestCase;
use Extended\MongoDB\Database\Eloquent\Model;
use Extended\MongoDB\Support\Facades\DB;

class EloquentModelTest extends TestCase
{
    protected function tearDown(): void
    {
        DB::table('books')->truncate();

        parent::tearDown();
    }

    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
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

    public function testModelCreate()
    {
        $book = Book::create(['title' => 'Laravel', 'description' => 'A PHP Framework']);

        $this->assertEquals($book->getKey(), Book::first()->getKey());
        // $this->assertTrue($book->is(Book::first()));
    }

}

class Book extends Model
{
    protected $fillable = ['title', 'description'];
}
