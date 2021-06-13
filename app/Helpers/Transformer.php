<?php


namespace App\Helpers;


use App\Models\Book;
use Illuminate\Support\Collection;

class Transformer
{
    public static function transformBook($book, $publisher, $country)
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

    public static function transformBooksFromExternalApi($response) : Collection
    {
        $books = [];

        foreach($response as $r){
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
}
