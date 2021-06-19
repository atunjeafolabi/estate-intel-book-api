<?php

namespace App\Http\Resources;

use App\Helpers\Status;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    /**
     * @var bool
     */
    public $shouldIncludeId;

    public function __construct($resource, $shouldIncludeId = false)
    {
        $this->shouldIncludeId = $shouldIncludeId;
        parent::__construct($resource);
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $book = $this->resource;

        if (!$this->bookExists($book)) {
            return [];
        }

        $array = [];
        if ($this->shouldIncludeId) {
            $array['id'] = $book->id;
        };

        return $array = $array + [
            "name" => $this->name,
            "isbn" => $this->isbn,
            "authors" => $book->exists ? $this->authors->map(function ($author) {
                return $author->name;
            }) : $book->authors,
            "number_of_pages" => (int) $book->number_of_pages,
            "publisher" => $book->exists ? $book->publisher->name : $book->publisher,
            "country" => $book->exists ? $this->country->name : $book->country,
            "release_date" => $this->release_date
        ];
    }

    public function with($request)
    {
        $array = [];

        if ($book = $this->resource) {
            if ($book->wasChanged()) {
                $array['message'] = "The book '$book->name' was updated successfully";
            }
        }

        return $array + [
            "status_code" => Status::HTTP_OK,
            "status" => Status::SUCCESS,
        ];
    }

    /**
     * @param $book
     * @return bool
     */
    protected function bookExists($book)
    {
        return !is_null($book);
    }
}
