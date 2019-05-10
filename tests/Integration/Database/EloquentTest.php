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
        DB::insert(['collection' => 'books', 'document' => ['title' => 'Laravel', 'pages' => 100]]);
        DB::insert(['collection' => 'books', 'document' => ['title' => 'Framework', 'pages' => 50]]);
    }

    protected function tearDown(): void
    {
        DB::truncate('books');

        parent::tearDown();
    }

    public function testIt()
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
    }

    public function testAggregateCount()
    {
        $this->assertEquals(2, EloquentStub::count());
    }

    public function testAggregateSum()
    {
        $this->assertEquals(150, EloquentStub::sum('pages'));
        $this->assertEquals(50, EloquentStub::where('title', 'Framework')->sum('pages'));
    }

    public function testAggregateAvg()
    {
        $this->assertEquals(75, EloquentStub::avg('pages'));
        $this->assertEquals(50, EloquentStub::where('title', 'Framework')->avg('pages'));
    }
}

class EloquentStub extends Model
{
    protected $table = 'books';

    protected $fillable = ['title', 'description'];
}
