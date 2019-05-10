<?php

namespace Extended\MongoDB\Tests\Integration\Database;

// use Orchestra\Testbench\TestCase;
use Extended\MongoDB\Database\Eloquent\Model;
use Extended\MongoDB\Support\Facades\DB;

class EloquentTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // EloquentStub::create();

        DB::insert(['collection' => 'books', 'document' => ['title' => 'Laravel']]);
        DB::insert(['collection' => 'books', 'document' => ['title' => 'Laravel']]);
    }

    protected function tearDown(): void
    {
        DB::truncate('books');

        parent::tearDown();
    }

    public function testTest()
    {
        $this->assertTrue(true);

        /*$model = EloquentStub::query()
            ->where('id', '=', 1)
            ->where('id', '=', 1)
            ->orWhere('name', '=', 'Ronald')
            ->orWhere('name', '=', 'Ronald')
            ->where('name', 'Ronald')
            ->first();*/

        /*$model = EloquentStub::where('id', '=', 1)
            ->orWhere([
                ['first', '=', 1],
                'second' => 2,
                'third' => 3,
            ])
            ->orWhere(function ($query) {
                return $query->where('id', 1);
            })
            ->first();*/

        // dump($model);

        // $this->markTestIncomplete('This test has not been implemented yet.');
    }

    public function testCount()
    {
        $this->assertEquals(2, EloquentStub::count());
    }
}

class EloquentStub extends Model
{
    protected $table = 'books';

    protected $fillable = ['title', 'description'];
}
