<?php

namespace App\DTOs;

class CreateCommentDTO
{
    public function __construct(
      public readonly string $content,
      public readonly int $userId,
      public readonly int $postId,
      public readonly ?int $parentId
    ){}

    public function toArray(): array
    {
        return [
            'content' => $this->content,
            'user_id' => $this->userId,
            'post_id' => $this->postId,
            'parent_id' => $this->parentId,
        ];
    }
}
