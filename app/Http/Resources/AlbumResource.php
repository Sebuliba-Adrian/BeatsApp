<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AlbumResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            "title"=>$this->title,
            "released_on"=> $this->release_date,
            "genre" => $this->genre->name,
            "artist"=> $this->user,
        ];
    }
}
