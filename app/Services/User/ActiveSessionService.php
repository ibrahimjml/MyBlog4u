<?php

namespace App\Services\User;

use App\Contracts\Services\ActiveSessionService as ActiveSessionContract;
use App\Models\ActiveSession;
use App\Models\RevokedBrowserSession;
use App\Models\User;
use App\Support\UserAgentSupport;
use Illuminate\Http\Request;

class ActiveSessionService implements ActiveSessionContract
{
    public function record(User $user, Request $request, string $browserToken): void
    {
        $sessionId = $request->session()->getId();

        if (! $sessionId) {
            return;
        }

        [$browser, $platform] = UserAgentSupport::parse($request->userAgent());

        ActiveSession::updateOrCreate(
            [
                'user_id' => $user->id,
                'browser_token' => hash('sha256', $browserToken),
            ],
            [
                'session_id' => $sessionId,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'browser' => $browser,
                'platform' => $platform,
                'last_active_at' => now(),
                'logged_out_at' => null,
            ]
        );
    }

    public function isBrowserRevoked(string $browserToken): bool
    {
        return RevokedBrowserSession::query()
            ->where('browser_token', hash('sha256', $browserToken))
            ->exists();
    }

    public function isRevoked(User $user, string $sessionId): bool
    {
        return ActiveSession::query()
            ->where('user_id', $user->id)
            ->where('session_id', $sessionId)
            ->whereNotNull('logged_out_at')
            ->exists();
    }

    public function logout(ActiveSession $session, Request $request): void
    {
        $request->session()->getHandler()->destroy($session->session_id);

        if ($session->browser_token) {
            RevokedBrowserSession::updateOrCreate(
                ['browser_token' => $session->browser_token],
                ['user_id' => $session->user_id, 'revoked_at' => now()],
            );
        }

        $session->update(['logged_out_at' => now()]);
    }
}
