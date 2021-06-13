<?php


namespace App\Helpers;


use App\Http\Resources\BookCollection;
use App\Http\Resources\BookResource;
use JsonSerializable;

class Response implements JsonSerializable
{
    /**
     * @var BookCollection|BookResource
     */
    private $resource;

    public function __construct($resource)
    {
        $this->resource = $resource;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return [
            "status_code" => Status::HTTP_OK,
            "status" => Status::SUCCESS,
            "data" => $this->getResourceResponseData()
        ];
    }

    /**
     * Gets data in the appropriate format for response
     *
     * @return mixed
     */
    protected function getResourceResponseData()
    {
//        dd($this->resource->response()->getData());
        return $this->resource ? $this->resource->response()->getData() : [];
    }
}
