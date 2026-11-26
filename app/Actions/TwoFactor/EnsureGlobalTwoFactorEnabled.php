<?php

namespace App\Actions\TwoFactor;

use App\DTOs\Auth\LoginContext;
use App\Models\Setting;
use Closure;

class EnsureGlobalTwoFactorEnabled
{
    public function handle(LoginContext $context, Closure $next): LoginContext
    {
        $context->globalTwoFactorEnabled = (bool) Setting::get('two_factor_enabled', true);

        return $next($context);
    }
}
