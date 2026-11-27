<?php

namespace App\Http\Controllers\User;

use App\Actions\ActiveSession\LogoutSessionAction;
use App\Http\Controllers\Controller;
use App\Http\Middleware\CheckIfBlocked;
use App\Models\ActiveSession;
use App\Support\BrowserTokenSupport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SessionsSettingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified', CheckIfBlocked::class]);
    }

    public function active_sessions(Request $request)
    {
        $user = $request->user();
        $currentSessionId = $request->session()->getId();
        $currentBrowserToken = BrowserTokenSupport::fromRequest($request);

        $sessions = $user->activeSessions()
            ->active()
            ->orderByDesc('last_active_at')
            ->get();

        $currentSessionKey = $user->activeSessions()
            ->currentBrowser($currentSessionId, $currentBrowserToken)
            ->value('id');

        $sessions->each(function (ActiveSession $session) use ($currentSessionKey): void {
            $session->is_current = $session->getKey() === $currentSessionKey;
        });

        return view('profile-settings.active-sessions', [
            'user' => $user,
            'sessions' => $sessions,
        ]);
    }

    public function logout_session(ActiveSession $activeSession, Request $request, LogoutSessionAction $action): RedirectResponse
    {
        abort_unless(
            $activeSession->user_id === $request->user()->id && is_null($activeSession->logged_out_at),
            404
        );

        return $action->handle($activeSession, $request);
    }

}
