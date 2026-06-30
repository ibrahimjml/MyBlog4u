<?php

namespace App\Actions;

use App\DTOs\CreateCommentDTO;
use App\Models\Comment;

class UpdateCommentAction
{
    public function update(Comment $comment,CreateCommentDTO $dto)
    {
      return $comment->update($dto->toArray());
    }
}
