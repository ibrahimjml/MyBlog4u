<?php

namespace App\Actions\TwoFactor;

use App\Contracts\Services\TrustedDeviceService as TrustedDeviceContract;
use App\DTOs\Auth\LoginContext;
use App\Enums\Auth\LoginResults;
use Closure;

class SkipChallengeForTrustedDevice
{
    public function __construct(
        protected TrustedDeviceContract $trustedDevices,
        protected CompleteTwoFactorLoginAction $complete,
    ) {}

    public function handle(LoginContext $context, Closure $next): LoginContext
    {
        if (! $context->requiresTwoFactor()) {
            return $next($context);
        }

        if (! $this->trustedDevices->isTrusted(request(), $context->user)) {
            return $next($context);
        }

        $this->complete->handle($context->user, $context->dto->remember);
        $context->result = LoginResults::SUCCESS;

        return $next($context);
    }
}
