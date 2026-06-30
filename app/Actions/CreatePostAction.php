<?php

namespace App\Actions;

use App\DTOs\CreatePostDTO;
use App\Enums\PostStatus;
use App\Models\Post;

class CreatePostAction
{
    public function execute(CreatePostDTO $dto, $image_path, $status)
    {
      return Post::create(array_merge($dto->toArray(),
            [
              'image_path' => $image_path,
              'status' => $status,
              'published_at' => $status === PostStatus::PUBLISHED ? now() : null,
            ]
          )
        );
    }
}
