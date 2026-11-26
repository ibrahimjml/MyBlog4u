<?php

namespace App\Actions\TwoFactor;

use App\Contracts\Services\TrustedDeviceService as TrustedDeviceContract;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FinishTwoFactorChallengeAction
{
    public function __construct(
        protected TrustedDeviceContract $trustedDevices,
        protected CompleteTwoFactorLoginAction $complete,
    ) {}

    public function handle(User $user, Request $request): RedirectResponse
    {
        $remember = (bool) $request->session()->pull('2fa:remember', false);

        $this->complete->handle($user, $remember);

        $response = redirect()->intended(
            $user->is_admin ? route('admin.panel') : route('home')
        );

        if ($request->boolean('trust_device')) {
            $response = $response->withCookie($this->trustedDevices->issue($user, $request));
        }
        
        $request->session()->forget([
            '2fa:user:id',
            '2fa:remember'
        ]);

        toastr()->success('Logged in successfully', ['timeOut' => 1000]);

        return $response;
    }
}
