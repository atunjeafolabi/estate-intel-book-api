<?php

namespace App\Http\Resources;

use App\Helpers\Status;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BookCollection extends ResourceCollection
{
    /**
     * @var bool
     */
    private $shouldIncludeId;

    public function __construct($resource, $shouldIncludeId = true)
    {
        $this->shouldIncludeId = $shouldIncludeId;
        parent::__construct($resource);
    }

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $this->collection->each(function ($bookResource) {
            $bookResource->shouldIncludeId = $this->shouldIncludeId;
        });

        return $this->collection;
    }

    public function with($request)
    {
        return [
            "status_code" => Status::HTTP_OK,
            "status" => "success",
        ];
    }
}
