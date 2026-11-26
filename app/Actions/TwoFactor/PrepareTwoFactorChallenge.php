<?php

namespace App\Actions\TwoFactor;

use App\DTOs\Auth\LoginContext;
use App\Enums\Auth\LoginResults;
use Closure;

class PrepareTwoFactorChallenge
{
    public function __construct(
        protected CompleteTwoFactorLoginAction $complete,
    ) {}

    public function handle(LoginContext $context, Closure $next): LoginContext
    {
        if ($context->result !== null) {
            return $next($context);
        }

        if (! $context->requiresTwoFactor()) {
            $this->complete->handle($context->user, $context->dto->remember);
            $context->result = LoginResults::SUCCESS;

            return $next($context);
        }

        $context->result = LoginResults::TWO_FACTOR_REQUIRED;

        session()->regenerate();
        session()->put([
           '2fa:user:id'  => $context->user->id,
           '2fa:remember' => $context->dto->remember,
        ]);

        return $next($context);
    }
}
