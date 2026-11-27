<?php

namespace App\Actions\ActiveSession;

use App\Contracts\Services\ActiveSessionService as ActiveSessionContract;
use App\Models\ActiveSession;
use App\Support\BrowserTokenSupport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LogoutSessionAction
{
    public function __construct(
        private readonly ActiveSessionContract $sessions,
        private readonly LogoutCurrentDeviceAction $logoutCurrentDevice,
    ) {
    }

    public function handle(ActiveSession $activeSession, Request $request): RedirectResponse
    {
        $isCurrentSession = $activeSession->isCurrentBrowser(
            $request->session()->getId(),
            BrowserTokenSupport::fromRequest($request),
        );

        $this->sessions->logout($activeSession, $request);

        if ($isCurrentSession) {
            $this->logoutCurrentDevice->handle($request);

            toastr()->success('Logged out success.', ['timeOut' => 1000]);

            return redirect()->route('login')->withCookie(BrowserTokenSupport::forgetCookie());
        }

        toastr()->success('Browser session logged out.', ['timeOut' => 1000]);

        return back();
    }
}
