<?php


namespace App\Helpers;


class RequestTransformer
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
}
