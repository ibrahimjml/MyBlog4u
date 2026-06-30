<?php

namespace App\Exceptions;

use Exception;

class CommentReplyException extends Exception
{
    public static function ownSelfReply(): self
    {
        return new self("Cannot reply to your own reply.");
    }

    public static function maxRepliesAllowed(int $allowed): self
    {
        return new self("Maximum {$allowed} replies allowed.");
    }
    public static function replyOnlyOnce(): self
    {
        return new self("You can only reply once on this comment.");
    }
}
