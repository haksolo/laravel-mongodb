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
        DB::insert(['collection' => 'publishers', 'document' => ['book' => 'Laravel']]);
        DB::insert(['collection' => 'publishers', 'document' => ['book' => 'Framework']]);

        DB::insert(['collection' => 'books', 'document' => ['title' => 'Laravel', 'pages' => 100]]);
        DB::insert(['collection' => 'books', 'document' => ['title' => 'Framework', 'pages' => 50]]);
    }

    protected function tearDown(): void
    {
        DB::truncate('books');
        DB::truncate('publishers');

        parent::tearDown();
    }

    public function testIt()
    {
        $this->assertTrue(true);

        $model = EloquentStub::query()
            ->where('id', '=', 1)
            ->where('id', '=', 2)
            ->orWhere('name', '=', 'Ronald')
            ->orWhere('name', '=', 'Ronald')
            ->where('name', 'Ronald')
            ->first()
            ;

        // dd($model);

        // EloquentStub::query()->options([])->get();;

        /*$model = EloquentStub::query()
            ->where('id', '=', 1)
            ->where('id', '=', 2)
            /*->orWhere([
                ['first', '=', 1],
                'second' => 2,
                'third' => 3,
            ])*\/
            ->orWhere(function ($query) {
                return $query->where('id', 1);
            })
            ->first();

        dd($model);*/
    }

    public function testJoin()
    {

        $query = EloquentStub::query()
            ->join('publishers', 'publishers.book', '=', 'title')
            // ->get()
            ;

        // dump($query->toArray());
        // dump($query->toSql());

        $this->assertTrue(true);
    }

    /*public function testAggregateCount()
    {
        $this->assertEquals(2, EloquentStub::count());
    }*/

    public function testAggregateSum()
    {
        $this->assertEquals(150, EloquentStub::sum('pages'));
        $this->assertEquals(50, EloquentStub::where('title', 'Framework')->sum('pages'));
    }

    /*public function testAggregateAvg()
    {
        $this->assertEquals(75, EloquentStub::avg('pages'));
        $this->assertEquals(50, EloquentStub::where('title', 'Framework')->avg('pages'));
    }*/
}

class EloquentStub extends Model
{
    protected $table = 'books';

    protected $fillable = ['title', 'description'];
}
