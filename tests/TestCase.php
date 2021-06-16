<?php

namespace Tests;

use App\Helpers\Status;
use App\Models\Author;
use App\Models\Book;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Http;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected static $booksTobeReturnedByExternalApi = [
        [
            "url" => "https://www.external-test-api.com/api/books/1",
            "name" => "Awesome Book",
            "isbn" => "333444",
            "authors" => [
                "Mathew Alao",
                "Benson Paul"
            ],
            "numberOfPages" => 100,
            "publisher" => "New Age Publishers",
            "country" => "Nigeria",
            "mediaType" => "Hardcover",
            "released" => "1999-04-10",
            "characters" => [
                "https://www.anapioficeandfire.com/api/characters/2",
                "https://www.anapioficeandfire.com/api/characters/12",
                "https://www.anapioficeandfire.com/api/characters/13",
                "povCharacters" => [
                    "https://www.anapioficeandfire.com/api/characters/148",
                    "https://www.anapioficeandfire.com/api/characters/208",
                    "https://www.anapioficeandfire.com/api/characters/232",
                    "https://www.anapioficeandfire.com/api/characters/339",
                ]
            ]
        ],
        [
            "url" => "https://www.external-test-api.com/api/books/2",
            "name" => "The Richest Man in Babylon",
            "isbn" => "333444",
            "authors" => [
                "Robert Kiyosaki",
            ],
            "numberOfPages" => 400,
            "publisher" => "Salam Publishers",
            "country" => "USA",
            "mediaType" => "Hardcover",
            "released" => "2012-04-10",
            "characters" => [
                "https://www.anapioficeandfire.com/api/characters/4",
                "https://www.anapioficeandfire.com/api/characters/2",
                "https://www.anapioficeandfire.com/api/characters/9",
                "povCharacters" => [
                    "https://www.anapioficeandfire.com/api/characters/148",
                    "https://www.anapioficeandfire.com/api/characters/208",
                    "https://www.anapioficeandfire.com/api/characters/232",
                    "https://www.anapioficeandfire.com/api/characters/339",
                ]
            ]
        ]
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|mixed
     */
    protected function generateBooks($count)
    {
        $bookFactory = Book::factory()
            ->has(Author::factory()->count(3));

        if ($count === 1) {
            $book =  $bookFactory->create();
            return $book;
        }

        $books = $bookFactory->count($count)->create();

        return $books;
    }

    protected function fakeExternalApi($nameOfBook = null)
    {
        Http::fake([
            env('EXTERNAL_API') =>
                Http::response(self::$booksTobeReturnedByExternalApi, Status::HTTP_OK),
            env('EXTERNAL_API') . "?name=" . rawurlencode($nameOfBook) =>
                Http::response(self::searchBookToBeReturnedByExternalApi($nameOfBook), Status::HTTP_OK),
        ]);
    }

    protected static function searchBookToBeReturnedByExternalApi($nameOfBook)
    {
        foreach (self::$booksTobeReturnedByExternalApi as $book) {
            return ($book['name'] === $nameOfBook) ?  [$book] : [];
        }
    }

    public function bookInfoDataProvider()
    {
        return [[
            [
                "name" => "A Beam of Hope",
                "isbn" => "5557",
                "authors" => [
                    "Segun Agunbiade",
                    "Oliseh Kwam"
                ],
                "publisher" => "Awesome Publishers",
                "country" => "Togo",
                "number_of_pages" => 900,
                "release_date" => "1997-02-21"
            ]
        ]];
    }
}
