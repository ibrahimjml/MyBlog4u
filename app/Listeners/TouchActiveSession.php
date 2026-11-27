<?php

namespace App\Listeners;

use App\Events\ActiveSessionActivityEvent;
use App\Models\ActiveSession;
use App\Support\BrowserTokenSupport;
use App\Support\UserAgentSupport;

class TouchActiveSession
{
    public function handle(ActiveSessionActivityEvent $event): void
    {
        $request = request();

        if (! $request->hasSession()) {
            return;
        }

        $sessionId = $request->session()->getId();

        if (! $sessionId) {
            return;
        }

        $hashedToken = BrowserTokenSupport::hash(BrowserTokenSupport::fromRequest($request));

        $session = ActiveSession::query()
            ->currentSession($event->user, $hashedToken)
            ->first();

        [$browser, $platform] = UserAgentSupport::parse($request->userAgent());

        $attributes = [
            'session_id' => $sessionId,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'browser' => $browser,
            'platform' => $platform,
            'last_active_at' => now(),
            'logged_out_at' => null,
        ];

        if ($session) {
            $session->update($attributes);
        }
    }
}
