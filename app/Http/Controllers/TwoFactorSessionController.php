<?php

namespace App\Http\Controllers;

use App\Actions\TwoFactor\FinishTwoFactorChallengeAction;
use App\Actions\TwoFactor\RecoveryCodeAction;
use App\Contracts\Services\TwoFactorService as TwoFactorContract;
use App\Http\Requests\App\Auth\TwoFactorCodeRequest;
use App\Http\Requests\App\Auth\TwoFactorRecoveryRequest;
use App\Models\User;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TwoFactorSessionController extends Controller
{
    public function __construct(
        protected TwoFactorContract $twoFactor,
        protected RecoveryCodeAction $recoveryCodes,
        protected FinishTwoFactorChallengeAction $finishChallenge,
    ) {
        $this->middleware(function (Request $request, Closure $next) {
            if (! $request->session()->has('2fa:user:id')) {
                return redirect()->route('login');
            }

            return $next($request);
        });
    }

    public function showChallenge(): View
    {
        return view('auth.two-factor-challenge');
    }

    public function showRecovery(): View
    {
        return view('auth.recovery-two-factor');
    }

    public function verifyRecovery(TwoFactorRecoveryRequest $request): RedirectResponse
    {
        $user = $this->challengeUser();

        if (! $this->recoveryCodes->verify($user, $request->validated('recovery_code'))) {
            return back()->withErrors(['recovery_code' => 'Invalid recovery code']);
        }

        return $this->finishChallenge->handle($user, $request);
    }

    public function verifyChallenge(TwoFactorCodeRequest $request): RedirectResponse
    {
        $user = $this->challengeUser();

        if (! $user->two_factor_secret || ! $this->twoFactor->verify($user->two_factor_secret, $request->validated('code'))) {
            return back()->withErrors(['code' => 'Invalid authentication or recovery code.']);
        }

        return $this->finishChallenge->handle($user, $request);
    }

    private function challengeUser(): User
    {
        return User::findOrFail(session('2fa:user:id'));
    }
}
