<?php

namespace App\Actions\ActiveSession;

use App\Models\RevokedBrowserSession;
use App\Support\BrowserTokenSupport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class KickRevokedBrowserAction
{
    public function __construct(
        private readonly LogoutCurrentDeviceAction $logoutCurrentDevice,
    ) {}

    public function handle(Request $request): ?RedirectResponse
    {
        $browserToken = BrowserTokenSupport::fromRequest($request);

        if ($browserToken === null) {
            return null;
        }

        $isRevoked = RevokedBrowserSession::query()
            ->where('browser_token', BrowserTokenSupport::hash($browserToken))
            ->exists();

        if (! $isRevoked) {
            return null;
        }

        $this->logoutCurrentDevice->handle($request);

        toastr()->warning('This browser session has been logged out.', ['timeOut' => 2000]);

        return redirect()->route('login')->withCookie(BrowserTokenSupport::forgetCookie());
    }
}
