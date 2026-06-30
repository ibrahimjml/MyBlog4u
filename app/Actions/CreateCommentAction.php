<?php

namespace App\Actions;

use App\DTOs\CreateCommentDTO;
use App\Models\Comment;

class CreateCommentAction
{
    public function execute(CreateCommentDTO $dto): Comment
    {
       return Comment::create($dto->toArray());
    }
}
