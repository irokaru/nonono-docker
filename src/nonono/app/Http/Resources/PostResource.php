<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if (!isset($this->release_flag)) {
            // 一覧表示用
            return [
                'id'       => $this->id,
                'title'    => $this->title,
                'date'     => $this->date,
                'categories' => PostCategoryResource::collection($this->post_categories),
            ];
        } else {
            return [
                'id'           => $this->id,
                'title'        => $this->title,
                'date'         => $this->date,
                'categories'   => PostCategoryResource::collection($this->post_categories),
                'detail'       => static::getPostContent($this->id),
                'release_flag' => $this->release_flag,
            ];
        }
    }

    // -----------------------------------------------------------------

    /**
     * idから本文ファイルの中身を引き当てる
     * @param int $id
     * @return string
     */
    public static function getPostContent($id): string
    {
        try {
            return Storage::disk('local')->get("posts/$id.md");
        } catch (\Illuminate\Contracts\Filesystem\FileNotFoundException $e) {
            return '';
        }
    }
}
