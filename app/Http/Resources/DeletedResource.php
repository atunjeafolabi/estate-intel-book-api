<?php

namespace App\Http\Resources;

use App\Helpers\Status;
use Illuminate\Http\Resources\Json\JsonResource;

class DeletedResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "status_code" => Status::HTTP_NO_CONTENT,
            "status" => Status::SUCCESS,
            "message" => "The book ‘ $this->name ’ was deleted successfully",
            "data" => []
        ];
//        return parent::toArray($request);
    }
}
