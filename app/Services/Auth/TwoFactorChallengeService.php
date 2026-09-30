<?php

namespace App\Services\Auth;

use App\Actions\Log\CreateSystemAccessLog;
use App\Models\User;

use App\Services\User\TrustedDeviceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TwoFactorChallengeService
{
    public function __construct(
        protected TrustedDeviceService $trustedDevices,
        protected CreateSystemAccessLog $auditLog,
    ) {}

    public function prepareLoginChallenge(User $user, bool $remember): void
    {
        session()->regenerate();

        session()->put([
            '2fa:user:id' => $user->id,
            '2fa:passed' => false,
            '2fa:remember' => $remember,
        ]);
        session()->forget('2fa:passed_at');
    }

    public function finishChallenge(User $user, Request $request): RedirectResponse
    {
        $remember = $request->session()->pull('2fa:remember', false);
            auth()->login($user, $remember);
            session()->regenerate();
            session()->put([
              '2fa:passed' => true,
              '2fa:passed_at' => now()->timestamp,
              ]);
            session()->forget(['2fa:user:id']);
            
        // log system access
        $this->auditLog->logAccess($user);

        $response = redirect()->intended(
            $user->is_admin || $user->hasPermission('Access') ? route('admin.panel') : route('home')
        );

        if ($request->boolean('trust_device')) {
            $response = $response->withCookie($this->trustedDevices->issue($user, $request));
        }

        toastr()->success('Logged in successfully', ['timeOut' => 1000]);

        return $response;
    }
}
