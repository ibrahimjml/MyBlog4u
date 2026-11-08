<?php

namespace App\Enums;

enum UserActivityType: string
{
     case Posted    = 'Posted';
     case Commented = 'Commented';
     case Replied   = 'Replied';

    public function label(): string
    {
        return match ($this) {
            self::Posted    => 'Published an article',
            self::Commented => 'Commented on an article',
            self::Replied   => 'Replied to a comment',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::Posted    => 'fas fa-pen',
            self::Commented => 'far fa-comment-dots',
            self::Replied   => 'fas fa-reply',
        };
    }

    public function accent(): string
    {
        return match ($this) {
            self::Posted    => 'text-primary',
            self::Commented => 'text-muted-foreground',
            self::Replied   => 'text-muted-foreground',
        };
    }

    public function isComment(): bool
    {
        return $this === self::Commented || $this === self::Replied;
    }

    public function anchor(?int $commentId = null): string
    {
    return $this->isComment() && $commentId
        ? "#comment-{$commentId}"
        : '';
    }
}
