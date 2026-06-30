<?php

namespace App\Exceptions;

use Exception;

class CommentException extends Exception
{
    public static function commentDisabled(): self
    {
        return new self("Comments disabled");
    }
}
