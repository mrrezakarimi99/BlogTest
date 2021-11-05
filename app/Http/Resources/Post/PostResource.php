<?php

namespace App\Http\Resources\Post;

use App\Http\Resources\User\UserResource;
use App\Models\User;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @method getThumbnailLinkAttribute()
 * @property mixed $id
 * @property mixed $user
 * @property mixed $content
 * @property mixed $title
 */
class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id'             => $this->id,
            'title'          => $this->title,
            'content'        => $this->content,
            'user'           => new UserResource($this->user),
            'thumbnail_link' => $this->getThumbnailLinkAttribute()
        ];
    }
}
