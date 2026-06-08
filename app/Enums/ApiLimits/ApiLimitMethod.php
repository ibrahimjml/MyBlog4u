<?php

namespace App\Enums\ApiLimits;

enum ApiLimitMethod: string
{
    case ALL = 'all';
    case GET = 'get';
    case POST = 'post';
    case PUT = 'put';
    case DELETE = 'delete';

    public function label(): string
    {
        return match($this) {
            self::ALL => 'All (*)',
            self::GET => 'GET',
            self::POST => 'POST',
            self::PUT => 'PUT',
            self::DELETE => 'DELETE',
        };
    }
}
