<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GameResource extends JsonResource
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
            'title'          => $this->title,
            'release_date'   => $this->release_date,
            'thumbnail_path' => $this->thumbnail_path,
            'category'       => $this->category,
            'infomation'     => $this->infomation,
            'url'            => $this->url,
        ];
    }
}
