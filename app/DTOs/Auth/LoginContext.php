<?php

namespace App\DTOs\Auth;

use App\Enums\Auth\LoginResults;
use App\Models\User;

class LoginContext
{
    public bool $globalTwoFactorEnabled = true;

    public ?LoginResults $result = null;

    public function __construct(
        public readonly LoginUserDTO $dto,
        public readonly User $user,
    ) {}

    public function requiresTwoFactor(): bool
    {
        return $this->globalTwoFactorEnabled && $this->user->has_two_factor_enabled;
    }
}
