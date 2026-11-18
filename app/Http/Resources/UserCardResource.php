<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserCardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
         return [
            'name'        => $this->name,
            'username'    => $this->username,
            'avatar_url'  => $this->avatar_url,
            'bio'         => $this->bio,
            'url'         => route('profile.home', $this->username),
            'followers_count'       => $this->whenCounted('followers'),
            'followings_count'      => $this->whenCounted('followings'),
            'published_posts_count' => $this->when(
                        ! is_null($this->published_posts_count),
                        fn () => (int) $this->published_posts_count
                    ),
            'likes_count' => $this->when(
                ! is_null($this->posts_likes_count),
                fn () => (int) $this->posts_likes_count
            ),
        ];
    }
}
