<?php

namespace App\Http\Resources;

use App\Helpers\Status;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $book = $this->resource;

        $array = [];
        if($book->exists) {
            $array['id'] = $book->id;
        };

        return $array = $array + [
            "name" => $this->name,
            "isbn" => $this->isbn,
            "authors" => $book->exists ? $this->authors->map(function($author){
                return $author->name;
            }) : $book->authors,
            "number_of_pages" => $this->number_of_pages,
            "publisher" => $book->exists ? $book->publisher->name : $book->publisher,
            "country" => $book->exists ? $this->country->name : $book->country,
            "release_date" => $this->release_date
        ];
//        return parent::toArray($request);
    }
}
