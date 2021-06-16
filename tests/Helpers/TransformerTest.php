<?php

namespace Tests\Unit;

use App\Helpers\Transformer;
use App\Models\Country;
use App\Models\Publisher;
use PHPUnit\Framework\TestCase;

class TransformerTest extends TestCase
{
    public function test_transform_book()
    {
        $book = [
            'name' => 'Sam Ken',
            'isbn' => '222-33333333',
            'number_of_pages' => 100,
            'release_date' => '1992-04-01'
        ];

        $publisher = new Publisher();
        $publisher->id = 1;

        $country = new Country();
        $country->id = 1;

        $this->assertSame([
            "name" => $book['name'],
            "isbn" => $book['isbn'],
            "number_of_pages" => $book['number_of_pages'],
            "publisher_id" => $publisher->id,
            "country_id" => $country->id,
            "release_date" => $book['release_date'],
        ], Transformer::transformBook($book, $publisher, $country));
    }
}
