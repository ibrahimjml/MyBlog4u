<?php

namespace App\Services\Auth;

use App\Actions\TwoFactor\EnsureGlobalTwoFactorEnabled;
use App\Actions\TwoFactor\PrepareTwoFactorChallenge;
use App\Actions\TwoFactor\SkipChallengeForTrustedDevice;
use App\DTOs\Auth\LoginContext;
use App\DTOs\Auth\LoginUserDTO;
use App\Enums\Auth\LoginResults;
use App\Exceptions\Auth\LoginException;
use App\Models\User;
use Illuminate\Pipeline\Pipeline;

class LoginUserService
{
    public function handleLogin(LoginUserDTO $dto): LoginResults
    {
        if (! auth()->validate($dto->credentials())) {
            throw LoginException::invalidCredentials();
        }

        $user = $this->findUser($dto);

        $this->validateUser($user);

        $context = app(Pipeline::class)
            ->send(new LoginContext($dto, $user))
            ->through([
                EnsureGlobalTwoFactorEnabled::class,
                SkipChallengeForTrustedDevice::class,
                PrepareTwoFactorChallenge::class,
            ])
            ->thenReturn();

        return $context->result ?? LoginResults::SUCCESS;
    }

    private function findUser(LoginUserDTO $dto): User
    {
        return User::query()
            ->where($dto->loginType, $dto->login)
            ->first();
    }

    private function validateUser(User $user): void
    {
        if ($user->is_blocked) {
            throw LoginException::blocked();
        }

        if (! $user->activation?->completed) {
            throw LoginException::notActivated();
        }
    }
}
