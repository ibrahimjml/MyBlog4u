<?php

namespace App\Listeners;

use App\Events\SessionLoggedOutEvent;
use App\Support\BrowserTokenSupport;

class MarkSessionLoggedOut
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(SessionLoggedOutEvent $event): void
    {
        $request = request();
        $user = $event->user;

        $browserToken = BrowserTokenSupport::fromRequest($request);

        if ($user) {
            $currentSession = $user->activeSessions()
                ->active()
                ->currentBrowser($request->session()->getId(), $browserToken)
                ->first();

            if ($currentSession) {
                $currentSession->update(['logged_out_at' => now()]);
            }
        }
    }
}
