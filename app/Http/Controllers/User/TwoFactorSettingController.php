<?php

namespace App\Http\Controllers\User;

use App\Actions\TwoFactor\CreateTwoFactorAction;
use App\Actions\TwoFactor\DisableTwoFactorAction;
use App\Actions\TwoFactor\EnableTwoFactorAction;
use App\Actions\TwoFactor\GenerateQrCodeAction;
use App\Actions\TwoFactor\RecoveryCodeAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\App\Auth\DisableTwoFactorRequest;
use App\Http\Requests\App\Auth\TwoFactorCodeRequest;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TwoFactorSettingController extends Controller
{

    public function __construct(
        protected CreateTwoFactorAction $create,
        protected GenerateQrCodeAction $generateQrCode,
        protected EnableTwoFactorAction $enable,
        protected DisableTwoFactorAction $disable,
        protected RecoveryCodeAction $recoveryCodes,
    ) {
        $this->middleware(['auth', 'verified']);
    }

    public function two_factor_view(): View
    {
        return view('profile-settings.security2fa', [
            'user' => request()->user(),
            'twoFactorGloballyEnabled' => (bool) Setting::get('two_factor_enabled', true),
        ]);
    }

    public function enable2fa(Request $request): View
    {
        $secret = $this->create->handle($request->user());
        $qr = $this->generateQrCode->handle($request->user(), $secret);

        return view('profile-settings.partials.enable-two-factor', [
            'qr' => $qr,
            'secret' => $secret,
        ]);
    }

    public function confirmTwofactor(TwoFactorCodeRequest $request): JsonResponse
    {
        $this->enable->handle($request->user(), $request->validated('code'));

        return response()->json(['success' => true]);
    }

    public function downloadRecoveryCodes(Request $request): StreamedResponse
    {
        return $this->recoveryCodes->download($request->user());
    }

    public function regenerate(Request $request): JsonResponse
    {
        $this->recoveryCodes->regenerate($request->user());

        return response()->json([
            'success' => true,
            'message' => 'Recovery codes regenerated',
        ]);
    }

    public function disable2fa(DisableTwoFactorRequest $request): JsonResponse
    {
        $this->disable->handle($request->user(), $request->validated('code'), $request);

        return response()->json([
            'success' => true,
            'message' => 'Two factor authentication disabled',
        ]);
    }
}
