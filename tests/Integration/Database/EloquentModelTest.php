<?php

namespace Extended\MongoDB\Tests\Integration\Database;

use Extended\MongoDB\Database\Eloquent\Model;
use Extended\MongoDB\Support\Facades\DB;

class EloquentModelTest extends TestCase
{
    protected function tearDown(): void
    {
        DB::table('books')->truncate();

        parent::tearDown();
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
