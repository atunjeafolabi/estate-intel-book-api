<?php


namespace App\Helpers;

use App\Models\Book;
use Illuminate\Support\Collection;

class Transformer
{
    /**
     * Transform the book information sent when creating or updating a book
     *
     * @param $book
     * @param $publisher
     * @param $country
     * @return array
     */
    public static function transformBook($book, $publisher, $country) : array
    {
        return [
            "name" => $book['name'],
            "isbn" => $book['isbn'],
            "number_of_pages" => $book['number_of_pages'],
            "publisher_id" => $publisher->id,
            "country_id" => $country->id,
            "release_date" => $book['release_date'],
        ];
    }

    /**
     * @param $response
     * @return Collection
     */
    public static function transformBooksFromExternalApi($response)
    {
        $books = [];

        if (empty($response)) {
            return collect($books);
        }

        // Check if response is not multi-dimensional array
        if (!self::isMultidimensional($response)) {
            $book = new Book();
            $book->name = $response['name'];
            $book->isbn = $response['isbn'];
            $book->number_of_pages = $response['numberOfPages'];
            $book->authors = $response['authors'];
            $book->publisher = $response['publisher'];
            $book->country = $response['country'];
            $book->release_date = $response['released'];

            return $book;
        }

        foreach ($response as $r) {
            $book = new Book();
            $book->name = $r['name'];
            $book->isbn = $r['isbn'];
            $book->number_of_pages = $r['numberOfPages'];
            $book->authors = $r['authors'];
            $book->publisher = $r['publisher'];
            $book->country = $r['country'];
            $book->release_date = $r['released'];

            $books[] = $book;
        }

        return collect($books);
    }

    private static function isMultidimensional($response)
    {
        return is_array(
            $response[array_key_first($response)]
        );
    }
}
