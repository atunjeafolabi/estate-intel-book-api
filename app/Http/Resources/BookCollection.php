<?php

namespace App\Http\Resources;

use App\Helpers\Status;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BookCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection->map(function($bookResource){
                $book = $bookResource->resource;

                $array = [];
                if($book->exists) {
                    $array['id'] = $book->id;
                };

                $array = $array + [
                    "name" => $book->name,
                    "isbn" => $book->isbn,
                    "authors" => $book->exists ? $book->authors->map(function($author){
                        return $author->name;
                    }) : $book->authors,
                    "number_of_pages" => $book->number_of_pages,
                    "publisher" => $book->exists ? $book->publisher->name : $book->name,
                    "country" => $book->exists ? $book->country->name : $book->name,
                    "release_date" => $book->release_date
                    ];

                return $array;
            });
//        return parent::toArray($request);
    }
}
